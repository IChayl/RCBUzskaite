@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi inventāri</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/inventars/create">Jauns inventārs</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Inventāri</h2>

    <div style="color: #ffffff; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($inventari->isEmpty())
        <p style="color: #ffffff;">Nav ierakstu.</p>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: white;">
                <thead>
                    <tr>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Nosaukums</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Apraksts</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Statuss</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Kategorija</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Telpa</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Atbildīgais</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventari as $item)
                        <tr style="background: rgba(73, 7, 0, 0.7);">
                            <td style="padding: 10px;">{{ $item->nosaukums }}</td>
                            <td style="padding: 10px;">{{ $item->apraksts ?? '-' }}</td>
                            <td style="padding: 10px;">{{ $item->statuss ?? '-' }}</td>
                            <td style="padding: 10px;">{{ $item->kategorija->nosaukums ?? ('ID: '.$item->kategorija_id) }}</td>
                            <td style="padding: 10px;">{{ optional($item->telpa)->nosaukums ?? ('ID: '.$item->telpas_id) }}</td>
                            <td style="padding: 10px;">{{ optional($item->atbildigais)->lietotajvards ?? ('ID: '.$item->atbildigais_id) }}</td>
                            <td style="padding: 10px;">
                                <a href="#" class="delete-btn" data-id="{{ $item->inventars_id }}" style="margin-right: 12px;">Dzēst</a>
                                <a href="/inventars/{{ $item->inventars_id }}/details" style="margin-right: 12px;">Detalizēta</a>
                                <a href="/inventars/{{ $item->inventars_id }}/edit">Rediģēt</a>
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