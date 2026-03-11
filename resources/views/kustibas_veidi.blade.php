@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visi kustību veidi</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/kustibas_veidi/create">Jauns kustības veids</a>
    </div>

    <hr>

    <div style="color: #ffffff; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #ffffff;">Kustību veidi</h2>

    @if($veidi->isEmpty())
        <p style="color: #ffffff;">Nav ierakstu.</p>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: white;">
                <thead>
                    <tr>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Nosaukums</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Apraksts</th>
                        <th style="border-bottom: 2px solid #90EE90; padding: 8px; text-align: left;">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($veidi as $item)
                        <tr style="background: rgba(73, 7, 0, 0.7);">
                            <td style="padding: 10px;">{{ $item->nosaukums }}</td>
                            <td style="padding: 10px;">{{ $item->apraksts ?? '-' }}</td>
                            <td style="padding: 10px;">
                                <a href="#" class="delete-btn" data-id="{{ $item->kustibas_veids_id }}" style="margin-right: 12px;">Dzēst</a>
                                <a href="/kustibas_veidi/{{ $item->kustibas_veids_id }}/details" style="margin-right: 12px;">Detalizēta</a>
                                <a href="/kustibas_veidi/{{ $item->kustibas_veids_id }}/edit">Rediģēt</a>
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
                    window.location.href = `/kustibas_veidi/${id}/delete`;
                }
            });
        });
    });
</script>
