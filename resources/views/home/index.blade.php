@extends('layouts.app')

@section('content')

<style>
    .menu-home i {
        display: block;
        font-size: 18px;
    }

    .menu-home ol {
        display: flex;
        border: none;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .menu-home li {
        border: 1px solid gray;
        margin: 5px;
        width: 120px;
        text-transform: uppercase;
    }

    .menu-home i {
        width: auto !important;
    }

    .menu-home li>div {
        margin: auto !important;
    }

    .break {
        flex-basis: 100%;
        height: 0;
    }
</style>

<div class="text-center">
    <div class="display-6 text-uppercase">
        Bienvenido {{ date('d/m/y h:i a') }}
    </div>
    <br>
    <hr>
    <br>
    <div class="menu-home">
        @include('layouts.leftmenu')
    </div>
    <br>
    <hr>
    <br>
    <div class="display-6 text-uppercase" style="font-size: 20px;">
        Resumen
    </div>
    <br>
    <hr>
    <br>
    <div class="menu-home">
        <ol class="leftmenu-items">
            <li class="p-2 d-flex justify-content-between align-items-start" style="border-radius: 50%; height: 120px;">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">
                        <i class="fa-solid fa-hashtag"></i>
                        Cuotas
                        <br>
                        <span style="font-size: 25px;"> {{ $tickets->count->tickets }} </span>
                    </div>
                </div>
            </li>
            <li class="p-2 d-flex justify-content-between align-items-start" style="border-radius: 50%; height: 120px;">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">
                        <i class="fa-solid fa-circle-dollar-to-slot"></i>
                        Enteros
                        <br>
                        <span style="font-size: 25px;"> {{ $tickets->count->tickets * 100 }} </span>
                    </div>
                </div>
            </li>
            <li class="p-2 d-flex justify-content-between align-items-start" style="border-radius: 50%; height: 120px;">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">
                        <i class="fa-solid fa-stopwatch"></i>
                        Pendientes
                        <br>
                        <span style="font-size: 25px;"> {{ round(($tickets->count->tickets * 100) -
                            $tickets->info->all_entires, 2) }} </span>
                    </div>
                </div>
            </li>
            <div class="break"></div>
            <li class="p-2 d-flex justify-content-between align-items-start" style="border-radius: 50%; height: 120px;">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">
                        <i class="fa-solid fa-handshake"></i>
                        Entregados
                        <br>
                        <span style="font-size: 25px;"> {{ round($tickets->info->all_entires, 2) }} </span>
                    </div>
                </div>
            </li>
            <li class="p-2 d-flex justify-content-between align-items-start"
                style="border-radius: 50%; width: 200px; height: 200px;">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">
                        <i class="fa-solid fa-sack-dollar" style="font-size: 28px;"></i>
                        Monto (Entregado)
                        <br>
                        <span style="font-size: 25px;"> {{ number_format($tickets->info->all_entires_price, 2) }}
                        </span>
                    </div>
                </div>
            </li>
        </ol>
    </div>



</div>

@endsection