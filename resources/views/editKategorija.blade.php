@extends('layout.app')

@section('content')
    <p style="color: #FAF8F2;">Rediģēt kategoriju</p>

    <div class="auth-links">
        <a href="/kategorija" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #FAF8F2;">Rediģēt kategoriju</h2>

    <form method="POST" action="/kategorija/{{ $kategorija->kategorija_id }}/editSubmit">
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" value="{{ $kategorija->nosaukums }}" required>
        </div>
        <div class="mb-3">
            <label for="apraksts" class="form-label">Apraksts</label>
            <input type="text" class="form-control" id="apraksts" name="apraksts" value="{{ $kategorija->apraksts }}">
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection