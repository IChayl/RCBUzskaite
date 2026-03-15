@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
    <div class="auth-links">
        <a href="/telpa">Atpakaļ uz telpām</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/telpa/{{ $telpa->telpas_id }}/edit" class="bloom-button sm">Rediģēt</a>
        @endif
    </div>

    <hr>
    <h2 style="color: #ffffff;">Telpas detaļas</h2>
    <div class="card mt-3" style="background: rgba(73, 7, 0, 0.65); border: 1px solid rgba(255,255,255,0.2); color: #ffffff; max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">{{ $telpa->nosaukums }} (ID: {{ $telpa->telpas_id }})</h5>
            <p class="card-text"><strong>Izmēri:</strong> {{ $telpa->izmeri ?? '-' }}</p>
            <p class="card-text"><strong>Numurs:</strong> {{ $telpa->numurs ?? '-' }}</p>
            <p class="card-text"><strong>Stāvs:</strong> {{ $telpa->stavs }}</p>
        </div>
    </div>
@endsection
