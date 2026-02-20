@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi inventāri</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/inventars/create">Jauns inventārs</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Inventāri</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
    @forelse ($inventari as $item)
        <div style="background: #490700; color: white; width: 340px;" class="card mt-3">
            <div class="card-body">
                <p class="card-text">Nosaukums: {{$item->nosaukums}}</p>
                <p class="card-text">Apraksts: {{$item->apraksts ?? '-'}}</p>
                <p class="card-text">Nolietojums: {{$item->nolietojums ?? '-'}}</p>
                <p class="card-text">Statuss: {{$item->statuss ?? '-'}}</p>
                <p class="card-text">Kategorija ID: {{$item->kategorija_id}}</p>
                <p class="card-text">Atrašanās vieta ID: {{$item->atrasanas_vieta_id}}</p>
                <div  class="auth-links">
                    <a href="#" class="delete-btn" data-id="{{ $item->inventars_id }}">Dzēst</a>
                    <a href="/inventars/{{ $item->inventars_id }}/details">Detalizēta</a>
                    <a href="/inventars/{{ $item->inventars_id }}/edit">Rediģēt</a>
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
                    window.location.href = `/inventars/${id}/delete`;
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