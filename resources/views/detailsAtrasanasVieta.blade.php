@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
<div class="auth-links">
    <a href="/atrasanas_vieta" >Atpakaļ uz Tabulu</a>
</div>

<hr>

<h2 style="color: #ffffff;">Detalizēta informācija</h2>          
             <div style="background: #490700; color: white; width: 200px;" class="card mt-3">
        <div class="card-body">
            <h5  class="card-title">Vieta: {{$vieta->nodala}}</h5>
            <p class="card-text">Nodaļa: {{$vieta->nodala }}</p>
            <p class="card-text">Telpa: {{ $vieta->telpa->nosaukums ?? ('ID: '.$vieta->telpas_id) }}</p>
            <p class="card-text">Stāvoklis: {{$vieta->stavs }}</p>
        </div>
    </div>

@endsection