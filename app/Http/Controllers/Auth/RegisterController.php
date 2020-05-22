<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::STEAM;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'firstname'     => $data['firstname'],
            'lastname'     => $data['lastname'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->bank()->associate(\App\Bank::where('name', 'FastWallet')->first())->save();
        $user->roles()->attach(\App\Role::where('name', 'user')->first());

        $bankmail = new \App\Bankmail;
        $bankmail->category = 'bienvenue';
        $bankmail->title = 'Bienvenue chez FastWallet';
        $bankmail->body = 'Bonjour ' . $data['firstname'] . ',
        
        Cordialement,
        
        Sonia MIRINE
        Service Client FastWallet';

        $bankmail->user_id = $user->id;
        $bankmail->bank_id = 1;

        $bankmail->save();

        return $user;
    }
}
