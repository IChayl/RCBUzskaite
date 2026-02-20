@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visas kategorijas</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/kategorija/create">Jauna kategorija</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Kategorijas</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
    @forelse ($kategorija as $item)
        <div style="background: #490700; color: white; width: 340px;" class="card mt-3">
            <div class="card-body">
          
                <p class="card-text">Nosaukums: {{$item->nosaukums}}</p>
                <p class="card-text">Apraksts: {{$item->apraksts ?? '-'}}</p>
                <div  class="auth-links">
                    <a href="#" class="delete-btn" data-id="{{ $item->kategorija_id }}">Dzēst</a>
                    <a href="/kategorija/{{ $item->kategorija_id }}/details">Detalizēta</a>
                    <a href="/kategorija/{{ $item->kategorija_id }}/edit">Rediģēt</a>
                </div>
            </div>
        </div>
    @empty
        <p>Nav kategoriju.</p>
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

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (confirm('Vai vēlaties dzēst šo ierakstu ?')) {
                    window.location.href = `/kategorija/${id}/delete`;
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
