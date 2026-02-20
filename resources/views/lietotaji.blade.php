@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi lietotāji</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/lietotajs/create">Jauns lietotājs</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Lietotāji</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
    @forelse ($lietotaji as $item)
        <div style="background: #490700; color: white; width: 340px;" class="card mt-3">
            <div class="card-body">
                <p class="card-text">Vārds: {{$item->lietotajvards}}</p>
                <p class="card-text">Admina tiesības: {{$item->admina_tiesibas? 'Jā':'Nē'}}</p>
                <div  class="auth-links">
                    <a href="#" class="delete-btn" data-id="{{ $item->lietotajs_id }}">Dzēst</a>
                    <a href="/lietotajs/{{ $item->lietotajs_id }}/details">Detalizēta</a>
                    <a href="/lietotajs/{{ $item->lietotajs_id }}/edit">Rediģēt</a>
                </div>
            </div>
        </div>
    @empty
        <p>Nav lietotāju.</p>
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
                    window.location.href = `/lietotajs/${id}/delete`;
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