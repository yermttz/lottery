@extends('layouts.app')

@section('content')

<div class="rssbanner text-uppercase">
    <a href="/lotteries"><strong>Cuotas</strong></a>
    <i class="fa-solid fa-angle-right"></i> Eliminar
</div>
<br>
<div class="text-center m-auto" style="width: 300px;">

    @error('login')
    <div class="alert alert-danger p-1 mb-1">
        ERROR: {{ $errors->first('login') }}
    </div>
    @enderror

    <h3>¿Estás seguro de Eliminar la Cuota?
        <form action="/tickets/{{$ticket->id}}" method="post">
            @csrf
            {{ method_field('DELETE') }}

            <button type="submit" class="rssbanner-btn w-100 text-uppercase">Eliminar Cuota</button>
        </form>
</div>

@endsection