<?php
namespace App\Http\Controllers;
use App\Models\ActivityLog;
use App\Models\Rental;
use App\Models\Asset;
use App\Models\User;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rental');
    }

    public function save(Request $request)
    {
        $id = $request->input('id');
        $type_sql = $id>0?1:0;
        $row = $id?Rental::find($id):new Rental();
        $row->id_tool = $request->input('id_tool');
        $row->return_date = $request->input('return_date');
        $row->id_employee = $request->input('id_employee');
        $row->vendor_name = $request->input('vendor_name');
        $row->rental_date = $request->input('rental_date');
        $row->needed_date = $request->input('needed_date');
        $row->note = $request->input('note');
        $row->notify = $request->input('notify');
        $row->status = $request->input('status');
        if(!$id){
            $row->created_by = Auth::user()->id;
        }    
        $row->updated_by = Auth::user()->id;
        $row->save();

        $myFiles=$request['myFiles'];
        if ($row->status=='check-in' && is_array($myFiles)) {
            $saved=[];
            for ($i=0; $i < count($myFiles); $i++) { 
                $file_rental =  $myFiles[$i];
                $filename = $file_rental->getClientOriginalName();
                Storage::disk("public")->put("files_rental_returned/$filename",  file_get_contents($file_rental));
                $file= new Asset();
                $file->type="rental_returned";
                $file->picture=$filename;
                $file->id_reference=$row->id;
                $file->ext = strtolower($file_rental->extension());
                $file->save();
                array_push($saved, $file);
            }
        }
        $href = "/rental";
        $user = User::find($row->id_employee);
        $tool = Tool::find($row->id_tool);
        ActivityLog::create([
            'type' => 7,
            'title' => $type_sql==0?'New rental has created':'Rental has updated',
            'desc' => 'Employee '.$user->name.' has rentaled an inventory '.$tool->title,
            'href' => $href,
            'id_reference' => $row->id,
            'type_sql' => $type_sql
        ]);
        return redirect('rental')->with('success', 'Element created successfully.');
    }

    public function dataTable(Request $request)
    {
        $rental = new Rental();
        $results = $rental->getDataTable($request);
        return response()->json($results);
    }

    public function delete(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        $title="";
        $assestController = new AssetController();
        foreach ($ids as $key => $id) {
            $assets = Asset::where('type', 'rental_returned')->where('id_reference', $id)->get();
            foreach ($assets as $asset) {
                $assestController->delete($asset->id);
            }
            $row = Rental::find($id);
            if ($row) {
                $user = User::find($row->id_employee);
                $tool = Tool::find($row->id_tool);
                $title.=($title==''?'':', ').'Employee '.$user->name.' has deleted a reantal for an inventory '.$tool->title;
                $cantSuccsess++;
                $row->delete();
            } else {
                $errors++;
            }
        }
        $href = "/rental";
        ActivityLog::create([
            'type' => 7,
            'title' => $cantSuccsess.' Rental has deleted',
            'desc' => $title,
            'href' => $href,
            'id_reference' => 0,
            'type_sql' => 2
        ]);
        return $cantSuccsess <= 1 ?
        Redirect::back()->with('success', $cantSuccsess . ' rental request successfully removed.')
        :
        Redirect::back()->with('success', $cantSuccsess . ' rental requests successfully removed.');
    }
}
