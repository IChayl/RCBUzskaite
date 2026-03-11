@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visas kategorijas</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/kategorija/create">Jauna kategorija</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Kategorijas</h2>

    <div style="color: #ffffff; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($kategorija->isEmpty())
        <p style="color: #ffffff;">Nav kategoriju.</p>
    @else
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nosaukums</th>
                        <th>Apraksts</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategorija as $item)
                        <tr>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->apraksts ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->kategorija_id }}">Dzēst</a>
                                    <a href="/kategorija/{{ $item->kategorija_id }}/details" class="bloom-button sm">Detalizēta</a>
                                    <a href="/kategorija/{{ $item->kategorija_id }}/edit" class="bloom-button sm">Rediģēt</a>
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

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (confirm('Vai vēlaties dzēst šo ierakstu ?')) {
                    window.location.href = `/kategorija/${id}/delete`;
                }
            });
        });
    });
</script>
