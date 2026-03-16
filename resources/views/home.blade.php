@extends ('layout.app')

<body class="navy-maroon-bg">
@section('content')

<style>
.navy-maroon {
    background: #0D203A; /* navy -> maroon */
    color: #FAF8F2;
    padding: 1rem;
    border-radius: 6px;
}
.navy-maroon p { margin: 0; font-size: 1.05rem; }
.sidemenu {
    background: #0D203A;
    color: #FAF8F2;
    padding: 0.75rem;
    border-radius: 6px;
}
.sidemenu a {
    color: #FAF8F2;
    text-decoration: none;
    display: block;
    padding: 0.25rem 0;
}
.sidemenu a:hover { color: #243B53; }
</style>

<div class="navy-maroon">
    <p>Rēzeknes Centrālās bibliotēkas inventāra uzskaites sistēma – pārskatāma, droša un efektīva bibliotēkas resursu pārvaldība.</p>
</div>

@endsection


</body>