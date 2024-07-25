<?php

namespace App\Http\Controllers;
use App\Http\Requests\ValidationFleet;
use App\Http\Requests\ValidationRequestCategory;
use App\Models\Fleet;
use App\Models\RequestCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class RequestCategoryController extends Controller
{

    public function requestCategories()
    {
        return view('request_categories');
    }

    public function dataTable(Request $request)
    {
        $elements = new RequestCategory();
        $results = $elements->getDataTable($request);
        return response()->json($results);
    }

    public function requestCategoryList(Request $request)
    {
        $results=[];
        if($request->type){
            $results= RequestCategory::where("type",$request->type)->get(["*"]);
        }else{
            $results= RequestCategory::get(["*"]);
        }
        return response()->json($results);
   
    }

    public function insert(ValidationRequestCategory $request)
    {
        $fleet = RequestCategory::create($request->all());
        ActivityLog::create([
            'type' => 9,
            'title' => 'New Request Category',
            'desc' => $fleet->title,
            'href' => "#",
            'id_reference' => $fleet->id,
        ]);
        return redirect('request_categories')->with('success', 'Element created successfully.');
    }

    public function update(ValidationRequestCategory $request)
    {
        $fleet=RequestCategory::findOrFail($request->id);
        $fleet->update(array_filter($request->except('id')));
        ActivityLog::create([
            'type' => 9,
            'title' => 'Request Category has updated',
            'desc' => $fleet->title,
            'href' => "/request_categories",
            'id_reference' => $fleet->id,
            'type_sql' => 1
        ]);
        return redirect('request_categories')->with('success', 'Element saved successfully.');
    }

    public function delete(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        $title='';
        foreach ($ids as $key => $id) {
            $fleet = RequestCategory::find($id);
            if ($fleet) {
                $title.=($title==''?'':', ').$fleet->title;
                $fleet->delete();
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }
        ActivityLog::create([
            'type' => 9,
            'title' => $cantSuccsess.' Request Category has deleted',
            'desc' => $title,
            'href' => "/request_categories",
            'id_reference' => 0,
            'type_sql' => 2
        ]);
        return $cantSuccsess <= 1 ?
        redirect('request_categories')->with('success', $cantSuccsess . ' item successfully removed.')
        :
        redirect('request_categories')->with('success', $cantSuccsess . ' items successfully removed.');
    }
}
