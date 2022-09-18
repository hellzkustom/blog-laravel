<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\User;

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
   protected $redirectTo = RouteServiceProvider::HOME;
//   protected $redirectTo = 'admin/list';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    
    
    
    public function redirectPath()
    {
      if(Auth::user()->admin==true)
        {
            return 'admin/list';
          }
          else
        {
        return route('admin_introduction');
        
         }
        //例）return 'costs/index';
    }
    
        public function showLoginForm()
    {
        if (!session()->has('url.intended')) {

            session(['url.intended' => url()->previous()]);

        }
        return view('auth.login');
    }
}
