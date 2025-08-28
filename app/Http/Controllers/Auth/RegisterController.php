<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
     * Get the redirect path based on user role
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->primary_role === 'client') {
                return '/client';
            } elseif ($user->primary_role === 'facility') {
                return '/facility';
            }
        }
        
        return $this->redirectTo;
    }

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'primary_role' => ['required', 'string', 'in:client,facility'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create the user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone'],
                'primary_role' => $data['primary_role'],
                'password' => Hash::make($data['password']),
            ]);

            // Get the role based on primary_role
            $role = Role::where('name', $data['primary_role'])->first();
            
            if ($role) {
                // Create the role relationship
                $user->roles()->attach($role->id, [
                    'facility_id' => null, // For now, set to null as this is a new user
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $user;
        });
    }
}
