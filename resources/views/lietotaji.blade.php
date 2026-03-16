@extends('layout.app')

@section('content')
    <p style="color: #FAF8F2;">Visi lietotāji</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/lietotajs/create">Jauns lietotājs</a>
        @endif
        <button type="button" class="bloom-button sm" onclick="window.print()">Printēt</button>
    </div>

    <hr>

    <div style="color: #FAF8F2; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0D203A; color: #243B53; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #243B53; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #FAF8F2;">Lietotāji</h2>

    @if($lietotaji->isEmpty())
        <p style="color: #FAF8F2;">Nav lietotāju.</p>
    @else
        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
        @foreach ($lietotaji as $item)
            <div class="card mt-3 table-card" style="background: rgba(36, 59, 83, 0.55); color: #FAF8F2; width: 100%; max-width: 340px;">
                <div class="card-body">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; overflow: hidden; background: rgba(209, 195, 165, 0.06); display:flex; align-items:center; justify-content:center;">
                            @if($item->avatar)
                                <img src="{{ Storage::disk('public')->url($item->avatar) }}" alt="Avatar" style="width: 48px; height: 48px; object-fit: cover;">
                            @else
                                <span style="color: rgba(209, 195, 165, 0.5); font-size: 20px;">👤</span>
                            @endif
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div class="card-text" style="font-weight: 700; color: #FAF8F2;">{{ $item->lietotajvards }}</div>
                            <div class="card-text" style="color: #243B53; font-size: 0.9rem;">Vārds: {{ $item->vards ?? '-' }}</div>
                            <div class="card-text" style="color: #243B53; font-size: 0.9rem;">Uzvārds: {{ $item->uzvards ?? '-' }}</div>
                            <div class="card-text" style="color: #243B53; font-size: 0.9rem;">E-pasts: {{ $item->epasts ?? '-' }}</div>
                            <div class="card-text" style="color: #243B53; font-size: 0.9rem;">Telefons: {{ $item->telefons ?? '-' }}</div>
                            <div class="card-text" style="color: #243B53; font-size: 0.9rem;">Amats: {{ $item->amats ?? '-' }}</div>
                            <div class="card-text" style="color: #243B53; font-size: 0.9rem;">Admina tiesības: <span style="font-weight: 600;">{{ $item->admina_tiesibas ? 'Jā' : 'Nē' }}</span></div>
                            <div class="card-text" style="color: #243B53; font-size: 0.9rem;">Aktīvs: <span style="font-weight: 600;">{{ $item->aktivs ? 'Jā' : 'Nē' }}</span></div>
                        </div>
                    </div>
                    <div class="auth-links" style="margin-top: 12px;">
                        @if(Auth::user()->admina_tiesibas)
                            <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->lietotajs_id }}">Dzēst</a>
                            <a href="/lietotajs/{{ $item->lietotajs_id }}/edit" class="bloom-button sm">Rediģēt</a>
                        @endif
                        <a href="/lietotajs/{{ $item->lietotajs_id }}/details" class="bloom-button sm">Detalizēta</a>
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
