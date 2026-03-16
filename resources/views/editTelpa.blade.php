@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Rediģēt telpu</p>

    <div class="auth-links">
        <a href="/telpa" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Rediģēt telpu</h2>

    <form method="POST" action="/telpa/{{ $telpa->telpas_id }}/editSubmit">
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" value="{{ $telpa->nosaukums }}" required>
        </div>
        <div class="mb-3">
            <label for="izmeri" class="form-label">Izmēri</label>
            <input type="text" class="form-control" id="izmeri" name="izmeri" value="{{ $telpa->izmeri }}">
        </div>
        <div class="mb-3">
            <label for="numurs" class="form-label">Numurs</label>
            <input type="number" class="form-control" id="numurs" name="numurs" value="{{ $telpa->numurs }}">
        </div>
        <div class="mb-3">
            <label for="stavs" class="form-label">Stāvs</label>
            <input type="number" class="form-control" id="stavs" name="stavs" value="{{ $telpa->stavs }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection
