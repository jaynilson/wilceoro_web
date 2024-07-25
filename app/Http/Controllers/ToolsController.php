<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationTool;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\ActivityLog;
class ToolsController extends Controller
{
    public function index(Request $request)
    {
        $path = $request->path();
        $explodedPath = explode('/', $path);
        $department = end($explodedPath);
        return view('tools', compact('department'));
    }

    public function insert(ValidationTool $request, $department="fleet")
    {
        $n = Tool::max('n') + 1;
        $element = Tool::create(
            array_merge(
                $request->except('picture_upload', 'required_return'),
                ['n' => $n]
            )
        );
        $requiredReturn = $request->input('required_return') === 'on';
        $element->required_return = $requiredReturn;
        $element->save();
        $pictureName = "default.png";
        if ($request->hasFile('picture_upload')){
            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;
            $pictureName = $element->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 95);
            Storage::disk('public')->put("images/tools/$pictureName", $pictureObj->stream());
        }else{
            $defaultImagePath = 'images/tools/default.png';
            $defaultSourcePath = 'assets/images/default-inventory.png';

            if (!Storage::disk('public')->exists($defaultImagePath)) {
                $publicDefaultPath = public_path($defaultSourcePath);
                $storageDefaultPath = storage_path('app/public/' . $defaultImagePath);
                File::copy($publicDefaultPath, $storageDefaultPath);
            }
        }
        Tool::findOrFail($element->id)->update(['picture' =>  $pictureName]);
        $element->department = $department;
        $element->save();

        $redirectRoute = 'tools';
        if ($department === 'fleet') {
            $redirectRoute = '/tools/fleet';
        } elseif ($department === 'office') {
            $redirectRoute = '/tools/office';
        } elseif ($department === 'shop') {
            $redirectRoute = '/tools/shop';
        } elseif ($department === 'general') {
            $redirectRoute = '/tools/general';
        }

        $path="/storage/images/tools/".$pictureName;
        ActivityLog::create([
            'type' => 2,
            'title' => 'New inventory has created',
            'desc' => $element->title,
            'href' => $redirectRoute,
            'id_reference' => $element->id,
            'file_name' => $pictureName,
            'file_url' => $path,
        ]);

        return redirect($redirectRoute)->with('success', 'Element created successfully.');
    }

    public function dataTable(Request $request, $department="")
    {
        $type = $request->input('type');
        $tool = new Tool();
        $results = $tool->getDataTable($request, $department, $type);
        return response()->json($results);
    }

    public function update(ValidationTool $request, $department="")
    {
        $requiredReturn = $request->input('required_return') === 'on';
        $fleet =  Tool::findOrFail($request->id);
        $fleet->fill(array_filter($request->except('picture_upload', 'id')));
        $fleet->required_return = $requiredReturn;
        $fleet->save();
        
        $fleet =  Tool::where("id",$request->id)->first();
        $pictureName = '';
        $path = '';
        if ($request->hasFile('picture_upload')) {
            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;
            $pictureName = $fleet->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 95);
            $path = "/storage/images/tools/$pictureName";
            Storage::disk('public')->put("images/tools/$pictureName", $pictureObj->stream());
            Tool::findOrFail($fleet->id)->update(['picture' =>  $pictureName]);
        }

        $redirectRoute = 'tools';
        if ($department === 'fleet' || $department === '') {
            $redirectRoute = '/tools/fleet';
        } elseif ($department === 'office') {
            $redirectRoute = '/tools/office';
        } elseif ($department === 'shop') {
            $redirectRoute = '/tools/shop';
        } elseif ($department === 'general') {
            $redirectRoute = '/tools/general';
        }

        ActivityLog::create([
            'type' => 2,
            'title' => 'New inventory has updated',
            'desc' => $fleet->title,
            'href' => $redirectRoute,
            'id_reference' => $fleet->id,
            'file_name' => $pictureName,
            'file_url' => $path,
            'type_sql' => 1,
        ]);

        return redirect($redirectRoute)->with('success', 'Element saved successfully.');
    }

    public function delete(Request $request, $department="")
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        $title = '';
        foreach ($ids as $key => $id) {
            $actualPictureName = Tool::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != "")
                Storage::disk('public')->delete("images/tools/$actualPictureName");
            $tool = Tool::find($id);
            if ($tool) {
                $title .= ($title==''?'':', ').$tool->title;
                $tool->delete();
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }
        
        $redirectRoute = 'tools';
        if ($department === 'fleet' || $department === '') {
            $redirectRoute = '/tools/fleet';
        } elseif ($department === 'office') {
            $redirectRoute = '/tools/office';
        } elseif ($department === 'shop') {
            $redirectRoute = '/tools/shop';
        } elseif ($department === 'general') {
            $redirectRoute = '/tools/general';
        }

        ActivityLog::create([
            'type' => 2,
            'title' => $cantSuccsess.' inventories has deleted',
            'desc' => "Title: ".$title,
            'href' => $redirectRoute,
            'id_reference' => 0,
            'type_sql' => 2
        ]);

        return $cantSuccsess <= 1 ?
        redirect($redirectRoute)->with('success', $cantSuccsess . ' item successfully removed.')
        :
        redirect($redirectRoute)->with('success', $cantSuccsess . ' items successfully removed.');
    }

    public function toolList(Request $request)
    {
        $tool = new Tool();
        $results = $tool->getList($request);
        return response()->json($results);
    }
}
