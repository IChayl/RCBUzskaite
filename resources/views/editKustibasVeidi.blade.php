@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Rediģēt kustības veidu</p>

    <div class="auth-links">
        <a href="/kustibas_veidi" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Rediģēt kustības veidu</h2>

    <form method="POST" action="/kustibas_veidi/{{ $veids->kustibas_veids_id }}/editSubmit">
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" value="{{ $veids->nosaukums }}" required>
        </div>
        <div class="mb-3">
            <label for="apraksts" class="form-label">Apraksts</label>
            <textarea class="form-control" id="apraksts" name="apraksts">{{ $veids->apraksts }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection
