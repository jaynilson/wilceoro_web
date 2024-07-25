<?php
namespace App\Http\Controllers;
use App\Helpers\SICAP;
use App\Http\Requests\ValidationChangePassword;
use App\Http\Requests\ValidationProfileUser;
use App\Http\Requests\ValidationUpdateProfileUser;
use App\Http\Requests\ValidationUser;
use App\Models\User;
use App\Models\Rol;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(auth()->user()->id);
        $roles = Rol::orderBy('id')->get();
        return view('profile_user', compact('user', 'roles'));
    }

    public function changePassword(ValidationChangePassword $request)
    {
        $id = $request->id;
        User::findOrFail($id)->update(
            [
                'password' => bcrypt($request->password)
            ]
        );
        return redirect()->route('user_profile')->with('success', 'La contraseña se actualizó con exitó y tendrá efecto la próxima vez que inicie sesión.');
    }

    public function update(ValidationProfileUser $request)
    {
        $id=$request->id;
        if ($request->hasFile('picture_upload')) {
            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;
            $actualPictureName = User::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != "")
                Storage::disk('public')->delete("images/profiles/$actualPictureName");

            $pictureName = $id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 75);
            $pictureObj->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put("images/profiles/$pictureName", $pictureObj->stream());
            $request->request->add(['picture' =>  $pictureName]);
        }
        if(empty($request->password)){
            $request->request->remove('password');
        }else{
            $request->request->set('password',bcrypt($request->password));
        }

        $data = array_filter($request->except('password_confirmation', 'user_name', 'picture_upload'));
        // Process notification values
        $notifyAccident = $request->input('notify_accident') === 'on';
        $notifyCheckout = $request->input('notify_checkout') === 'on';
        $notifyService = $request->input('notify_service') === 'on';
        $notifyOutOfStock = $request->input('notify_outofstock') === 'on';
        $notifyDamage = $request->input('notify_damage') === 'on';

        $data['notify_accident'] = $notifyAccident;
        $data['notify_checkout'] = $notifyCheckout;
        $data['notify_service'] = $notifyService;
        $data['notify_outofstock'] = $notifyOutOfStock;
        $data['notify_damage'] = $notifyDamage;
        
        User::findOrFail($id)->update($data);
        return redirect()->route('user_profile')->with('success', 'Información actualizada con exitó.');
    }

    public function updateProfileEmployee(ValidationUserFromEmployee $request)
    {
        $employee = User::where("id",$request->id)->first();
        $employee->name=$request->name;
        $employee->last_name=$request->last_name;
        $employee->email=$request->email;
        $employee->save();
        if ($request->hasFile('picture_upload')) {

            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;

            $pictureName = $employee->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 75);
            $pictureObj->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put("images/profiles/$pictureName", $pictureObj->stream());
            User::findOrFail($employee->id)->update(['picture' =>  $pictureName]);
        }
        $user = User::where("id",$request->id)->first();
        $accessToken = AccessToken::updateOrCreate(
            [ 'user_id' => $user->id ],
            [ 'access_token' => Str::random(255) ]
        );
        return response()->json([ 'access_token' =>  $accessToken->access_token, 'user'=> $user ], 200);
      
    }
}
