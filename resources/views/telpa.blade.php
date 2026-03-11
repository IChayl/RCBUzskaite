@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visas telpas</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/telpa/create">Jauna telpa</a>
    </div>

    <hr>

    <div style="color: #ffffff; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #ffffff;">Telpas</h2>

    @if($telpas->isEmpty())
        <p style="color: #ffffff;">Nav telpu.</p>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: white;">
                <thead>
                    <tr>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Nosaukums</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Izmēri</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Numurs</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Stāvs</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($telpas as $item)
                        <tr style="background: rgba(73, 7, 0, 0.7);">
                            <td style="padding: 10px;">{{ $item->nosaukums }}</td>
                            <td style="padding: 10px;">{{ $item->izmeri ?? '-' }}</td>
                            <td style="padding: 10px;">{{ $item->numurs ?? '-' }}</td>
                            <td style="padding: 10px;">{{ $item->stavs ?? '-' }}</td>
                            <td style="padding: 10px;">
                                <a href="#" class="delete-btn" data-id="{{ $item->telpas_id }}" style="margin-right: 12px;">Dzēst</a>
                                <a href="/telpa/{{ $item->telpas_id }}/details" style="margin-right: 12px;">Detalizēta</a>
                                <a href="/telpa/{{ $item->telpas_id }}/edit">Rediģēt</a>
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
                    window.location.href = `/telpa/${id}/delete`;
                }
            });
        });
    });
</script>
