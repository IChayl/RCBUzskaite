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
            <p class="card-text">Kategorijas ID: {{$inventar->kategorija_id }}</p>
            <p class="card-text">Atrašanās vietas ID: {{$inventar->atrasanas_vieta_id }}</p>
        </div>
    </div>

@endsection