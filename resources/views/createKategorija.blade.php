@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Jauna kategorija</p>

    <div class="auth-links">
        <a href="/kategorija" class="btn btn-secondary">Atpakal uz kategoriju sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Jauna kategorija</h2>

    @include('partials.validation-errors')

    <!-- Jaunas kategorijas izveides forma -->
    <form method="POST" action="{{ route('kategorijas.store') }}" novalidate>
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" required>
        </div>
        <div class="mb-3">
            <label for="apraksts" class="form-label">Apraksts</label>
            <textarea class="form-control" id="apraksts" name="apraksts"></textarea>
        </div>
        <!-- Saglabā ierakstu datubāzē -->
        <button type="submit" class="btn btn-primary">Saglabat</button>
    </form>
@endsection
