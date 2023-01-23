<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index() {
        if(Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function register(RegisterRequest $request) {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email',
            'username' => 'required|min:4|unique:users,username',
            'password' => 'required|min:3',
            'password_confirmation' => 'required|same:password'
        ];
        $messages = [
            'name.required' => 'Ingresa un nombre',
            'name.max' =>'El nombre no puede ser mayor a :max caracteres.',
            'email.required' => 'Ingresa un correo electrónico',
            'email.unique' => 'Correo electrónico ya registrado',
            'username.required' => 'Ingrese un nombre de usuario',
            'username.min' => 'El nombre de usuario debe ser mínimo :min caracteres',
            'username.unique' => 'Nombre de usuario ya registrado',
            'password.required' => 'Ingrese una contraseña',
            'password.min' => 'La contraseña debe ser mínimo :min caracteres',
            'password_confirmation.required' => 'Ingrese la confirmación de contraseña',
            'password_confirmation.same' => 'La validación de contraseña no coincide'
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('register')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $user = User::create($request->all());
        return redirect('login')->with('success', 'Account create successfully');
    }
}