@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
    <div class="auth-links">
        <a href="/lietotajs">Atpakaļ uz lietotājiem</a>
        <a href="/lietotajs/{{ $lietotajs->lietotajs_id }}/edit" class="bloom-button sm">Rediģēt</a>
    </div>

    <hr>

    <h2 style="color: #ffffff;">Lietotāja detaļas</h2>
    <div class="card mt-3" style="background: rgba(73, 7, 0, 0.65); border: 1px solid rgba(255,255,255,0.2); color: #ffffff; max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">{{ $lietotajs->lietotajvards }} (ID: {{ $lietotajs->lietotajs_id }})</h5>
            <p class="card-text"><strong>Vārds:</strong> {{ $lietotajs->vards ?? '-' }}</p>
            <p class="card-text"><strong>Uzvārds:</strong> {{ $lietotajs->uzvards ?? '-' }}</p>
            <p class="card-text"><strong>E-pasts:</strong> {{ $lietotajs->epasts ?? '-' }}</p>
            <p class="card-text"><strong>Telefons:</strong> {{ $lietotajs->telefons ?? '-' }}</p>
            <p class="card-text"><strong>Amats:</strong> {{ $lietotajs->amats ?? '-' }}</p>
            <p class="card-text"><strong>Admina tiesības:</strong> {{ $lietotajs->admina_tiesibas ? 'Jā' : 'Nē' }}</p>
            <p class="card-text"><strong>Aktīvs:</strong> {{ $lietotajs->aktivs ? 'Jā' : 'Nē' }}</p>
            <p class="card-text"><strong>Avatar:</strong> {{ $lietotajs->avatar ? 'Saglabāts' : 'Nav' }}</p>
        </div>
    </div>
@endsection