@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi dati</p>
    <div class="auth-links">
        <a href="/kategorija">Atpakaļ uz kategorijām</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/kategorija/{{ $kategorija->kategorija_id }}/edit" class="bloom-button sm">Rediģēt</a>
        @endif
    </div>

    <hr>
    <h2 style="color: #E2D4BB;">Kategorijas detaļas</h2>
    <!-- Kategorijas informācija kartītes veidā -->
    <div class="card mt-3" style="background: rgba(45, 65, 89, 0.65); border: 1px solid rgba(226, 212, 187, 0.2); color: #E2D4BB; max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">{{ $kategorija->nosaukums }} (ID: {{ $kategorija->kategorija_id }})</h5>
            <p class="card-text"><strong>Nosaukums:</strong> {{ $kategorija->nosaukums }}</p>
            <p class="card-text"><strong>Apraksts:</strong> {{ $kategorija->apraksts ?? '-' }}</p>
        </div>
    </div>
@endsection