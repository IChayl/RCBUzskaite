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

    <div class="table-controls">
        <input class="table-search-input" type="text" placeholder="Meklēt...">
        <span class="no-results-message" style="display:none; color:#f88;">Nav rezultātu.</span>
    </div>

    @if($veidi->isEmpty())
        <p style="color: #ffffff;">Nav ierakstu.</p>
    @else
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="sortable">Nosaukums</th>
                        <th class="sortable">Apraksts</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($veidi as $item)
                        <tr>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->apraksts ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->kustibas_veids_id }}">Dzēst</a>
                                    <a href="/kustibas_veidi/{{ $item->kustibas_veids_id }}/details" class="bloom-button sm">Detalizēta</a>
                                    <a href="/kustibas_veidi/{{ $item->kustibas_veids_id }}/edit" class="bloom-button sm">Rediģēt</a>
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
                    window.location.href = `/kustibas_veidi/${id}/delete`;
                }
            });
        });
    });
</script>
