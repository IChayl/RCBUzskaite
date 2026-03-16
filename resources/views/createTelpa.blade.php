@extends('layout.app')

@section('content')
    <p style="color: #FAF8F2;">Jauna telpa</p>

    <div class="auth-links">
        <a href="/telpa" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #FAF8F2;">Jauna telpa</h2>

    <form method="POST" action="{{ route('telpa.store') }}">
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" required>
        </div>
        <div class="mb-3">
            <label for="izmeri" class="form-label">Izmēri</label>
            <input type="text" class="form-control" id="izmeri" name="izmeri">
        </div>
        <div class="mb-3">
            <label for="numurs" class="form-label">Numurs</label>
            <input type="number" class="form-control" id="numurs" name="numurs">
        </div>
        <div class="mb-3">
            <label for="stavs" class="form-label">Stāvs</label>
            <input type="number" class="form-control" id="stavs" name="stavs" required>
        </div>
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection
