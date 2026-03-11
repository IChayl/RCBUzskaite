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
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nosaukums</th>
                        <th>Izmēri</th>
                        <th>Numurs</th>
                        <th>Stāvs</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($telpas as $item)
                        <tr>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->izmeri ?? '-' }}</td>
                            <td>{{ $item->numurs ?? '-' }}</td>
                            <td>{{ $item->stavs ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->telpas_id }}">Dzēst</a>
                                    <a href="/telpa/{{ $item->telpas_id }}/details" class="bloom-button sm">Detalizēta</a>
                                    <a href="/telpa/{{ $item->telpas_id }}/edit" class="bloom-button sm">Rediģēt</a>
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
                    window.location.href = `/telpa/${id}/delete`;
                }
            });
        });
    });
</script>
