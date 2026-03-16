@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi dati</p>
    <div class="auth-links">
        <a href="/inventars">Atpakaļ uz inventāriem</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/inventars/{{ $inventar->inventars_id }}/edit" class="bloom-button sm">Rediģēt</a>
        @endif
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Inventāra detaļas</h2>
    <!-- Inventāra pilnā informācija kartītes skatā -->
    <div class="card mt-3" style="background: rgba(45, 65, 89, 0.65); border: 1px solid rgba(226, 212, 187, 0.2); color: #E2D4BB; max-width: 700px;">
        <div class="card-body">
            <h5 class="card-title">{{ $inventar->nosaukums }} (ID: {{ $inventar->inventars_id }})</h5>
            <p class="card-text"><strong>Apraksts:</strong> {{ $inventar->apraksts ?? '-' }}</p>
            <p class="card-text"><strong>Statuss:</strong> {{ $inventar->statuss ?? '-' }}</p>
            <p class="card-text"><strong>Kategorija:</strong> {{ $inventar->kategorija->nosaukums ?? ('ID: '.$inventar->kategorija_id) }}</p>
            <p class="card-text"><strong>Telpa:</strong> {{ optional($inventar->telpa)->nosaukums ?? ('ID: '.$inventar->telpas_id) }}</p>
            <p class="card-text"><strong>Atbildīgais:</strong> {{ optional($inventar->atbildigais)->lietotajvards ?? ('ID: '.$inventar->atbildigais_id) }}</p>
            <p class="card-text"><strong>Inventāra numurs:</strong> {{ $inventar->inventara_numurs ?? '-' }}</p>
            <p class="card-text"><strong>Iegādes datums:</strong> {{ $inventar->iegades_datums ?? '-' }}</p>
        </div>
    </div>
@endsection