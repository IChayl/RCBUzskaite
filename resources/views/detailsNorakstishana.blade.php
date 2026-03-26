@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Norakstīšanas detaļas</p>
    <div class="auth-links">
        <a href="/norakstishana">Atpakaļ uz norakstīšanām</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/norakstishana/{{ $norakstishana->norakstishana_id }}/edit" class="bloom-button sm">Rediģēt</a>
        @endif
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Norakstīšanas detaļas</h2>
    <!-- Norakstīšanas pilnā informācija kartītes skatā -->
    <div class="card mt-3" style="background: rgba(45, 65, 89, 0.65); border: 1px solid rgba(226, 212, 187, 0.2); color: #E2D4BB; max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">Norakstīšana #{{ $norakstishana->norakstishana_id }}</h5>
            <p class="card-text"><strong>Inventars:</strong> {{ optional($norakstishana->inventars)->nosaukums ?? ('ID: '.$norakstishana->inventara_id) }}</p>
            <p class="card-text"><strong>Inv. numurs:</strong> {{ optional($norakstishana->inventars)->inventara_numurs ?? '-' }}</p>
            <p class="card-text"><strong>Pieteica:</strong> {{ optional($norakstishana->pieteicejs)->pilnais_vards ?? optional($norakstishana->pieteicejs)->lietotajvards ?? '-' }}</p>
            <p class="card-text"><strong>Datums:</strong> {{ $norakstishana->norDatums->toDateString() }}</p>
            <p class="card-text"><strong>Pieteikuma datums:</strong> {{ $norakstishana->pieteikshanas_dat?->toDateString() ?? $norakstishana->pieteikshanas_dat ?? '-' }}</p>
            <p class="card-text"><strong>Apstiprināšanas datums:</strong> {{ $norakstishana->akceptets ? ($norakstishana->apstiprinashanas_dat?->toDateString() ?? $norakstishana->apstiprinashanas_dat ?? '-') : '-' }}</p>
            <p class="card-text"><strong>Akceptēts:</strong> {{ $norakstishana->akceptets ? 'Jā' : 'Nē' }}</p>
            <p class="card-text"><strong>Iemesls:</strong> {{ $norakstishana->iemesls }}</p>
            <p class="card-text"><strong>Tālākā rīcība:</strong> {{ $norakstishana->talaka_riciba }}</p>
        </div>
    </div>
@endsection
