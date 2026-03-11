@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi lietotāji</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/lietotajs/create">Jauns lietotājs</a>
    </div>

    <hr>

    <div style="color: #ffffff; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #ffffff;">Lietotāji</h2>

    @if($lietotaji->isEmpty())
        <p style="color: #ffffff;">Nav lietotāju.</p>
    @else
        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
        @foreach ($lietotaji as $item)
            <div class="card table-card">
                <div class="card-body" style="display: grid; grid-template-columns: 1.6fr 1fr 1.5fr; gap: 12px; align-items: center;">
                    <div>
                        <div class="card-text" style="font-weight:600;">{{ $item->lietotajvards }}</div>
                        <div class="card-text" style="opacity: 0.75; font-size: 0.9rem;">Vārds</div>
                    </div>

                    <div>
                        <div class="card-text" style="font-weight:600;">{{ $item->admina_tiesibas ? 'Jā' : 'Nē' }}</div>
                        <div class="card-text" style="opacity: 0.75; font-size: 0.9rem;">Admina tiesības</div>
                    </div>

                    <div class="actions" style="justify-content: flex-end;">
                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->lietotajs_id }}">Dzēst</a>
                        <a href="/lietotajs/{{ $item->lietotajs_id }}/details" class="bloom-button sm">Detalizēta</a>
                        <a href="/lietotajs/{{ $item->lietotajs_id }}/edit" class="bloom-button sm">Rediģēt</a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @endif

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.addEventListener('click', function() {
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
