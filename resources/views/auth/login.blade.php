@extends('layouts.app')

@section('content')

<div class="text-center m-auto" style="width: 300px;">
    <b class="text-uppercase">
        Iniciar Sesion
    </b>
    <br>
    <br>
    @error('login')
    <div class="alert alert-danger p-1 mb-1">
        ERROR: {{ $errors->first('login') }}
    </div>
    @enderror

    <form action="/login" method="post">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="username" id="username" placeholder="Nombre de Usuario"
                value="{{ old('username') }}">
            <label for="username" class="form-label">Nombre de Usuario</label>
            @error('username')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('username') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña"
                value="{{ old('password') }}">
            <label for="password" class="form-label">Contraseña</label>

            @error('password')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('password') }}
            </div>
            @enderror
        </div>
        <button type="submit" class="rssbanner-btn w-100 text-uppercase">Ingresar</button>
    </form>
</div>

@endsection