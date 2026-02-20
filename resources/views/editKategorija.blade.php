@extends('layout.app')

@section('content')
    <h2>Rediģēt Kategoriju</h2>
    <a href="/kategorija" class="btn btn-secondary">Atpakaļ</a>
    <hr>

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