@extends('layouts.app')

@section('content')

<div class="rssbanner text-uppercase">
    <a href="/customers"><strong>Clientes</strong></a>
    <i class="fa-solid fa-angle-right"></i> Entregar cuotas
</div>
<br>
<div class="text-center m-auto">
    <form action="/customers/give" method="post">
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
            <select class="form-control" name="ticketid" id="ticketid" placeholder="Cuota"
                value="{{ old('ticketid') }}">
                @foreach ($tickets as $ticket)
                <option value="{{ $ticket->id }}" {{ old('ticketid')==$ticket->id ? 'selected' : '' }}>
                    {{ dateEs(date('l d-m-Y',
                    strtotime($ticket->lottery_date))) }}
                    ({{ $ticket->supplier_name.' - '.$ticket->lottery_name }})
                    (Enteros: {{ $ticket->entires }}, Disponibles: {{$ticket->entires - $ticket->entires_count}},
                    Fracciones: {{ $ticket->fractions }} = ¢{{
                    number_format($ticket->price, 2) }})
                    {{ ($ticket->serie) ? '(Serie: '.$ticket->serie.')' : '' }}
                    {{ ($ticket->emission) ? '(Emisión: '.$ticket->emission.')' : '' }}
                </option>
                @endforeach
            </select>
            <label for="ticketid" class="form-label">Cuota</label>
            @error('ticketid')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('ticketid') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="entires" id="entires" placeholder="Cantidad de enteros"
                value="{{ old('entires') }}" step=".01">
            <label for="entires" class="form-label">Cantidad de enteros</label>
            @error('entires')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('entires') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="fractions" id="fractions"
                placeholder="Cantidad de fracciones" value="{{ old('fractions') }}" step=".01">
            <label for="fractions" class="form-label">Cantidad de fracciones</label>
            @error('fractions')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('fractions') }}
            </div>
            @enderror
        </div>
        <button type="submit" class="rssbanner-btn w-100 text-uppercase">Entregar Cuota</button>
    </form>
</div>

@endsection