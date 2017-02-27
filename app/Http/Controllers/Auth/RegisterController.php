<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Address;
use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/home';

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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'address_name' => 'required',
            'city' => 'required|exists:cities,id',
            'district' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
        ]);
    }

    /**
     * Create a new address and user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $address = Address::firstOrCreate([
            'city_id' => $data['city'],
            'name' => $data['address_name'],
            'district' => $data['district'],
            'postal_code' => $data['postal_code'],
            'phone' => $data['phone'],
        ]);

        return User::create([
            'address_id' => $address->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
