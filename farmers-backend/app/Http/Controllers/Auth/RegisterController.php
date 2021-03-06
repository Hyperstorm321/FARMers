<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'location' => ['required'],
            'boundary' => ['required', 'string', 'max:255'],
            'kind_id' => ['required'],
            'valid_id' => ['required', 'string', 'max:255'],
            'user_type' => ['required', 'string', 'max:255'],
            'is_approved' => ['required'],
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
        $request = request();
        $valid_id_path = time().'.'.$request->file('valid_id_img')->getClientOriginalExtension();
        $request->file('valid_id_img')->move(public_path('../../farmers-frontend/src/assets/storage/images/valid_ids/'), $valid_id_path);

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'location' => $data['location'],
            'boundary' => $data['boundary'],
            'valid_id_type_id' => $data['kind_id'],
            'valid_id_path' => $valid_id_path,
            'user_type_id' => $data['user_type'],
            'is_approved' => $data['is_approved'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
