<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Policy;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $validation_array = [
            'username' => ['required', 'string', 'min:4', 'max:30', 'unique:users','alpha_dash','regex:/^[A-Za-z0-9ñÑ]+(?:[ _-][A-Za-z0-9ñÑ]+)*$/'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules()
        ];

        $messages = [
            'username.regex' => 'The username may only contain letters, numbers, dashes and underscores.',
        ];

        $validator = Validator::make($input, [], $messages, []);

        $validator->validate();

        return User::create([
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
