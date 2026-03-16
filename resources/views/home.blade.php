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

@endsection


</body>