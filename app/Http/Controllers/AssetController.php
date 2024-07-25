<?php
namespace App\Http\Controllers;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class AssetController extends Controller
{
    public function insert(Request $request)
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();    
        try {
            if (
            $request->hasFile('asset') &&  
            !empty($request->type) &&
            !empty($request->id_reference)
            ) {
                $ext = $request->file('asset')->extension();
                $pictureObj =  $request->asset;
                $pictureName = $request->id_reference . time() . ".$ext";
                $pictureObj = Image::make($pictureObj)->encode($ext, 95);
                // $pictureObj->resize(150, 150, function ($constraint) {
                //     $constraint->aspectRatio();
                // });
                Storage::disk('public')->put("images/assets/$pictureName", $pictureObj->stream());
                $asset = new Asset();
                $asset->type= $request->type;
                $asset->picture=$pictureName;
                $asset->id_reference= $request->id_reference;
                $asset->save();
                return response()->json(["asset"=> $asset]);
            }else{
                $out->writeln($request->type);
                $out->writeln($request->id_reference);
                return response()->json(["error"=>"fields are missing"],400);
            }
        } catch (\Throwable $th) {
            $out->writeln($th);
        }
    }

    public function delete($id)
    {
        $file = Asset::where('id', $id)->first();
        $path = "images/assets";
        if($file->type=='fleet_detail') $path = 'files_fleet';
        if($file->type=='fleet_record') $path = 'files_fleet_records';
        if($file->type=='rental_returned') $path = 'files_rental_returned';
        if($file->type=='record') $path = 'files_records';
        Storage::disk('public')->delete("${path}/".$file->picture);
        $file->delete();
        return response()->json(["id" => $id], 200);
    }
}
