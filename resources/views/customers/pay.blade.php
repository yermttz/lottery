@extends('layouts.app')

@section('content')

<div class="rssbanner text-uppercase">
    <a href="/customers"><strong>Clientes</strong></a>
    <i class="fa-solid fa-angle-right"></i> Aplicar Saldos
</div>
<br>
<div class="text-center m-auto">
    <form action="/customers/pay" method="post">
        @csrf
        <div class="form-floating mb-3">
            <select class="form-control" name="customerid" id="customerid" placeholder="Nombre del Cliente"
                value="{{ old('customerid') }}">
                @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customerid')==$customer->id ? 'selected' : (($c ==
                    $customer->id) ? 'selected' : '') }}>{{
                    $customer->name }}</option>
                @endforeach
            </select>
            <label for="customerid" class="form-label">Cliente</label>
            @error('customerid')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('customerid') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <select class="form-control" name="type" id="type" placeholder="Tipo de Aplicación"
                value="{{ old('type') }}">
                <option value="2" {{ old('type')=='2' ? 'selected' : '' }}>Recibo</option>
                <option value="1" {{ old('type')=='1' ? 'selected' : '' }}>Pago</option>
            </select>
            <label for="type" class="form-label">Tipo de Aplicación</label>
            @error('type')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('type') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="amount" id="amount" placeholder="Cantidad de enteros"
                value="{{ old('amount') }}" step=".01">
            <label for="amount" class="form-label">Monto a Aplicar</label>
            @error('amount')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('amount') }}
            </div>
            @enderror
        </div>
        <button type="submit" class="rssbanner-btn w-100 text-uppercase">Aplicar Saldo</button>
    </form>
</div>

@endsection