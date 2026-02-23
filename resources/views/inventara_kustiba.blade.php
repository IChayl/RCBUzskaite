@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visas inventāra kustības</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/inventara_kustiba/create">Jauna kustība</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Inventāra kustība</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
    @forelse ($kustibas as $item)
        <div style="background: #490700; color: white; width: 340px;" class="card mt-3">
            <div class="card-body">
                <p class="card-text">Datums: {{$item->datums}}</p>
                <p class="card-text">Inventārs: {{ $item->inventars->nosaukums ?? ('ID: '.$item->inventars_id) }}</p>
                <p class="card-text">No vietas: {{ $item->noVieta->nodala ?? ('ID: '.$item->no_atrasanas_vietas_id) }}</p>
                <p class="card-text">Uz vietu: {{ $item->uzVieta->nodala ?? ('ID: '.$item->uz_atrasanas_vietas_id) }}</p>
                <p class="card-text">Atbildīgais: {{ $item->lietotajs->lietotajvards ?? ('ID: '.$item->atbildigais_lietotajs_id) }}</p>
                <div  class="auth-links">
                    <a href="#" class="delete-btn" data-id="{{ $item->kustiba_id }}">Dzēst</a>
                    <a href="/inventara_kustiba/{{ $item->kustiba_id }}/details">Detalizēta</a>
                    <a href="/inventara_kustiba/{{ $item->kustiba_id }}/edit">Rediģēt</a>
                </div>
            </div>
        </div>
    @empty
        <p>Nav ierakstu.</p>
    @endforelse
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('div[style*="background: #490700"]');
        if (alert) {
            alert.style.cursor = 'pointer';
            alert.addEventListener('click', function() {
                this.remove();
            });
        }

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (confirm('Vai vēlaties dzēst šo ierakstu ?')) {
                    window.location.href = `/inventara_kustiba/${id}/delete`;
                }
            });
        });
    });
</script>
<div style="color: #ffffff; margin-top: 20px;">
    @if(session('success'))
        <div style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90;">
            {{ session('success') }}
        </div>
    @endif
</div>