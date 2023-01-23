<!doctype html>
<html lang="es">
@php
$settings_All = \App\Models\Setting::all();
$settings = [];
foreach ($settings_All as $value) {
$settings[$value["key"]] = $value["value"];
}
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
    <title>{{ $settings["title"] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <script src="https://kit.fontawesome.com/80b5229b62.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        a {
            text-decoration: none;
            color: #000000;
        }

        a:hover {
            text-decoration: none;
            background: #fffde8;
            color: #000000;
        }

        .rssbanner-btn {
            background-color: #c0c0c0;
            border: 1px solid gray;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
            padding: 0.25rem;
            height: 40px;
        }

        .rssbanner-btn:hover {
            background: #fffde8;
            color: #000000;
            border: 1px solid gray;

        }

        .htmloutterdiv {
            width: 8.5in;
            min-height: 11in;
            height: auto;
            background-color: #ffffff;
            border: 1px solid gray;
            margin: 0.25rem;
            padding: 0.375in;
        }

        hr {
            margin: 0;
        }

        .noHover {
            pointer-events: none;
        }

        .htmlviewtitle {
            text-align: right;
            font-family: verdana;
            font-size: 16px;
            font-weight: bold;
        }

        .htmlviewheader {
            text-align: right;
            font-family: verdana;
            font-size: 12px;
        }

        div#toolbar {
            position: fixed;
            z-index: 100;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 48px;
            border-bottom: 1px solid gray;
            background-color: #e4e0d8;
        }


        .leftmenu {
            vertical-align: top;
            background-color: #d4d0c8;
            position: relative;
            text-align: left;
            border-right: 1px solid gray;
            width: 200px;
            min-width: 200px;
        }

        .leftmenu a {
            cursor: pointer;
        }


        .leftmenu-items {
            border-right: 1px solid gray;
            padding: 0;
            margin: 0;
        }

        .leftmenu-items li {
            cursor: pointer;
        }

        .leftmenu-items li:hover {
            text-decoration: none;
            background: #fffde8;
            color: #000000;
        }

        .reportheader {
            margin-bottom: 10px;
        }

        .htmlcontainer {
            width: 8.5in;
        }

        @media only screen and (max-width: 1240px) {
            .rightmenu {
                display: none;
            }
        }

        .showMenu {
            display: none;
        }

        .showonmobile {
            display: none;
        }

        .hideonmobile {
            display: block;
        }

        .hideonmobile-cell {
            display: table-cell;
        }

        /** MOBILE VERSION **/
        @media only screen and (max-width: 1000px) {
            .htmloutterdiv {
                min-height: auto;
                height: auto;
            }

            .noselect,
            * {
                -webkit-touch-callout: none;
                /* iOS Safari */
                -webkit-user-select: none;
                /* Safari */
                -khtml-user-select: none;
                /* Konqueror HTML */
                -moz-user-select: none;
                /* Old versions of Firefox */
                -ms-user-select: none;
                /* Internet Explorer/Edge */
                user-select: none;
                /* Non-prefixed version, currently
            supported by Chrome, Edge, Opera and Firefox */
            }

            .showMenu {
                display: block;
            }

            .leftmenu,
            .hideonmobile,
            .hideonmobile-cell {
                display: none;
            }

            .showonmobile {
                display: block;
            }

            .htmloutterdiv,
            .htmlcontainer {
                width: 100%;
                min-width: 100%;
                margin: 0;
                border: 0;
                padding: 0.05in;
            }
        }

        .rssbanner {
            background-color: #c0c0c0;
            border: 1px solid gray;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
            padding: 0.25rem;
        }

        .first-row td {
            padding: 0.25rem;
        }

        .bold {
            font-weight: bold;
        }

        .red td {
            background: #ffd2d2;
        }

        .green td {
            background: #b7e7b7;
        }

        .table-border-bottom tbody {
            border-top: 1px solid gray;
            padding: 5px;
            cursor: pointer;
        }

        .table-border-bottom tbody:nth-child(even) {
            background: #f7f7f7;
        }

        .table-border-bottom tbody:hover {
            background: #fffde8;
        }

        div#maintablewrapper {
            position: absolute;
            height: auto;
            bottom: 0px;
            width: 100%;
            top: 48px;
        }

        .toolbar-items {
            margin: 0;
        }

        .toolbar-items li {
            cursor: pointer;
            border-bottom: 1px solid gray;
        }

        .form-control,
        .btn {
            border-radius: 0px;
        }

        .overflow-table {
            overflow-x: hidden;
        }

        @media print {
            body {
                background-color: #ffffff;
                overflow-y: visible;
                zoom: 100%;
                -webkit-print-color-adjust: exact;
            }

            .htmloutterdiv {
                margin: 0px !important;
                padding: 0px !important;
                height: auto;
                width: 100%;
                border: 0px;
            }

            table {
                width: 100%;
            }

            div#maintablewrapper {
                top: 0px;
                overflow-y: visible;
                position: static;
                bottom: auto;
                width: auto;
                top: auto;
                height: auto;
            }

            .hideonprint {
                display: none !important;
            }
        }
    </style>
</head>
<script>
    function showMenu() {
        var yourUl = document.getElementById("mobilemenu");
        if(yourUl.style.display === '') {
            yourUl.style.display = 'block';
        } else {
            yourUl.style.display = yourUl.style.display === 'none' ? 'block' : 'none';
        }
    }
</script>

<body>
    <div id="toolbar" class="hideonprint">
        <div class="d-flex">
            <ul class="toolbar-items border-bottom-gray d-flex"
                style=" padding:0; width: 200px;height: 48px; align-items: center; justify-content: end;">
                <a href="javascript:showMenu()" class="showMenu">
                    <li class="px-2"
                        style="text-align:center; justify-content: center; height: 48px;width:60px; display: flex; align-items: center;">
                        <div style="line-height: 10px;">
                            <i class="fa-solid fa-2x fa-bars"></i>
                        </div>
                    </li>
                </a>
                <a href="javascript:history.back()">
                    <li class="px-2"
                        style="text-align:center; justify-content: center; height: 48px;width:60px; display: flex; align-items: center;">
                        <div style="line-height: 10px;">
                            <i class="fa-solid fa-2x fa-arrow-left"></i>
                            <br>
                            <small style="font-size: 12px;">
                                Regresar
                            </small>
                        </div>
                    </li>
                </a>
            </ul>
            <ul class="toolbar-items border-bottom-gray d-flex"
                style="align-items: center; justify-content: end; width: 8.6in; height: 48px;">
                @yield('toolbar')
            </ul>
        </div>
    </div>

    <div id="maintablewrapper">

        <div id="mobilemenu" class="showonmobile"
            style="position: fixed; width: 200px; background-color: #d4d0c8; display: none; z-index: 9999;">
            @include('layouts.leftmenu')
        </div>

        <table class="w-100">
            <tbody>
                <tr>
                    <td class="leftmenu hideonprint" id="leftmenu">
                        <div style="position: fixed; width: 200px; background-color: #d4d0c8;">
                            @include('layouts.leftmenu')
                        </div>
                    </td>
                    <td class="overflow-table" valign="top" align="left" id="overflow-table">
                        <div class="htmlcontainer">
                            @if(session()->has('message'))
                            @if(session()->get('type') == "success")
                            <div class="alert alert-success alert-dismissible fade show mb-2 hideonprint" role="alert">
                                <i class="fa-solid fa-check" style="font-size: 1.5em;"></i> <strong>{{
                                    session()->get('message') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif
                            @endif

                            <div id="htmloutterdiv" class="htmloutterdiv">
                                <table class="reportheader" width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="1"><img style="max-height: 100px; max-width: 200px;"
                                                    id="core_logo_image" src="/uploads/{{ $settings['logo'] }}">
                                            </td>
                                            <td class="htmlviewheader" valign="top" style="text-align: right;">
                                                <font class="htmlviewtitle">
                                                    <span id="core_header_title">{{ $settings["title"] }}</span>
                                                </font>
                                                <hr><span id="core_header_bottom">{{ $settings["description"] }}<br>{{
                                                    $settings["description2"] }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                @yield('content')

                                <div class="p-5 text-center hideonprint">
                                    <i class="fa-regular fa-copyright"></i> 2022 - Mttiempoz Group
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!--
    <div>
        <div class="row g-0 hideonprint" id="toolbar">
            <div class="col-2" style="border-right: 1px solid gray;">
                as
            </div>
            <div class="col-8 d-flex" style="align-items: center; justify-content: end;">
                <div class="px-2"
                    style="text-align:center; justify-content: center; border-right: 1px solid gray; height: 48px;width:60px; display: flex; align-items: center;">
                    <div style="line-height: 10px;">
                        <i class="fa-solid fa-1x fa-house"></i>
                        <br>
                        <small style="font-size: 12px;">
                            Home
                        </small>
                    </div>
                </div>
                <a href="javascript:void(processPrint('htmloutterdiv'));">Print</a>
                <div class="px-2" onClick='window.print()'
                    style="text-align:center;justify-content: center; height: 48px; width:60px; display: flex; align-items: center;">
                    <div style="line-height: 10px;">
                        <i class="fa-solid fa-2x fa-print"></i>
                        <br>
                        <small style="font-size: 12px;">
                            Imprimir
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-2" style="border-left: 1px solid gray;">
                cs
            </div>
        </div>

        <div class="hideonprint" style="height: 48px;"></div>

        <div class="row g-0" style="height: 100vh;">
            <div class="col-2 position-fixed leftmenu hideonprint" id="sticky-sidebar"
                style="height: 100vh; border-right: 1px solid gray;">
                @yield('leftmenu')
            </div>
            <div class="col-8 offset-2" id="main" style="background: #C4C0B8">
                <div id="htmloutterdiv" class="htmloutterdiv mt-1 mb-2" style="padding: 0.375in; margin: auto;">
                    <table class="reportheader" width="100%">
                        <tbody>
                            <tr>
                                <td width="1"><img style="margin-bottom: 10px;" id="core_logo_image"
                                        src="https://cip.trial.cipreporting.com/svc/media/logo/cip/3">
                                </td>
                                <td class="htmlviewheader" valign="top" style="text-align: right;">
                                    <font class="htmlviewtitle">
                                        <span id="core_header_title">CIP Reporting
                                            Trial Management</span>
                                    </font>
                                    <hr><span id="core_header_bottom">www.cipreporting.com<br>888.975.2040</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @yield('content')

                </div>
            </div>
            <div class="col-2 offset-10 position-fixed rightmenu hideonprint" id="sticky-sidebar"
                style="height: 100vh; border-left: 1px solid gray;">
                cs
            </div>
        </div>
    </div>
-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
</body>

</html>