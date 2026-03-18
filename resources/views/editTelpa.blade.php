@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Rediģēt telpu</p>

    <div class="auth-links">
        <a href="/telpa" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Rediģēt telpu</h2>

    @include('partials.validation-errors')

    <!-- Telpas datu rediģēšanas forma -->
    <form method="POST" action="/telpa/{{ $telpa->telpas_id }}/editSubmit" novalidate>
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" value="{{ $telpa->nosaukums }}" required>
        </div>
        <div class="mb-3">
            <label for="platība" class="form-label">Platība</label>
            <input type="text" class="form-control" id="platība" name="platība" value="{{ $telpa->platība }}">
        </div>
        <div class="mb-3">
            <label for="numurs" class="form-label">Numurs</label>
            <input type="number" class="form-control" id="numurs" name="numurs" value="{{ $telpa->numurs }}">
        </div>
        <!-- Saglabā atjauninātos telpas datus -->
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection
