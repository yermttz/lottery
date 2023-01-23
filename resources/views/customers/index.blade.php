@extends('layouts.app')

@section('toolbar')
<a href="/customers/return">
    <li class="px-2"
        style="text-align:center; justify-content: center; height: 48px;width:60px; display: flex; align-items: center;">
        <div style="line-height: 10px;">
            <i class="fa-solid fa-2x fa-hand-holding-dollar"></i>
            <br>
            <small style="font-size: 12px;">
                Devolver
            </small>
        </div>
    </li>
</a>
<a href="/customers/give">
    <li class="px-2"
        style="text-align:center; justify-content: center; height: 48px;width:60px; display: flex; align-items: center;">
        <div style="line-height: 10px;">
            <i class="fa-solid fa-2x fa-handshake"></i>
            <br>
            <small style="font-size: 12px;">
                Entregar
            </small>
        </div>
    </li>
</a>
<a href="/customers/create">
    <li class="px-2"
        style="text-align:center; justify-content: center; height: 48px;width:60px; display: flex; align-items: center;">
        <div style="line-height: 10px;">
            <i class="fa-solid fa-2x fa-plus-circle"></i>
            <br>
            <small style="font-size: 12px;">
                Agregar
            </small>
        </div>
    </li>
</a>
<a onClick="window.print()">
    <li class="px-2"
        style="text-align:center;justify-content: center; height: 48px; width:60px; display: flex; align-items: center;">
        <div style="line-height: 10px;">
            <i class="fa-solid fa-2x fa-print"></i>
            <br>
            <small style="font-size: 12px;">
                Imprimir
            </small>
        </div>
    </li>
</a>
@endsection

@section('content')

<div class="rssbanner text-uppercase">
    <a href="/customers"><strong>Clientes</strong></a>
</div>
<br>
<table class="table-border-bottom w-100">
    <thead>
        <tr>
            <td class="rssbanner text-center">#</td>
            <td class="rssbanner">Nombre</td>
            <td class="rssbanner text-center">Enteros</td>
            <td class="rssbanner text-center">%Utilidad</td>
            <td class="rssbanner text-center">Creado</td>
        </tr>
    </thead>
    @php
    $int_total_debt = 0;
    $int_tota_gain = 0;
    @endphp
    @foreach ($customers as $id => $customer)
    @php
    $int_total = $customer->total_entires + $customer->total_fractions;
    $int_neto = $int_total - ($int_total * $customer->utility / 100);
    $int_gain = ($int_neto - $customer->balance) * $customer->utility / 100;
    @endphp
    <tbody class="tbody-header" onclick="window.location='/customers/{{ $customer->id}}';">
        <tr class="first-row bold">
            <td class="text-center">{{ $id+1 }} <br> <a href="/customers/{{$customer->id}}/edit" style="color: red;"><i
                        class="fa-solid fa-trash"></i></a></td>
            <td>{{ $customer->name }}</td>
            <td class="text-center">{{ round($customer->entires, 2) }}</td>
            <td class="text-center">{{ $customer->utility }}</td>
            <td class="text-center">{{ date("d/m/Y h:m a", strtotime($customer->created_at)) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4">
                <b>Total fracciones:</b> {{ $customer->all_fractions }}
                <br>
                <b>Monto total:</b> {{ number_format($int_total, 2) }}
                <i class="fa-solid fa-minus"></i>
                <b>Monto neto:</b> {{ number_format($int_neto, 2)
                }}
                <br>
                <b>Saldo:</b> {{ number_format($customer->balance, 2) }}
                <br>
                <b>Ganancia:</b> {{ number_format($int_gain, 2) }}
            </td>
        </tr>
    </tbody>
    @php
    $int_total_debt += $int_neto;
    $int_tota_gain += $int_gain;
    @endphp
    @endforeach
    <tbody>
        <tr>
            <td class="rssbanner text-center" colspan="5">
                <table class="m-auto noHover">
                    <tbody style="border: none !important;">
                        <tr>
                            <td>TOTAL NETO:</td>
                            <td class="text-end">{{ number_format($int_total_debt, 2) }}</td>
                        </tr>
                        <tr>
                            <td>GANANCIA NETO:</td>
                            <td class="text-end">{{ number_format($int_tota_gain, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>


@endsection