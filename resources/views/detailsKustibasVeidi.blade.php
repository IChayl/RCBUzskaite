@extends('layout.app')

@section('content')
    <p style="color: #FAF8F2;">Visi dati</p>
    <div class="auth-links">
        <a href="/kustibas_veidi">Atpakaļ uz veidiem</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/kustibas_veidi/{{ $veids->kustibas_veids_id }}/edit" class="bloom-button sm">Rediģēt</a>
        @endif
    </div>

    <hr>
    <h2 style="color: #FAF8F2;">Kustības veida detaļas</h2>
    <div class="card mt-3" style="background: rgba(36, 59, 83, 0.65); border: 1px solid rgba(209, 195, 165, 0.2); color: #FAF8F2; max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">{{ $veids->nosaukums }} (ID: {{ $veids->kustibas_veids_id }})</h5>
            <p class="card-text"><strong>Nosaukums:</strong> {{ $veids->nosaukums }}</p>
            <p class="card-text"><strong>Apraksts:</strong> {{ $veids->apraksts ?? '-' }}</p>
        </div>
    </div>
@endsection
