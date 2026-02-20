@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
<div class="auth-links">
    <a href="/inventara_kustiba" >Atpakaļ uz Tabulu</a>
</div>

<hr>

<h2 style="color: #ffffff;">Detalizēta informācija</h2>          
             <div style="background: #490700; color: white; width: 200px;" class="card mt-3">
        <div class="card-body">
            <h5  class="card-title">ID: {{$kustiba->kustiba_id}}</h5>
            <p class="card-text">Datums: {{$kustiba->datums }}</p>
            <p class="card-text">Inventārs ID: {{$kustiba->inventars_id }}</p>
            <p class="card-text">No vietas ID: {{$kustiba->no_atrasanas_vietas_id }}</p>
            <p class="card-text">Uz vietas ID: {{$kustiba->uz_atrasanas_vietas_id }}</p>
            <p class="card-text">Atbildīgais lietotājs ID: {{$kustiba->atbildigais_lietotajs_id }}</p>
        </div>
    </div>

@endsection