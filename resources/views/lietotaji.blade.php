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
            <div class="card mt-3 table-card" style="background: rgba(73, 7, 0, 0.55); color: #ffffff; width: 340px;">
                <div class="card-body">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; overflow: hidden; background: rgba(255,255,255,0.06); display:flex; align-items:center; justify-content:center;">
                            @if($item->avatar)
                                <img src="{{ asset('storage/' . $item->avatar) }}" alt="Avatar" style="width: 48px; height: 48px; object-fit: cover;">
                            @else
                                <span style="color: rgba(255,255,255,0.5); font-size: 20px;">👤</span>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <div class="card-text" style="font-weight: 700; color: #f4f4f9;">{{ $item->lietotajvards }}</div>
                            <div class="card-text" style="color: #d0d6ff; font-size: 0.9rem;">Admina tiesības: <span style="font-weight: 600;">{{ $item->admina_tiesibas ? 'Jā' : 'Nē' }}</span></div>
                        </div>
                    </div>
                    <div class="auth-links" style="margin-top: 12px;">
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
