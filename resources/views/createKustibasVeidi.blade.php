@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Jauns kustības veids</p>

    <div class="auth-links">
        <a href="/kustibas_veidi" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Jauns kustības veids</h2>

    @include('partials.validation-errors')

    <!-- Forma jauna kustības veida pievienošanai -->
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
        <!-- Iesniedz formas datus saglabāšanai -->
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection
