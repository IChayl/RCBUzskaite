@extends ('layout.app')

<body class="navy-maroon-bg">
@section('content')

<style>
.navy-maroon {
    background: #490700; /* navy -> maroon */
    color: #FFFFFF;
    padding: 1rem;
    border-radius: 6px;
}
.navy-maroon p { margin: 0; font-size: 1.05rem; }
.sidemenu {
    background: #490700;
    color: #FFFFFF;
    padding: 0.75rem;
    border-radius: 6px;
}
.sidemenu a {
    color: #FFFFFF;
    text-decoration: none;
    display: block;
    padding: 0.25rem 0;
}
.sidemenu a:hover { color: #fff; }
</style>

<div class="navy-maroon">
    <p>Rēzeknes Centrālās bibliotēkas inventāra uzskaites sistēma – pārskatāma, droša un efektīva bibliotēkas resursu pārvaldība.</p>
</div>

@endsection


</body>