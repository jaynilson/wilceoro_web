<?php
namespace App\Http\Controllers;
use App\Helpers\SICAP;
use App\Http\Requests\ValidationDeleteUser;
use App\Http\Requests\ValidationUser;
use App\Http\Requests\ValidationUserEditPut;
use App\Http\Requests\ValidationUserEmployee;
use App\Http\Requests\ValidationUserFromEmployee;
use App\Models\AccessToken;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Rol;
use App\Models\Fleet;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use PDF;
class UserController extends Controller
{
    public function users()
    {
        $roles = Rol::get();
        return view('users', compact('roles'));
    }

    public function insert(ValidationUser $request)
    {
        if ($request->has('password'))
            $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create(array_filter($request->except('password_confirmation', 'picture_upload', 'cdl')));
        if ($request->hasFile('picture_upload')) {

            $ext = $request->file('picture_upload')->extension();
            $pictureObj =  $request->picture_upload;

            $pictureName = $user->id . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 75);
            $pictureObj->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put("images/profiles/$pictureName", $pictureObj->stream());
            User::findOrFail($user->id)->update(['picture' =>  $pictureName]);
        }
        $namespace = 'App\Http\Controllers';
        //$controller = app()->make($namespace . '\Auth\UserForgotPasswordController');
        // $controller->callAction('sendResetLinkEmail', [$request]);
        return redirect('users')->with('success', 'Element created successfully.');
    }

    public function update(ValidationUser $request)
    {
        $employee = User::where("id",$request->id)->first();
        $employee->name=$request->name;
        $employee->last_name=$request->last_name;
        $employee->email=$request->email;
        $employee->id_rol=$request->id_rol;
        $employee->tel=$request->tel;
        $employee->department=$request->department;
        $employee->yard_location=$request->yard_location;
        $employee->status=$request->status;
        if($request->has('pin')) $employee->pin=$request->pin;
        if($request->has('password')){
            if($request->password!="" && $request->password!=null){
                $employee->password= bcrypt($request->password);
            }
        }
        if($request->has('_notify_setting')&&$request->_notify_setting=="true"){
            $employee->notify_accident = false;
            $employee->notify_checkout = false;
            $employee->notify_service = false;
            $employee->notify_outofstock = false;
            $employee->notify_damage = false;
            if($request->has('notify_accident')) $employee->notify_accident=$request->notify_accident === 'on';
            if($request->has('notify_checkout')) $employee->notify_checkout=$request->notify_checkout === 'on';
            if($request->has('notify_service')) $employee->notify_service=$request->notify_service === 'on';
            if($request->has('notify_outofstock')) $employee->notify_outofstock=$request->notify_outofstock === 'on';
            if($request->has('notify_damage')) $employee->notify_damage=$request->notify_damage === 'on';
        }
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

        if ($request->hasFile('cdl_upload')) {
            $ext = $request->file('cdl_upload')->extension();
            $originalName = $request->file('cdl_upload')->getClientOriginalName();
            $name = pathinfo($originalName, PATHINFO_FILENAME);
            $pictureObj =  $request->cdl_upload;
            $pictureName = $name. "_" . time() . ".$ext";
            $pictureObj = Image::make($pictureObj)->encode($ext, 75);
            $pictureObj->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::disk('public')->put("images/cdl_files/$pictureName", $pictureObj->stream());
            User::findOrFail($employee->id)->update(['cdl_path' =>  $pictureName]);
        }

        $page = $request->has('_page')?$request->_page: "users";
        return redirect($page)->with('success', 'Updated successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $role = Rol::findOrFail($user->id_rol);
        
        $role_name = $role->name=='super_admin'?'Super Admin':(
            $role->name=='tools_admin'?'Inventory Admin':(
                $role->name=='fleet_admin'?'Vehicle Admin':(
                    $role->name=='employee'?'Employee':(
                        $role->name=='mechanic'?'Mechanic': ""
                    )
                )
            )
        );
        $roles = Rol::all();

        $fleet = Fleet::findOrFail(1);
        $id_check_out = 1;
        $custom_fields = [];
        $registration_date = now();
        $current_driver = 1;
        return view('user_edit', compact('user', 'role', 'role_name', 'roles', 'fleet', 'id_check_out', 'custom_fields', 'registration_date', 'current_driver'));
    }

    public function delete(ValidationDeleteUser $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        foreach ($ids as $key => $id) {
            $actualPictureName = User::where('id', $id)->get(['picture']);
            $actualPictureName = $actualPictureName[0]->picture ?? null;
            if ($actualPictureName != null && $actualPictureName != "")
                Storage::disk('public')->delete("images/profiles/$actualPictureName");
            if (User::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        return $cantSuccsess <= 1 ?
            redirect('users')->with('success', $cantSuccsess . ' item successfully removed.')
            :
            redirect('users')->with('success', $cantSuccsess . ' items successfully removed.');
    }

    public function dataTable(Request $request)
    {
        $user = new User();
        $users = $user->getDataTable($request);
        return response()->json($users);
    }

    public function dataTableEmployee(Request $request)
    {
        $user = new User();
        $users = $user->getDataTableEmployee($request);
        return response()->json($users);
    }
    
    public function dataTableMechanic(Request $request)
    {
        $user = new User();
        $users = $user->getDataTableMechanic($request);
        return response()->json($users);
    }

    public function loginPin(Request $request)
    {
        try {
            $user = User::where('pin', $request->pin)->first();
            if (!$user) {
                return response()->json([ 'errors' => 'Invalid pin' ], 400);
            }
            // $check = Hash::check($request->pin, $user->pin);
            // if (!$check) {
            //     return response()->json([ 'errors' => 'Invalid pin' ]);
            // }
            $accessToken = AccessToken::updateOrCreate(
                [ 'user_id' => $user->id ],
                [ 'access_token' => Str::random(255) ]
            );
            return response()->json([ 'access_token' =>  $accessToken->access_token, 'user'=> $user ], 200);
        } catch (\Throwable $th) {
            return response()->json([ 'errors' =>  $th ], 400);
        }
    }

    public function resendPin(Request $request){
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([ 'errors' => 'We did not find any account associated with that email' ], 400);
        }
        $data = [
            'title' => '',
            'body' => 'Hello  '.'your access pin is <strong>'.$user->pin."</strong>",
            'asunto' => 'We have sent you your access pin  '
        ];
        try {
            Mail::to($user->email)->send(new \App\Mail\ResendPin($data));
            return response()->json([ 'send' => true ], 200);
        } catch (\Throwable $th) {
            return response()->json([ 'errors' =>  $th ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $accessToken = AccessToken::where('access_token', $request->access_token)->first();
            if ($accessToken) {
                $accessToken->delete();
                return response()->json([ 'success' => true ]);
            }
            
            return response()->json([ 'success' => false ]); 
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getRoleList(Request $request)
    {
        $roles = Rol::orderBy('id')->select('name', 'id')->get();
        $res = [];
        foreach($roles as $role){
            $role->name = str_replace("_", " ", $role->name);
            $usersCount = User::where('id_rol', $role->id)->count();
            //if($usersCount > 0){
                $role->users_count = $usersCount;
                $res[] = $role;
            //}
        }
        return response()->json($res, 200);
    }

    public function getUserList(Request $request)
    {
        $id_role = $request->id_role;
        $users = User::orderBy('id');
        if ($id_role) {
            $users = $users->where('id_role', $id_role);
        }
        $users = $users->select()->get();
        $res = [];

        foreach ($users as $user) {
            $res[] = [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'role' => $user->roles,
                'email' => $user->email,
            ];
        }
        return response()->json($res, 200);
    }
}
