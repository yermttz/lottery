@extends('layouts.app')

@section('toolbar')
<a href="/tickets/create">
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
    <a href="/tickets"><strong>Cuotas</strong></a>
</div>
<br>
<table class="table-border-bottom w-100">
    <thead>
        <tr>
            <td class="rssbanner text-center">#</td>
            <td class="rssbanner">Proveedor</td>
            <td class="rssbanner text-center">Lotería</td>
            <td class="rssbanner text-center"># Fracciones</td>
            <td class="rssbanner text-center">¢ Precio</td>
            <td class="rssbanner text-center hideonmobile-cell">Creado</td>
        </tr>
    </thead>

    @foreach ($tickets as $id => $ticket)
    <tbody>
        <tr class="first-row bold">
            <td class="text-center">{{ $id+1 }}</td>
            <td>{{ $ticket->supplier_name }}</td>
            <td class="text-center">{{ $ticket->lottery_name }}</td>
            <td class="text-center">{{ $ticket->fractions }}</td>
            <td class="text-center">{{ number_format($ticket->price, 2) }}</td>
            <td class="text-center hideonmobile-cell">{{ date('d/m/y h:i A', strtotime($ticket->created_at)) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="5">
                <b>Precio total de la cuota:</b> ¢ {{ number_format($ticket->price * $ticket->fractions * 100, 2) }}
                <br>
                <b>Precio neto:</b> ¢ {{ number_format(($ticket->price * $ticket->fractions * 100) - ($ticket->price *
                $ticket->fractions * 100 * ($ticket->supplier_utility / 100)), 2) }}
                <br>
                <b>Ganancia:</b> ¢ {{ number_format($ticket->price *
                $ticket->fractions * 100 * ($ticket->supplier_utility / 100), 2) }}
                <br>
                <b>Enteros entregados:</b> {{ $ticket->total_entires + $ticket->total_fractions }}
                <i class="fa-solid fa-minus"></i>
                <b>Enteros pendientes:</b> {{ 100 - ($ticket->total_entires + $ticket->total_fractions) }}
                <br>
                <div class="showonmobile"><b>Creado:</b> {{ date('d/m/y h:i A', strtotime($ticket->created_at)) }}</div>
            </td>
        </tr>
    </tbody>
    @endforeach
</table>


@endsection