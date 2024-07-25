<?php
namespace App\Http\Controllers\Security;
use App\Http\Controllers\Controller;
use App\Models\Rol;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\ValidationDashboard;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/dashboard';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ValidationDashboard $request)
    {
        return view('login');
    }

    public function username()
    {
        return 'email';
    }

    protected function authenticated(Request $request, $user)
    {
      
        $role = Rol::where('id',$user->id_rol )->get('name')->first();
        if($user->status!="enable"){
     
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('login')->withErrors(['error'=>'Tu cuenta ha sido deshabilitada']);
        }else{
            $time=Carbon::now()->format('H:i:s');
           
        }
        if($role->name=="super_admin"){
            return redirect('dashboard');
        }else if($role->name=="alumno"){
            return redirect('student/profile');
        }else{
            return redirect('teacher/profile');
        }
    }

    public function login(Request $request)
    {
        
        $this->guard()->logout();
        $request->session()->invalidate();
        
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


   
}
