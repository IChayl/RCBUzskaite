@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi dati</p>
    <div class="auth-links">
        <a href="/inventara_kustiba">Atpakaļ uz kustībām</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/inventara_kustiba/{{ $kustiba->kustiba_id }}/edit" class="bloom-button sm">Rediģēt</a>
        @endif
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Inventāra kustības detaļas</h2>
    <div class="card mt-3" style="background: rgba(45, 65, 89, 0.65); border: 1px solid rgba(226, 212, 187, 0.2); color: #E2D4BB; max-width: 700px;">
        <div class="card-body">
            <h5 class="card-title">Kustība #{{ $kustiba->kustiba_id }}</h5>
            <p class="card-text"><strong>Datums:</strong> {{ $kustiba->datums }}</p>
            <p class="card-text"><strong>Inventārs:</strong> {{ $kustiba->inventars->nosaukums ?? ('ID: '.$kustiba->inventars_id) }}</p>
            <p class="card-text"><strong>Kustības veids:</strong> {{ optional($kustiba->kustibasVeids)->nosaukums ?? ('ID: '.$kustiba->kustibas_veids_id) }}</p>
            <p class="card-text"><strong>Atbildīgais:</strong> {{ optional($kustiba->lietotajs)->lietotajvards ?? ('ID: '.$kustiba->atbildigais_lietotajs_id) }}</p>
            <p class="card-text"><strong>Vecā telpa:</strong> {{ optional($kustiba->vecaTelpa)->nosaukums ?? ('ID: '.$kustiba->veca_telpa_id) }}</p>
            <p class="card-text"><strong>Jaunā telpa:</strong> {{ optional($kustiba->jaunaTelpa)->nosaukums ?? ('ID: '.$kustiba->jauna_telpa_id) }}</p>
            <p class="card-text"><strong>Piezīmes:</strong> {{ $kustiba->piezimes ?? '-' }}</p>
            <p class="card-text"><strong>Dokuments:</strong> {{ $kustiba->dokuments ?? '-' }}</p>
        </div>
    </div>
@endsection