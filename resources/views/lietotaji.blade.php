@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi darbinieki</p>

   <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/lietotajs/create">Jauns darbinieks</a>
        @endif
        <a type="button" class="auth-links" onclick="window.print()" title="Printēt dokumentu"><i class="fas fa-print"></i> Printēt</a>
    </div>

    <hr>

    <div style="color: #E2D4BB; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0F1931; color: #E2D4BB; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #2D4159; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #E2D4BB;">Darbinieki</h2>

    @if($lietotaji->isEmpty())
        <p style="color: #E2D4BB;">Nav lietotāju.</p>
    @else
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Lietotājvārds</th>
                        <th>Vārds</th>
                        <th>Uzvārds</th>
                        <th>E-pasts</th>
                        <th>Telefons</th>
                        <th>Amats</th>
                        <th>Admins</th>
                        <th>Aktīvs</th>
                        @if(Auth::user()->admina_tiesibas)
                            <th>Darbības</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lietotaji as $item)
                        <tr>
                            <td>
                                @if($item->hasAvatarFile())
                                    <img src="{{ route('lietotajs.avatar', $item->lietotajs_id) }}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->lietotajvards }}</td>
                            <td>{{ $item->vards ?? '-' }}</td>
                            <td>{{ $item->uzvards ?? '-' }}</td>
                            <td>{{ $item->epasts ?? '-' }}</td>
                            <td>{{ $item->telefons ?? '-' }}</td>
                            <td>{{ $item->amats ?? '-' }}</td>
                            <td>{{ $item->admina_tiesibas ? 'Jā' : 'Nē' }}</td>
                            <td>{{ $item->aktivs ? 'Jā' : 'Nē' }}</td>
                            @if(Auth::user()->admina_tiesibas)
                                <td>
                                    <div class="actions">
                                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->lietotajs_id }}">Dzēst</a>
                                        <a href="/lietotajs/{{ $item->lietotajs_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flash ziņojums pazūd pēc klikšķa.
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.addEventListener('click', function() {
                this.remove();
            });
        }

        // Dzēšanas darbībai pieprasa apstiprinājumu.
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
