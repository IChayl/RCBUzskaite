@extends('layout.app')

@section('content')
    <h2>Jauna telpa</h2>
    <a href="/telpa" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    <hr>

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
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection
