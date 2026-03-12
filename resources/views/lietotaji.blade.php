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

    <div class="table-controls">
        <input class="table-search-input" type="text" placeholder="Meklēt...">
        <span class="no-results-message" style="display:none; color:#f88;">Nav rezultātu.</span>
    </div>

    @if($lietotaji->isEmpty())
        <p style="color: #ffffff;">Nav lietotāju.</p>
    @else
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="sortable">Lietotājvārds</th>
                        <th class="sortable">Admina tiesības</th>
                        <th>Avatar</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lietotaji as $item)
                        <tr>
                            <td>{{ $item->lietotajvards }}</td>
                            <td>{{ $item->admina_tiesibas ? 'Jā' : 'Nē' }}</td>
                            <td>
                                <div style="width: 36px; height: 36px; border-radius: 12px; overflow: hidden; background: rgba(255,255,255,0.06); display:flex; align-items:center; justify-content:center;">
                                    @if($item->avatar)
                                        <img src="{{ Storage::disk('public')->url($item->avatar) }}" alt="Avatar" style="width: 34px; height: 34px; object-fit: cover;">
                                    @else
                                        <span style="color: rgba(255,255,255,0.5); font-size: 18px;">👤</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->lietotajs_id }}">Dzēst</a>
                                    <a href="/lietotajs/{{ $item->lietotajs_id }}/details" class="bloom-button sm">Detalizēta</a>
                                    <a href="/lietotajs/{{ $item->lietotajs_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
