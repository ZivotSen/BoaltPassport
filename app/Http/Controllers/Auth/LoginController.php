<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginAPI(Request $request){
        $input = $request->all();
        $validator = $this->validator($input);
        if($validator->fails()){
            $error = $validator->messages()->get('*');
            return response()->json($error);
        }

        try {
            if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']]) ) {
                $user = Auth::user();
                $token = $user->createToken($user->email.'-'.now());
            }
            if(isset($token)){
                return response()->json([
                    'token' => $token->accessToken
                ]);
            }
        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json([
            'error' => 'Unexpected error trying to login user'
        ]);
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }
}
