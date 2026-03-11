@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visas inventāra kustības</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/inventara_kustiba/create">Jauna kustība</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Inventāra kustība</h2>

    <div style="color: #ffffff; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($kustibas->isEmpty())
        <p style="color: #ffffff;">Nav ierakstu.</p>
    @else
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Datums</th>
                        <th>Inventārs</th>
                        <th>Kustības veids</th>
                        <th>Atbildīgais</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kustibas as $item)
                        <tr>
                            <td>{{ $item->datums }}</td>
                            <td>{{ $item->inventars->nosaukums ?? ('ID: '.$item->inventars_id) }}</td>
                            <td>{{ optional($item->kustibasVeids)->nosaukums ?? ('ID: '.$item->kustibas_veids_id) }}</td>
                            <td>{{ optional($item->lietotajs)->lietotajvards ?? ('ID: '.$item->atbildigais_lietotajs_id) }}</td>
                            <td>
                                <div class="actions">
                                    <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->kustiba_id }}">Dzēst</a>
                                    <a href="/inventara_kustiba/{{ $item->kustiba_id }}/details" class="bloom-button sm">Detalizēta</a>
                                    <a href="/inventara_kustiba/{{ $item->kustiba_id }}/edit" class="bloom-button sm">Rediģēt</a>
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
                    window.location.href = `/inventara_kustiba/${id}/delete`;
                }
            });
        });
    });
</script>
