@extends('layouts.app')

@section('content')

<div class="rssbanner text-uppercase">
    <a href="/tickets"><strong>Cuotas</strong></a>
    <i class="fa-solid fa-angle-right"></i> Agregar
</div>
<br>
<div class="text-center m-auto" style="width: 300px;">

    @error('login')
    <div class="alert alert-danger p-1 mb-1">
        ERROR: {{ $errors->first('login') }}
    </div>
    @enderror

    <form action="/tickets" method="post">
        @csrf
        <div class="form-floating mb-3">
            <select type="text" class="form-control" name="supplierid" id="supplierid" placeholder="Proveedor"
                value="{{ old('supplierid') }}">
                @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplierid')==$supplier->id ? 'selected' : '' }}>{{
                    $supplier->name
                    }}
                </option>
                @endforeach
            </select>
            <label for="supplierid" class="form-label">Proveedor</label>
            @error('supplierid')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('supplierid') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <select type="text" class="form-control" name="lotteryid" id="lotteryid" placeholder="Lotería"
                value="{{ old('name') }}">
                @foreach ($lotteries as $lottery)
                <option value="{{ $lottery->id }}" {{ old('lotteryid')==$lottery->id ? 'selected' : '' }}>{{
                    $lottery->name }}</option>
                @endforeach
            </select>
            <label for="lotteryid" class="form-label">Lotería</label>
            @error('lotteryid')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('lotteryid') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="entires" id="entires" placeholder="# Fracciones"
                value="{{ empty(old('entires')) ? 100 : old('entires') }}" step=".01">
            <label for="entires" class="form-label"># Enteros</label>
            @error('entires')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('entires') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="fractions" id="fractions" placeholder="# Fracciones"
                value="{{ old('fractions') }}" step=".01">
            <label for="fractions" class="form-label"># Fracciones</label>
            @error('fractions')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('fractions') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="price" id="price" placeholder="Precio por Fracción"
                value="{{ old('price') }}" step=".01">
            <label for="price" class="form-label">Precio por Fracción</label>
            @error('price')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('price') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="serie" id="serie" placeholder="Seríe (Opcional)"
                value="{{ old('serie') }}">
            <label for="serie" class="form-label">Seríe (Opcional)</label>
            @error('serie')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('serie') }}
            </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="emission" id="emission" placeholder="Emisión (Opcional)"
                value="{{ old('emission') }}">
            <label for="emission" class="form-label">Emisión (Opcional)</label>
            @error('emission')
            <div class="alert alert-danger p-1 mt-1">
                ERROR: {{ $errors->first('emission') }}
            </div>
            @enderror
        </div>
        <button type="submit" class="rssbanner-btn w-100 text-uppercase">Crear Cuota</button>
    </form>
</div>

@endsection