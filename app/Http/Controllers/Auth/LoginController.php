<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
//use Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/user_budgetplanner';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function login(Request $request){
        $credentials=$request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if(Auth::attempt($credentials)){
            $user_role=Auth::user()->role;

            switch($user_role){
                case 0:
                    return redirect('/user_budgetplanner');
                    break;
                case 1:
                    return redirect('/editor');
                    break;
                case 2:
                    return redirect('/admin');
                    break;
                default:
                    Auth::logout();
                    return redirect('/login')->with('error', 'Ooops! Something went wrong!');
            }
    }else{
        return redirect('/login')->withErrors([
            'message'=>'Invalid Credetials!',
            'duration'=>3000,
        ]);
    }

}

        public function redirectToGoogle(){
             return Socialite::driver('google')->redirect();
         }

        public function GoogleCallback()
        {
            $user = Socialite::driver('google')->user();
        
            $finduser = User::where('google_id', $user->id)->first();
        
            if($finduser){
                Auth::login($finduser);
                return redirect('user_budgetplanner');
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                ]);
            
                Auth::login($newUser);
            
                return redirect('user_budgetplanner');
            }
        }

}