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
    <a href="/settings"><strong>Ajustes</strong></a>
</div>
<br>
<form method="post" enctype="multipart/form-data">
    @csrf
    <table class="table-border-bottom w-100">
        @foreach ($settings as $id => $setting)
        <tbody class="tbody-header">
            <tr class="first-row bold">
                <td class="text-center" style="width: 15px;">{{ $id+1 }}</td>
                <td><input type="{{ $setting->type }}" name="{{ $setting->type }}:{{ $setting->key }}"
                        value="{{ $setting->value }}" class="w-100"></td>
                <td>{{ $setting->description }}</td>
                </td>
            </tr>
            @error($setting->key)
            <tr>
                <td colspan="3">
                    <div class="alert alert-danger p-1 mt-1">
                        ERROR: {{ $errors->first($setting->key) }}
                    </div>
                </td>
            </tr>
            @enderror
        </tbody>
        @endforeach
    </table>
    <br>
    <input class="rssbanner-btn w-100 text-uppercase" type="submit" value="Guardar Ajustes">
</form>

@endsection