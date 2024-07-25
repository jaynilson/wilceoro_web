<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Fleet;
use App\Models\FleetRecord;
use App\Models\FleetTool;
use App\Models\Record;
use App\Models\Tool;
use App\Models\Service;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\RecordCategory;
use App\Models\FleetCustomField;
use App\Models\FleetCustomRow;
use App\Models\FleetCustom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Psy\Command\WhereamiCommand;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SICAP;
class FleetManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function services()
    {
        Service::whereNull('status')->update(['status'=>'Unassigned']);//TODO remove this later absolutely
        Service::where('status', '')->update(['status'=>'Unassigned']);//TODO remove this later absolutely
        $status = Service::distinct('status')->pluck('status');
        return view('fleet_manager_services', compact('status'));
    }

    public function service($id){
        $service=Service::where('id',$id)->first();
        $fleet = Fleet::where('id',$service->id_fleet)->first();
        $driver= User::where('id',$service->id_employee)->first();
        $checkIn = CheckIn::where('id_fleet',$fleet->id)->first();
        $checkOut = CheckOut::where('id_fleet',$fleet->id)->first();
        $odometer=0;
        if($checkIn != null && $checkOut !=null){
            if($checkIn->id_check_out==$checkOut->id){
                $odometer=$checkIn->odometer_reading;
            }else{
                $odometer=$checkOut->odometer_reading;  
            }
        }else if($checkIn != null && $checkOut == null){
            $odometer=$checkIn->odometer_reading;
        }else if($checkIn == null && $checkOut != null){
            $odometer=$checkOut->odometer_reading;
        }
        $cost=0;
        $hours=0;
        $records=Record::where("id_service",$id)->get();
        foreach ($records as $key => $value){
            $price = 0;
            if($value->id_tool){
                $tool = Tool::find($value->id_tool);
                $price = $tool->price;
            }
            $cost = $cost + $value->cost + $price;
            $hours = $hours + $value->hour_spend;
        }
        $tools = Tool::where('department', 'shop')->get();
        $categories = RecordCategory::all();
        return view('fleet_manager_service_detail', compact('service', 'fleet', 'driver', 'odometer', 'cost', 'tools', 'hours', 'categories'));
    }

    public function fleetDetail($id){
        //FleetCustom::where('id','>',0)->delete();
        $fleet = Fleet::where("id", $id)->first();
        $last_checkout = CheckOut::where('id_fleet', $id)->where('status','in_process')->first();
        $current_driver = "";
        if($last_checkout){
            $userTmp =  User::where("id", $last_checkout->id_employee)->first();
            $current_driver = $userTmp->name." ".$userTmp->last_name;
        }
        $registration_date = Carbon::createFromFormat('Y-m-d H:i:s', $fleet->created_at)->format('Y-m-d');
        $files = Asset::where('type', 'fleet_detail')->where('id_reference', $id)->get();
        $custom_fields = FleetCustomField::where('status','true')->get();
        for($i=0;$i<count($custom_fields);$i++){
            $custom = FleetCustom::where('id_fleet',$id)->where('id_field',$custom_fields[$i]->id)->get();
            if(count($custom))
                $custom_fields[$i]->value = $custom[0];
            else
                $custom_fields[$i]->value = null;
        }
        
        $manager = User::where('id', $fleet->id_manager)->first();
        $fleet->manager_name = $manager?($manager->name." ".$manager->last_name):'';
        
        $id_check_out = 0;
        $checkouts = CheckOut::where('id_fleet', $fleet->id)->get();
        foreach($checkouts as $checkout){
            $checkin = CheckIn::where('id_check_out', $checkout->id)->first();
            if(!$checkin){
                $id_check_out = $checkout->id;
                break;
            }
        };

        return view('fleet_detail', compact('fleet', 'current_driver', 'registration_date', 'files', 'custom_fields', 'id_check_out'));
    }

    public function fleetDetailUpdate(Request $request){
        $fleet = Fleet::where("id",$request->id)->first();
        if($request->has('department'))$fleet->department = $request->department;
        if($request->has('model'))$fleet->model = $request->model;
        if($request->has('make'))$fleet->make = $request->make;
        if($request->has('licence_plate'))$fleet->licence_plate = $request->licence_plate;
        if($request->has('vin'))$fleet->vin = $request->vin;
        if($request->has('price'))$fleet->price = $request->price;
        if($request->has('insurance_expiration_date'))$fleet->insurance_expiration_date = SICAP::decodeDate($request->insurance_expiration_date);
        if($request->has('last_odometer'))$fleet->last_odometer = $request->last_odometer;
        if($request->has('category'))$fleet->category = $request->category;
        if($request->has('current_yard_location')&&$request->current_yard_location!="OTHER"){
            $fleet->current_yard_location= $request->current_yard_location;
        }
        if($request->has('lease_rental_return_date'))$fleet->lease_rental_return_date = SICAP::decodeDate($request->lease_rental_return_date);
        if($request->has('required_cdl'))$fleet->required_cdl = $request->required_cdl;
        if($request->has('id_manager'))$fleet->id_manager = $request->id_manager;
        if($request->has('is_main_field')&&$request->is_main_field==1){
            $fleet->insurance_expiration_reminder = $request->insurance_expiration_reminder=='on';
            $fleet->lease_rental_return_reminder = $request->lease_rental_return_reminder=='on';
            $fleet->registration_reminder = $request->registration_reminder=='on';
        }
        $fleet->save();

        $path = '';
        $pictureName = "";
        if ($request->hasFile('picture_upload')) {
            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;
            $pictureName = $fleet->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 95);
            $path = "images/fleet/$pictureName";
            Storage::disk('public')->put($path, $pictureObj->stream());
            Fleet::findOrFail($fleet->id)->update(['picture' =>  $pictureName]);
        }

        if($path!='') $path="/storage/".$path;
        ActivityLog::create([
            'type' => 0,
            'title' => 'Vehicle has updated',
            'desc' => "n: ".$fleet->n.", model:  ".$fleet->model.", licence: ".$fleet->licence_plate.", location: ".$fleet->yard_location.", department:".$fleet->department,
            'href' => "/fleet_detail/".$fleet->id,
            'id_reference' => $fleet->id,
            'type_sql' => 1,
            'file_name' => $pictureName,
            'file_url' => $path,
        ]);

        // add email reminder
        // $fleet->insurance_expiration_date
        // $fleet->lease_rental_return_date
        // $fleet->created_at
        // TODO add reminder schedule

        return Redirect::back()->with('success', 'Element saved successfully.');
    }

    public function customDataTable($id, Request $request){
        $data = new FleetCustomRow();
        $data = $data->getDataTable($id, $request);
        return response()->json($data);
    }

    public function customSave($id, Request $request){
        // $id = $request->input('id');
        if($request->has('custom_row_id')){
            $custom_fields = FleetCustomField::get();
            $custom_row_id = $request->custom_row_id;
            if($custom_row_id==0){
                $row = new FleetCustomRow();
                $row->id_fleet = $id;
                $row->save();
                $custom_row_id = $row->id;
            }else{
                $row = FleetCustomRow::find($custom_row_id);
            }
            $customs = [];
            foreach($custom_fields as $field){
                if(!$request->has($field->name)) continue;
                $custom = FleetCustom::where('id_fleet',$custom_row_id)->where('id_field',$field->id)->get();
                if(count($custom)){
                    $custom = $custom[0];
                }else{
                    $custom = new FleetCustom;
                    $custom->id_fleet = $custom_row_id;
                    $custom->id_field = $field->id;
                    $custom->created_at = now();
                }
                $custom["value_".$field->type] = $request[$field->name];
                //TODO
                // if($field->type=='string') $custom->value_string = $request[$field->name];
                // else if($field->type=='number') $custom->value_integer = $request[$field->name];
                // else if($field->type=='boolean') $custom->value_boolean = $request[$field->name];
                // else if($field->type=='date'&&$request[$field->name]) $custom->value_date = $request[$field->name];
                $custom->updated_at = now();
                $custom->save();

                $customs[] = $custom;
            }
        }

        return response()->json(["Custom Row" => $row, "Customs" => $customs], 200);
    }

    public function customDelete($id, $row_id){
        FleetCustom::where('id_fleet', $row_id)->delete();
        $row = FleetCustomRow::find($row_id);
        if(!$row) return response()->json($e->getMessage(),400);
        $row->delete();
        return response()->json(["record" => $id], 200);
    }

    public function driverHistoryDataTable(Request $request){
        $data = new CheckOut();
        $data = $data->getDataTableDriverHistory($request);
        return response()->json($data);
    }

    public function maintenanceDataTable(Request $request){
        $data = new Service();
        $data = $data->getDataTableMaintenance($request);
        return response()->json($data);
    }

    public function fleetDocument(Request $request){
        if(!$request['file'])
        return Redirect::back()->withErrors(['msg' => 'Required file']);

        $myFile=$request['file'];
        $file_record =  $request['file'];
       
        $filename = $file_record->getClientOriginalName();
        
        Storage::disk("public")->put("files_fleet/$filename",  file_get_contents($file_record));

        $file= new Asset();
        $file->type="fleet_detail";
        $file->picture=$filename;
        $file->title=$request->title;
        $file->id_reference=$request->id_fleet;
        $file->ext = strtolower($file_record->extension());
        $file->save();

        $fleet = Fleet::find($request->id_fleet);
        ActivityLog::create([
            'type' => 0,
            'title' => 'Fleet has updated',
            'desc' => "New document file ".$file->title." has attached for vehicle with n ".$fleet->n."",
            'href' => "/fleet_detail/".$fleet->id,
            'id_reference' => $fleet->id,
            'type_sql' => 1,
            'file_name' => $filename,
            'file_url' => "/storage/files_fleet/$filename",
        ]);

        return Redirect::back()->with('success', 'Element saved successfully.');
    }

    public function fleetDocumentDelete(Request $request){
        $asset = Asset::where('id',$request->id)->first();
        Storage::disk('public')->delete("files_fleet/".$asset->picture);
        $fleet = Fleet::find($asset->id_reference);
        $asset->delete();

        ActivityLog::create([
            'type' => 0,
            'title' => 'Fleet has updated',
            'desc' => "Document file ".$asset->title." has deleted for vehicle with n ".$fleet->n,
            'href' => "/fleet_detail/".$fleet->id,
            'id_reference' => $fleet->id,
            'type_sql' => 1
        ]);

        return Redirect::back()->with('success', 'Element deleted successfully.');
    }

    public function recordDataTable(Request $request){
        $data = new FleetRecord();
        $data = $data->getDataTable($request);
        return response()->json($data);
    }

    public function recordSave(Request $request){
        $id = $request->input('id');
        $type_sql = $id>0?1:0;
        $row = $id?FleetRecord::find($id):new FleetRecord();
        $row->id_fleet = $request->input('id_fleet');
        $row->type = $request->input('type');
        $row->date = $request->input('date');
        $row->note = $request->input('note');
        if(!$id){
            $row->created_by = Auth::user()->id;
        }    
        $row->updated_by = Auth::user()->id;
        $row->save();

        $fleet = Fleet::find($request->input('id_fleet'));
        $title = 'New fleet record has created';
        $desc = ($row->type==0?'Dielectric Test':($row->type==1?'DOT':'Custom'))." Record for vehicle with n ".$fleet->n." at ".$row->date;
        $href = "/fleet_detail/".$row->id_fleet;
        if($type_sql){
            $title = 'Fleet record has updated';
        }
        ActivityLog::create([
            'type' => 15,
            'title' => $title,
            'desc' => $desc,
            'href' => $href,
            'id_reference' => $row->id,
            'type_sql' => $type_sql
        ]);

        return response()->json(["record" => $row], 200);
    }

    public function recordDelete($id){
        $row = FleetRecord::find($id);
        if(!$row) return response()->json($e->getMessage(),400);

        $fleet = Fleet::find($row->id_fleet);
        $title = 'Fleet record has deleted';
        $desc = ($row->type==0?'Dielectric Test':($row->type==1?'DOT':'Custom'))." Record for vehicle with n ".$fleet->n." at ".$row->date;
        $href = "/fleet_detail/".$row->id_fleet;
        ActivityLog::create([
            'type' => 15,
            'title' => $title,
            'desc' => $desc,
            'href' => $href,
            'id_reference' => $row->id,
            'type_sql' => 2
        ]);

        $row->delete();

        return response()->json(["record" => $id], 200);
    }

    public function saveRecordFile(Request $request)
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        try {
            $myFiles=$request['myFiles'];
            $saved=[];
            for ($i=0; $i < count($myFiles); $i++) { 
                $file_record =  $myFiles[$i];
                $filename = $file_record->getClientOriginalName();
                Storage::disk("public")->put("files_fleet_records/$filename",  file_get_contents($file_record));
                $file= new Asset();
                $file->type="fleet_record";
                $file->picture=$filename;
                $file->id_reference=$request->id_record;
                $file->ext = strtolower($file_record->extension());
                $file->save();
                array_push($saved, $file);
            }
            return response()->json(["files"=>$saved],200);
        } catch (\Exception $e) {
            $out->writeln("Error ");
            $out->writeln($e);
            return response()->json($e->getMessage(),400);
        }
    }

    public function toolDataTable(Request $request){
        $data = new FleetTool();
        $data = $data->getDataTable($request);
        return response()->json($data);
    }

    public function toolSave(Request $request){
        $id = $request->input('id');
        $type_sql = $id>0?1:0;
        $row = $id?FleetTool::find($id):new FleetTool();
        $row->id_fleet = $request->input('id_fleet');
        $row->id_tool = $request->input('id_tool');
        $row->assign_date = $request->input('assign_date');
        $row->return_date = $request->input('return_date');
        $row->note = $request->input('note');
        if(!$id){
            $row->created_by = Auth::user()->id;
        }    
        $row->updated_by = Auth::user()->id;
        $row->save();

        $fleet = Fleet::find($request->input('id_fleet'));
        $tools = Tool::whereIn('id', explode(',', $request->input('id_tool')))->get();
        $names = [];
        foreach($tools as $tool){
            array_push($names, str_replace(',', ' ', 'N° '.$tool->n.' '.$tool->title));
        }
        $desc = "Inventory ".implode(', ', $names)." has assigned to fleet with n ".$fleet->n;
        $title = 'New inventory request';
        $href = "/fleet_detail/".$row->id_fleet;
        if($type_sql){
            $title = 'Inventory request has updated';
        }
        ActivityLog::create([
            'type' => 16,
            'title' => $title,
            'desc' => $desc,
            'href' => $href,
            'id_reference' => $row->id,
            'type_sql' => $type_sql
        ]);

        return response()->json(["record" => $row], 200);
    }

    public function toolDelete($id){
        $row = FleetTool::find($id);
        if(!$row) return response()->json($e->getMessage(),400);

        $fleet = Fleet::find($row->id_fleet);
        $tool = Tool::find($row->id_tool);
        $title = 'Inventory assign cancel';
        $desc = "Inventory ".$tool->title." for vehicle with n ".$fleet->n;
        $href = "/fleet_detail/".$row->id_fleet;
        ActivityLog::create([
            'type' => 16,
            'title' => $title,
            'desc' => $desc,
            'href' => $href,
            'id_reference' => $row->id,
            'type_sql' => 2
        ]);

        $row->delete();
        return response()->json(["record" => $id], 200);
    }
}
