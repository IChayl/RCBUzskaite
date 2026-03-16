@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visas telpas</p>

 <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/lietotajs/create">Jauns lietotājs</a>
        @endif
        <a type="button" class="auth-links" onclick="window.print()">Printēt</a>
    </div>

    <hr>

    <div style="color: #E2D4BB; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0F1931; color: #2D4159; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #2D4159; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #E2D4BB;">Telpas</h2>

    <!-- Telpu meklēšanas un kārtošanas vadīklas -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
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
        <span class="no-results-message" style="display:none; color:#2D4159;">Nav rezultātu.</span>
    </form>

    @if($telpas->isEmpty())
        <p style="color: #E2D4BB;">Nav telpu.</p>
    @else
        <!-- Telpu tabula ar kārtojamām kolonnām -->
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
                    <!-- Attēlo katru telpu kā tabulas rindu -->
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
        // Flash paziņojumu aizver ar klikšķi.
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.addEventListener('click', function() {
                this.remove();
            });
        }

        // Pirms dzēšanas lūdzam lietotājam apstiprināt darbību.
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
