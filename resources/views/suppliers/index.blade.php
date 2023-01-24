@extends('layouts.app')

@section('toolbar')
<a href="/suppliers/create">
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
    <a href="/suppliers"><strong>Proveedores</strong></a>
    <i class="fa-solid fa-angle-right"></i> Semana abierta
</div>
<br>
<table class="table-border-bottom w-100">
    <thead>
        <tr>
            <td class="rssbanner text-center">#</td>
            <td class="rssbanner">Nombre</td>
            <td class="rssbanner text-center">%Utilidad</td>
            <td class="rssbanner text-center">Creado</td>
        </tr>
    </thead>
    @php
    $int_total_debt = 0;
    $int_total_give = 0;
    $int_tota_gain = 0;
    @endphp
    @foreach ($suppliers as $id => $supplier)
    @php
    $int_total = $supplier->count_price;
    $int_neto = $int_total - ($int_total * $supplier->utility / 100);
    @endphp
    <tbody class="tbody-header" onclick="window.location='/suppliers/{{ $supplier->id}}';">
        <tr class="first-row bold">
            <td class="text-center" style="width: 15px;">{{ $id+1 }} <br> <a href="/suppliers/{{$supplier->id}}/edit"
                    style="color: red;"><i class="fa-solid fa-trash"></i></a></td>
            <td>{{ $supplier->name }}</td>
            <td class="text-center" style="width: 25px;">{{ $supplier->utility }}</td>
            <td class="text-center" style="width: 125px;">{{ date('d/m/y h:i A', strtotime($supplier->created_at)) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                <b>Total cuotas:</b> {{ $supplier->count }} <i class="fa-solid fa-minus"></i> <b>Total enteros:</b> {{
                $supplier->count_entires }}
                <br>
                <b>Monto total:</b> {{ number_format($int_total, 2) }}
                <i class="fa-solid fa-minus"></i>
                <b>Monto neto:</b> {{ number_format($int_neto, 2) }}
                <br>
                <b>Enteros entregados:</b> {{ $supplier->entires + $supplier->fractions }}
                <i class="fa-solid fa-minus"></i>
                <b>Enteros pendientes:</b> {{ $supplier->count_entires - ($supplier->entires +
                $supplier->fractions) }}
                <br>
                <b>Monto entregado:</b> {{ number_format($supplier->total_entires, 2) }}
                <i class="fa-solid fa-minus"></i>
                <b>Monto neto:</b> {{ number_format($supplier->total_entires - ($supplier->total_entires *
                $supplier->utility / 100), 2) }}
                <br>
                <b>Ganancia:</b> {{ number_format($supplier->total_entires *
                $supplier->utility / 100, 2) }}
            </td>
        </tr>
    </tbody>
    @php
    $int_total_debt += $int_neto;
    $int_total_give += $supplier->total_entires - ($supplier->total_entires * $supplier->utility / 100);
    $int_tota_gain += $supplier->total_entires * $supplier->utility / 100;
    @endphp
    @endforeach
    <tbody>
        <tr>
            <td class="rssbanner text-center" colspan="4">
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
                            <td class="text-end">{{ number_format($int_tota_gain, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>


@endsection