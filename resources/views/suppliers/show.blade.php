@extends('layouts.app')

@section('toolbar')
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
    <a href="/suppliers"><strong>Proveedores</strong></a>
    <i class="fa-solid fa-angle-right"></i> {{ $supplier->name }}
</div>
<br>
<table class="table-border-bottom w-100">
    <thead>
        <tr>
            <td class="rssbanner text-center">#</td>
            <td class="rssbanner">Lotería</td>
            <td class="rssbanner text-center">Fracciones</td>
            <td class="rssbanner text-center">¢Precio</td>
            <td class="rssbanner text-center">%Utilidad</td>
            <td class="rssbanner text-center hideonmobile-cell">Aplicado</td>
        </tr>
    </thead>
    @php
    $int_total_debt = 0;
    $int_total_give = 0;
    $int_total_gain = 0;
    @endphp
    @foreach ($tickets as $id => $ticket)
    @php
    $int_total = $ticket->price * $ticket->fractions * 100;
    $int_total_neto = $int_total - ($int_total * $ticket->supplier_utility / 100);
    $int_give = ($ticket->total_entires + $ticket->total_fractions) * $ticket->price * $ticket->fractions;
    @endphp
    <tbody class="tbody-header">
        <tr class="first-row bold">
            <td class="text-center">{{ $id+1 }}</td>
            <td>{{ $ticket->lottery_name }} ({{ dateES(date('l m/d/Y', strtotime($ticket->lottery_date))) }})</td>
            <td class="text-center">{{ $ticket->fractions }}</td>
            <td class="text-center">{{ number_format($ticket->price, 2) }}</td>
            <td class="text-center">{{ $supplier->utility }}</td>
            <td class="text-center hideonmobile-cell">{{ date('d/m/y h:i A', strtotime($ticket->created_at)) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="6">
                <b>Precio total:</b> ¢ {{ number_format($int_total, 2) }}
                <i class="fa-solid fa-minus"></i>
                <b>Precio neto:</b> ¢ {{ number_format($int_total_neto, 2) }}
                <br>
                <b>Enteros entregados:</b> {{ $ticket->total_entires + $ticket->total_fractions }}
                <i class="fa-solid fa-minus"></i>
                <b>Enteros pendientes:</b> {{ 100 - ($ticket->total_entires + $ticket->total_fractions) }}
                <br>
                <b>Monto entregado:</b> {{ number_format($int_give, 2) }}
                <i class="fa-solid fa-minus"></i>
                <b>Monto neto:</b> {{ number_format($int_give - ($int_give * $supplier->utility / 100), 2)
                }}
                <br>
                <b>Ganancia:</b> ¢ {{ number_format($int_give * $ticket->supplier_utility / 100, 2) }}
            </td>
        </tr>
    </tbody>
    @php
    $int_total_debt += $int_total_neto;
    $int_total_give += $int_give - ($int_give * $supplier->utility / 100);
    $int_total_gain += $int_give * $ticket->supplier_utility / 100;
    @endphp
    @endforeach
    <tbody>
        <tr>
            <td class="rssbanner text-center" colspan="7">
                <table class="m-auto noHover">
                    <tbody style="border: none !important;">
                        <tr>
                            <td>TOTAL NETO:</td>
                            <td class="text-end">{{ number_format($int_total_debt, 2) }}</td>
                        </tr>
                        <tr>
                            <td>ENTREGADO NETO:</td>
                            <td class="text-end">{{ number_format($int_total_give, 2) }}</td>
                        </tr>
                        <tr>
                            <td>GANANCIA NETO:</td>
                            <td class="text-end">{{ number_format($int_total_gain, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>


@endsection