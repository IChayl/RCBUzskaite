@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
<div class="auth-links">
    <a href="/telpa" >Atpakaļ uz Tabulu</a>
</div>

<hr>

<h2 style="color: #ffffff;">Detalizēta informācija</h2>          
             <div style="background: #490700; color: white; width: 200px;" class="card mt-3">
        <div class="card-body">
            <h5  class="card-title">Telpa: {{$telpa->nosaukums}}</h5>
            <p class="card-text">Nosaukums: {{$telpa->nosaukums }}</p>
            <p class="card-text">Izmēri: {{$telpa->izmeri }}</p>
            <p class="card-text">Numurs: {{$telpa->numurs }}</p>
        </div>
    </div>

@endsection
