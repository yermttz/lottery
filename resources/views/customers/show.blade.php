@extends('layouts.app')

@section('toolbar')
<a href="/customers/return?c={{$id}}">
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
<a href="/customers/give?c={{$id}}">
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
    <i class="fa-solid fa-angle-right"></i> {{ $customer->name }}
</div>

<ul class="toolbar-items d-flex" style="align-items: center; justify-content: end; height: 50px;">
    <a href="/customers/pay?c={{$id}}">
        <li class="px-2"
            style="text-align:center; justify-content: center; height: 50px;width:60px; display: flex; align-items: center; border: none;">
            <div style="line-height: 10px;">
                <i class="fa-solid fa-2x fa-money-bill-transfer"></i>
                <br>
                <small style="font-size: 12px;">
                    Aplicar Saldo
                </small>
            </div>
        </li>
    </a>
</ul>

<table class="table-border-bottom w-100">
    <thead>
        <tr>
            <td class="rssbanner text-center">#</td>
            <td class="rssbanner">Lotería</td>
            <td class="rssbanner text-center">Enteros</td>
            <td class="rssbanner text-center">Fracciones</td>
            <td class="rssbanner text-center">¢Precio</td>
            <td class="rssbanner text-center">%Utilidad</td>
            <td class="rssbanner text-center hideonmobile-cell">Aplicado</td>
        </tr>
    </thead>
    @php
    $int_total_debt = 0;
    $int_tota_gain = 0;
    @endphp
    @foreach ($tickets as $id => $ticket)
    @php
    $int_fractions = ($ticket->entires * $ticket->tickets_fractions) + $ticket->fractions;
    $int_total = $int_fractions * $ticket->ticket_price;
    $int_neto = $int_total - ($int_total * $customer->utility / 100);
    @endphp
    <tbody
        class="tbody-header {{ ($ticket->entires < 0) ? 'red' : '' }} {{ ($ticket->type == 'balance') ? 'green' : '' }} ">
        @if($ticket->type == 'ticket')
        <tr class="first-row bold">
            <td class="text-center">{{ $id+1 }}</td>
            <td>{{ $ticket->lottery_name }} ({{ dateES(date('l d/m/Y', strtotime($ticket->lottery_date))) }})</td>
            <td class="text-center">{{ $ticket->entires }}</td>
            <td class="text-center">{{ $ticket->fractions }}</td>
            <td class="text-center">{{ number_format($ticket->ticket_price, 2) }}</td>
            <td class="text-center">{{ $customer->utility }}</td>
            <td class="text-center hideonmobile-cell">{{ date('d/m/y h:i A',
                strtotime($ticket->created_at)) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="6">
                @if ($int_fractions < 0) <b style="color: red;">Devolución</b> <br> @endif
                    <b>Total fracciones:</b> {{ $int_fractions }}
                    <br>
                    <b>Monto total:</b> {{ number_format($int_total, 2) }}
                    <i class="fa-solid fa-minus"></i>
                    <b>Monto neto:</b> {{ number_format($int_neto, 2) }}
                    <br>
                    <b>Ganancia:</b> {{ number_format($int_total - $int_neto, 2) }}
                    <br>
                    <b>Aplicado por:</b> {{ $ticket->created_by }}
                    <div class="showonmobile">
                        <b>Aplicado:</b> {{ date('d/m/y h:i A', strtotime($ticket->created_at)) }}
                    </div>
            </td>
        </tr>
        @endif
        @if($ticket->type == 'balance')
        <tr class="first-row bold">
            <td class="text-center pt-3 pb-3">{{ $id+1 }}</td>
            <td>{{ ($ticket->typebalance == 1) ? 'PAGO' : 'RECIBO' }}</td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center">{{ number_format($ticket->amount, 2) }}</td>
            <td class="text-center"></td>
            <td class="text-center hideonmobile-cell">{{ date('d/m/y h:i A',
                strtotime($ticket->created_at)) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="6">
                <b>Aplicado por:</b> {{ $ticket->created_by }}
                <div class="showonmobile">
                    <b>Aplicado:</b> {{ date('d/m/y h:i A', strtotime($ticket->created_at)) }}
                </div>
            </td>
        </tr>
        @endif
    </tbody>
    @php
    $int_total_debt += $int_total - ($int_total * $customer->utility / 100);
    $int_tota_gain += $int_total - $int_neto;
    @endphp
    @endforeach
    <tbody>
        <tr>
            <td class="rssbanner text-center" colspan="7">
                <table class="noHover" style="margin-right: 0; margin-left: auto; width: auto;">
                    <tbody style="border: none !important;">
                        <tr>
                            <td style="padding-right: 10px;">TOTAL NETO A COBRAR:</td>
                            <td class="text-end">{{ number_format($int_total_debt, 2) }}</td>
                        </tr>
                        <tr>
                            <td>TOTAL PAGADO:</td>
                            <td class="text-end">{{ number_format($balances->pay, 2) }}</td>
                        </tr>
                        <tr>
                            <td>TOTAL COBRADO:</td>
                            <td class="text-end">{{ number_format($balances->receipt, 2) }}</td>
                        </tr>
                        <tr>
                            <td>SALDO:</td>
                            <td class="text-end">{{ number_format($int_total_debt - $balances->receipt + $balances->pay,
                                2) }}</td>
                        </tr>
                        <tr>
                            <td>GANANCIA NETO:</td>
                            <td class="text-end">{{ number_format(($balances->receipt - $balances->pay) *
                                $customer->utility / 100, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>


@endsection