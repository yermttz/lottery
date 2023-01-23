@extends('layouts.app')

@section('content')

@if (isset($errors) && count($errors) > 0)
<div class="alert alert-danger">
    <b>Corrige {{ count($errors) == 1 ? 'el siguiente error' : 'los siguientes errores' }}:</b>
    <ul class="list-unstyled mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="container">
    <h1>Bienvenid@, crea tu cuenta para empezar a gestionar!</h1>

    <form action="/register" method="post">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">

        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de Usuario</label>
            <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}">

        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}">

        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" id="password" value="{{ old('password') }}">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password_confirmation" name="password_confirmation" class="form-control"
                id="password_confirmation" value="{{ old('password_confirmation') }}">
        </div>
        <button type="submit" class="rssbanner-btn w-100 text-uppercase">Registrarme</button>
    </form>

</div>
@endsection