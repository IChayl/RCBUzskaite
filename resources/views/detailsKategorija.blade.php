 @extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi dati</p>
<div class="auth-links">
    <a href="/kategorija" >Atpakaļ uz Tabulu</a>
</div>

<hr>

<h2 style="color: #ffffff;">Detalizēta informācija</h2>          
             <div style="background: #490700; color: white; width: 200px;" class="card mt-3">
        <div class="card-body">
            <h5  class="card-title">ID: {{$kategorija->kategorija_id}}</h5>
            <p class="card-text">Nosaukums: {{$kategorija->nosaukums }}</p>
            <p class="card-text">Apraksts: {{$kategorija->apraksts }}</p>

           
        </div>
    </div>

@endsection