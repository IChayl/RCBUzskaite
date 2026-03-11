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
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nosaukums</th>
                        <th>Apraksts</th>
                        <th>Statuss</th>
                        <th>Kategorija</th>
                        <th>Telpa</th>
                        <th>Atbildīgais</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventari as $item)
                        <tr>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->apraksts ?? '-' }}</td>
                            <td>{{ $item->statuss ?? '-' }}</td>
                            <td>{{ $item->kategorija->nosaukums ?? ('ID: '.$item->kategorija_id) }}</td>
                            <td>{{ optional($item->telpa)->nosaukums ?? ('ID: '.$item->telpas_id) }}</td>
                            <td>{{ optional($item->atbildigais)->lietotajvards ?? ('ID: '.$item->atbildigais_id) }}</td>
                            <td>
                                <div class="actions">
                                    <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->inventars_id }}">Dzēst</a>
                                    <a href="/inventars/{{ $item->inventars_id }}/details" class="bloom-button sm">Detalizēta</a>
                                    <a href="/inventars/{{ $item->inventars_id }}/edit" class="bloom-button sm">Rediģēt</a>
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
                    window.location.href = `/inventars/${id}/delete`;
                }
            });
        });
    });
</script>
