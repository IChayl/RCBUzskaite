@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
<div class="auth-links">
    <a href="/lietotajs" >Atpakaļ uz Tabulu</a>
</div>

<hr>

<h2 style="color: #ffffff;">Detalizēta informācija</h2>          
             <div style="background: #490700; color: white; width: 200px;" class="card mt-3">
        <div class="card-body">
            <h5  class="card-title">Lietotājs: {{$lietotajs->lietotajvards}}</h5>
            <p class="card-text">Lietotājvārds: {{$lietotajs->lietotajvards }}</p>
            <p class="card-text">Parole: {{$lietotajs->parole }}</p>
            <p class="card-text">Admina tiesības: {{$lietotajs->admina_tiesibas ? 'Jā' : 'Nē' }}</p>
        </div>
    </div>

@endsection