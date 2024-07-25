<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationFleet;
use App\Models\Fleet;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class FleetController extends Controller
{
    public function trucksCars()
    {
        $fleet = new Fleet();
        $departments = $fleet->getDepartments();
        $locations = $fleet->getLocations();
        $statuses = $fleet->getStatuses();
        $type_fleet="trucks_cars";
        return view('fleet', compact('type_fleet', 'departments', 'locations', 'statuses'));
    }
    public function trailers()
    {
        $fleet = new Fleet();
        $departments = $fleet->getDepartments();
        $locations = $fleet->getLocations();
        $statuses = $fleet->getStatuses();
        $type_fleet="trailers";
        return view('fleet', compact('type_fleet', 'departments', 'locations', 'statuses'));
    }
    public function equipment()
    {
        $fleet = new Fleet();
        $departments = $fleet->getDepartments();
        $locations = $fleet->getLocations();
        $statuses = $fleet->getStatuses();
        $type_fleet="equipment";
        return view('fleet',compact('type_fleet', 'departments', 'locations', 'statuses'));
    }

    public function dataTable(Request $request)
    {
        $fleet = new Fleet();
        $results = $fleet->getDataTable($request);
        return response()->json($results);
    }

    public function dataTableAll(Request $request)
    {
        $fleet = new Fleet();
        $results = $fleet->getDataTableAll($request);
        return response()->json($results);
    }

    public function fleetList(Request $request)
    {
        $fleet = new Fleet();
        $results = $fleet->getList($request);
        return response()->json($results);
    }
    
    public function insert(ValidationFleet $request)
    {
        $fleet = Fleet::create(array_filter($request->except( 'picture_upload')));
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
            'title' => 'New vehicle has created',
            'desc' => "n: ".$fleet->n.", model:  ".$fleet->model.", licence: ".$fleet->licence_plate.", location: ".$fleet->yard_location.", department:".$fleet->department,
            'href' => "/fleet_detail/".$fleet->id,
            'id_reference' => $fleet->id,
            'file_name' => $pictureName,
            'file_url' => $path,
        ]);

        if($request->type=="trucks_cars"){
            return redirect('trucks_cars')->with('success', 'Element created successfully.');
        }else if($request->type=="trailers"){
            return redirect('trailers')->with('success', 'Element created successfully.');
        }else{
            return redirect('equipment')->with('success', 'Element created successfully.');
        }
    }

    public function update(ValidationFleet $request)
    {
        $fleet =  Fleet::findOrFail($request->id)->update(array_filter($request->except('picture_upload', 'id')));
        $fleet =  Fleet::where("id",$request->id)->first();
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

        if($request->type=="trucks_cars"){
            return redirect('trucks_cars')->with('success', 'Element saved successfully.');
        }else if($request->type=="trailers"){
            return redirect('trailers')->with('success', 'Element saved successfully.');
        }else{
            return redirect('equipment')->with('success', 'Element saved successfully.');
        }
    }

    public function delete(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        $title = "";
        foreach ($ids as $key => $id) {
            $actualPictureName = Fleet::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != "")
               // Storage::disk('public')->delete("images/fleet/$actualPictureName");

            $fleet = Fleet::where('id', $id)->first(); 
            if ($fleet) {
                $title .= ($title==''?'':', ').$fleet->n;
                $fleet->delete();
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        ActivityLog::create([
            'type' => 0,
            'title' => $cantSuccsess.' Fleets have deleted',
            'desc' => "n: ".$title,
            'href' => "#",
            'id_reference' => 0,
            'type_sql' => 2
        ]);

        if($request->type=="trucks_cars"){
            return $cantSuccsess <= 1 ?
            redirect('trucks_cars')->with('success', $cantSuccsess . ' item successfully removed.')
            :
            redirect('trucks_cars')->with('success', $cantSuccsess . ' items successfully removed.');
        }else if($request->type=="trailers"){
            return $cantSuccsess <= 1 ?
            redirect('trailers')->with('success', $cantSuccsess . ' item successfully removed.')
            :
            redirect('trailers')->with('success', $cantSuccsess . ' items successfully removed.');
        }else{
            return $cantSuccsess <= 1 ?
            redirect('equipment')->with('success', $cantSuccsess . ' item successfully removed.')
            :
            redirect('equipment')->with('success', $cantSuccsess . ' items successfully removed.');
        }
    }

    public function getFleetList(Request $request){
        return response()->json(Fleet::select('id', 'n', 'custom_name', 'model')->get());
    }

    public function getLocationList(Request $request){
        $fleet = new Fleet();
        $locations = $fleet->getLocations();
        return response()->json($locations);
    }

    public function getDepartmentList(Request $request){
        $fleet = new Fleet();
        $departments = $fleet->getDepartments();
        return response()->json($departments);
    }
}
