@extends ('layout.app')

<body class="navy-maroon-bg">
@section('content')

<style>
.navy-maroon {
    background: #0F1931; /* navy -> maroon */
    color: #E2D4BB;
    padding: 1rem;
    border-radius: 6px;
}
.navy-maroon p { margin: 0; font-size: 1.05rem; }
.sidemenu {
    background: #0F1931;
    color: #E2D4BB;
    padding: 0.75rem;
    border-radius: 6px;
}
.sidemenu a {
    color: #E2D4BB;
    text-decoration: none;
    display: block;
    padding: 0.25rem 0;
}
.sidemenu a:hover { color: #2D4159; }
</style>

<div class="navy-maroon">
    <p>Rēzeknes Centrālās bibliotēkas inventāra uzskaites sistēma – pārskatāma, droša un efektīva bibliotēkas resursu pārvaldība.</p>
</div>

<div class="table-controls" style="margin-top: 18px; margin-bottom: 0;">
    <a class="bloom-button" href="{{ url('/inventars?inventory_status=in_use') }}">Inventārs: Lietošanā</a>
    <a class="bloom-button" href="{{ url('/inventars?inventory_status=in_repair') }}">Inventārs: Remontā</a>
    <a class="bloom-button" href="{{ url('/inventars?inventory_status=written_off') }}">Inventārs: Norakstīts</a>
    <a class="bloom-button" href="{{ url('/inventara_kustiba') }}">Kustības</a>
    <a class="bloom-button" href="{{ url('/norakstishana') }}">Norakstīšana</a>
    <a class="bloom-button" href="{{ url('/lietotajs') }}">Darbinieki</a>
    <a class="bloom-button" href="{{ url('/telpa') }}">Saraksti: Telpu saraksts</a>
    <a class="bloom-button" href="{{ url('/kategorija') }}">Saraksti: Kategoriju saraksts</a>
</div>

@endsection


</body>