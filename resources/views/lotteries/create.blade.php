@extends('layouts.app')

@section('content')

<div class="rssbanner text-uppercase">
    <a href="/lotteries"><strong>Loterías</strong></a>
    <i class="fa-solid fa-angle-right"></i> Crear
</div>
<br>
<div class="text-center m-auto" style="width: 300px;">

    @error('login')
    <div class="alert alert-danger p-1 mb-1">
        ERROR: {{ $errors->first('login') }}
    </div>
    @enderror

    <form action="/lotteries" method="post">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="name" id="name" placeholder="Nombre del Cliente"
                value="{{ old('name') }}">
            <label for="name" class="form-label">Nombre de la Lotería</label>
            @error('name')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('name') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" name="date" id="date" placeholder="Fecha a Jugar"
                value="{{ old('date') }}" step=".01">
            <label for="date" class="form-label">Fecha a Jugar</label>
            @error('date')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('date') }}
            </div>
            @enderror
        </div>
        <button type="submit" class="rssbanner-btn w-100 text-uppercase">Crear Loteria</button>
    </form>
</div>

@endsection