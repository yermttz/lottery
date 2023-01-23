@extends('layouts.app')

@section('content')

<div class="rssbanner text-uppercase">
    <a href="/suppliers"><strong>Proveedores</strong></a>
    <i class="fa-solid fa-angle-right"></i> Crear
</div>
<br>
<div class="text-center m-auto" style="width: 300px;">

    @error('login')
    <div class="alert alert-danger p-1 mb-1">
        ERROR: {{ $errors->first('login') }}
    </div>
    @enderror

    <form action="/suppliers" method="post">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="name" id="name" placeholder="Nombre del Proveedor"
                value="{{ old('name') }}">
            <label for="name" class="form-label">Nombre del Proveedor</label>
            @error('name')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('name') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="utility" id="utility" placeholder="Contraseña"
                value="{{ old('utility') }}" step=".01">
            <label for="utility" class="form-label">% Utilidad</label>
            @error('utility')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('utility') }}
            </div>
            @enderror
        </div>
        <button type="submit" class="rssbanner-btn w-100 text-uppercase">Crear Proveedor</button>
    </form>
</div>

@endsection