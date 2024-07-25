<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;
use App\Helpers\SICAP;
use App\Http\Requests\ValidationInterface;
use App\Models\Check;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class InterfacesController extends Controller
{
    public function interfaces()
    {
        return view('interfaces');
    }

    public function insert(ValidationInterface $request)
    {
        $element = Check::create(array_filter($request->except('picture_upload')));
        $path = '';
        $pictureName = "";
        if ($request->hasFile('picture_upload')) {
            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;

            $pictureName = $element->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 95);
            // $pictureObj->resize(150, 150, function ($constraint) {
            //     $constraint->aspectRatio();
            // });
            $path = "images/interface/$pictureName";
            Storage::disk('public')->put($path, $pictureObj->stream());
            Check::findOrFail($element->id)->update(['picture' =>  $pictureName]);
        }
        if($path!='') $path="/storage/".$path;
        ActivityLog::create([
            'type' => 17,
            'title' => 'New interface has created',
            'desc' => $element->title,
            'href' => "/interfaces",
            'id_reference' => $element->id,
            'file_name' => $pictureName,
            'file_url' => $path,
        ]);

        return redirect('interfaces')->with('success', 'Element created successfully.');
    }

    public function update(ValidationInterface $request)
    {
        $interface = Check::findOrFail($request->id);
        $interface->update(array_filter($request->except('picture_upload', 'id')));
        $path = '';
        $pictureName = "";
        if ($request->hasFile('picture_upload')) {
            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;
            $pictureName = $interface->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 95);
            // $pictureObj->resize(150, 150, function ($constraint) {
            //     $constraint->aspectRatio();
            // });
            $path = "images/interface/$pictureName";
            Storage::disk('public')->put($path, $pictureObj->stream());
            Check::findOrFail($interface->id)->update(['picture' =>  $pictureName]);
        }

        if($path!='') $path="/storage/".$path;
        ActivityLog::create([
            'type' => 17,
            'title' => 'Interface has updated',
            'desc' => $interface->title,
            'href' => "/interfaces",
            'id_reference' => $interface->id,
            'file_name' => $pictureName,
            'file_url' => $path,
            'type_sql' => 1
        ]);  

        return redirect('interfaces')->with('success', 'Element saved successfully.');
    }

    public function dataTable(Request $request)
    {
        $check = new Check();
        $results = $check->getDataTable($request);
        return response()->json($results);
    }

    public function getInterfaces(Request $request){
        $interfaces = Check::where('type', $request->type)->get(['*']);
        return response()->json(["interfaces"=>$interfaces]);
    }

    public function delete(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        $title = "";
        foreach ($ids as $key => $id) {
            $actualPictureName = Check::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != "")
                Storage::disk('public')->delete("images/interface/$actualPictureName");

            $interface = Check::find($id);
            if ($interface) {
                $title .= ($title==''?'':', ').$interface->title;
                $interface->delete();
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        ActivityLog::create([
            'type' => 17,
            'title' => $cantSuccsess.' Inverfaces has deleted',
            'desc' => "Title: ".$title,
            'href' => "#",
            'id_reference' => 0,
            'type_sql' => 2
        ]);

        return $cantSuccsess <= 1 ?
        redirect('interfaces')->with('success', $cantSuccsess . ' item successfully removed.')
        :
        redirect('interfaces')->with('success', $cantSuccsess . ' items successfully removed.');
    }

    public function getInterfaceList(Request $request){
        $res = Check::get();
        return response()->json($res);
    }
}