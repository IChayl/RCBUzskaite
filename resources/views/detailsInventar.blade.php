@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
<div class="auth-links">
    <a href="/inventars" >Atpakaļ uz Tabulu</a>
</div>

<hr>

<h2 style="color: #ffffff;">Detalizēta informācija</h2>          
             <div style="background: #490700; color: white; width: 200px;" class="card mt-3">
        <div class="card-body">
            <h5  class="card-title">ID: {{$inventar->inventars_id}}</h5>
            <p class="card-text">Nosaukums: {{$inventar->nosaukums }}</p>
            <p class="card-text">Apraksts: {{$inventar->apraksts }}</p>
            <p class="card-text">Nolietojums: {{$inventar->nolietojums }}</p>
            <p class="card-text">Statuss: {{$inventar->statuss }}</p>
            <p class="card-text">Kategorija: {{ $inventar->kategorija->nosaukums ?? ('ID: '.$inventar->kategorija_id) }}</p>
            <p class="card-text">Telpa: {{ optional($inventar->telpa)->nosaukums ?? ('ID: '.$inventar->telpas_id) }}</p>
            <p class="card-text">Atbildīgais: {{ optional($inventar->atbildigais)->lietotajvards ?? ('ID: '.$inventar->atbildigais_id) }}</p>
        </div>
    </div>

@endsection