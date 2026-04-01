@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi dati</p>
    <div class="auth-links">
        <a href="/lietotajs">Atpakaļ uz darbiniekiem</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/lietotajs/{{ $lietotajs->lietotajs_id }}/edit" class="bloom-button sm">Rediģēt</a>
        @endif
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Darbinieka detaļas</h2>
    <!-- Darbinieka profila un piekļuves informācija -->
    <div class="card mt-3" style="background: rgba(45, 65, 89, 0.65); border: 1px solid rgba(226, 212, 187, 0.2); color: #E2D4BB; max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">{{ $lietotajs->pilnais_vards }} (ID: {{ $lietotajs->lietotajs_id }})</h5>
            <p class="card-text"><strong>Vārds:</strong> {{ $lietotajs->vards ?? '-' }}</p>
            <p class="card-text"><strong>Uzvārds:</strong> {{ $lietotajs->uzvards ?? '-' }}</p>
            <p class="card-text"><strong>E-pasts:</strong> {{ $lietotajs->epasts ?? '-' }}</p>
            <p class="card-text"><strong>Telefons:</strong> {{ $lietotajs->telefons ?? '-' }}</p>
            <p class="card-text"><strong>Amats:</strong> {{ $lietotajs->amats ?? '-' }}</p>
            <p class="card-text"><strong>Admina tiesības:</strong> {{ $lietotajs->admina_tiesibas ? 'Jā' : 'Nē' }}</p>
            <p class="card-text"><strong>Avatar:</strong> {{ $lietotajs->avatar ? 'Saglabāts' : 'Nav' }}</p>
        </div>
    </div>
@endsection