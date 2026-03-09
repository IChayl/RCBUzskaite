@extends('layout.app')

@section('content')
    <h2>Jauns kustības veids</h2>
    <a href="/kustibas_veidi" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    <hr>

    <form method="POST" action="{{ url('/kustibas_veidi') }}">
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" required>
        </div>
        <div class="mb-3">
            <label for="apraksts" class="form-label">Apraksts</label>
            <textarea class="form-control" id="apraksts" name="apraksts"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection
