@extends('layout.app')

@section('content')
    <p style="color: #FAF8F2;">Visas telpas</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        @if(Auth::user()->admina_tiesibas)
            <a href="/telpa/create">Jauna telpa</a>
        @endif
        <button type="button" class="bloom-button sm" onclick="window.print()">Printēt</button>
    </div>

    <hr>

    <div style="color: #FAF8F2; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0D203A; color: #243B53; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #243B53; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #FAF8F2;">Telpas</h2>

    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#FAF8F2; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(209, 195, 165, 0.2); background:rgba(209, 195, 165, 0.06); color:#FAF8F2;">
                <option value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option value="nosaukums" {{ request('column') === 'nosaukums' ? 'selected' : '' }}>Nosaukums</option>
                <option value="izmeri" {{ request('column') === 'izmeri' ? 'selected' : '' }}>Izmēri</option>
                <option value="numurs" {{ request('column') === 'numurs' ? 'selected' : '' }}>Numurs</option>
                <option value="stavs" {{ request('column') === 'stavs' ? 'selected' : '' }}>Stāvs</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
        <a href="{{ url('/telpa') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#243B53;">Nav rezultātu.</span>
    </form>

    @if($telpas->isEmpty())
        <p style="color: #FAF8F2;">Nav telpu.</p>
    @else
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="sortable {{ request('sort') === 'nosaukums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'nosaukums' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nosaukums', 'direction' => $dir]) }}">Nosaukums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'izmeri' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'izmeri' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'izmeri', 'direction' => $dir]) }}">Izmēri</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'numurs' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'numurs' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'numurs', 'direction' => $dir]) }}">Numurs</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'stavs' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'stavs' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stavs', 'direction' => $dir]) }}">Stāvs</a>
                        </th>
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
                                    @if(Auth::user()->admina_tiesibas)
                                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->telpas_id }}">Dzēst</a>
                                        <a href="/telpa/{{ $item->telpas_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                    @endif
                                    <a href="/telpa/{{ $item->telpas_id }}/details" class="bloom-button sm">Detalizēta</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $telpas->links() }}
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
