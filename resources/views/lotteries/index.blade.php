@extends('layouts.app')

@section('toolbar')
<a href="/lotteries/create">
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
    <a href="/lotteries"><strong>Loterías</strong></a>
</div>
<br>
<table class="table-border-bottom w-100">
    <thead>
        <tr>
            <td class="rssbanner text-center">#</td>
            <td class="rssbanner">Nombre</td>
            <td class="rssbanner text-center">Fecha</td>
            <td class="rssbanner text-center">Creado</td>
        </tr>
    </thead>
    @foreach ($lotteries as $id => $lottery)
    <tbody class="tbody-header">
        <tr class="first-row bold">
            <td class="text-center">{{ $id+1 }} <br> <a href="/lotteries/{{$lottery->id}}/edit" style="color: red;"><i
                        class="fa-solid fa-trash"></i></a>
            </td>
            <td>{{ $lottery->name }}</td>
            <td class="text-center">{{ dateEs(date('l d-m-Y', strtotime($lottery->date))) }}</td>
            <td class="text-center">{{ date('d-m-Y h:i a', strtotime($lottery->created_at)) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                <b>Total cuotas:</b> {{ $lottery->count }}
                <i class="fa-solid fa-minus"></i>
                <b>Total enteros:</b> {{ $lottery->count * 100 }}
                <br>
                <b>Enteros entregados:</b> {{ $lottery->entires + $lottery->fractions }}
                <i class="fa-solid fa-minus"></i>
                <b>Enteros pendientes:</b> {{ (100 * $lottery->count) - ($lottery->entires + $lottery->fractions) }}
            </td>
        </tr>
    </tbody>
    @endforeach
</table>


@endsection