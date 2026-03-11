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
            <table style="width: 100%; border-collapse: collapse; color: white;">
                <thead>
                    <tr>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Datums</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Inventārs</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Kustības veids</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Atbildīgais</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kustibas as $item)
                        <tr style="background: rgba(73, 7, 0, 0.7);">
                            <td style="padding: 10px;">{{ $item->datums }}</td>
                            <td style="padding: 10px;">{{ $item->inventars->nosaukums ?? ('ID: '.$item->inventars_id) }}</td>
                            <td style="padding: 10px;">{{ optional($item->kustibasVeids)->nosaukums ?? ('ID: '.$item->kustibas_veids_id) }}</td>
                            <td style="padding: 10px;">{{ optional($item->lietotajs)->lietotajvards ?? ('ID: '.$item->atbildigais_lietotajs_id) }}</td>
                            <td style="padding: 10px;">
                                <a href="#" class="delete-btn" data-id="{{ $item->kustiba_id }}" style="margin-right: 12px;">Dzēst</a>
                                <a href="/inventara_kustiba/{{ $item->kustiba_id }}/details" style="margin-right: 12px;">Detalizēta</a>
                                <a href="/inventara_kustiba/{{ $item->kustiba_id }}/edit">Rediģēt</a>
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