@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visas inventāra kustības</p>
    
 <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/inventara_kustiba/create">Jauna inventāra kustība</a>
        @endif
        <a type="button" class="auth-links" onclick="window.print()" title="Printēt dokumentu"><i class="fas fa-print"></i> Printēt</a>
    </div>
    <hr>
    <h2 style="color: #E2D4BB;">Inventāra kustība</h2>

    <!-- Meklēšana kustību sarakstam -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <input type="hidden" name="filter_veids" value="{{ request('filter_veids') }}">
        <input type="hidden" name="filter_atbildigais" value="{{ request('filter_atbildigais') }}">
        <input type="hidden" name="datums_no" value="{{ request('datums_no') }}">
        <input type="hidden" name="datums_lidz" value="{{ request('datums_lidz') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="inventars" {{ request('column') === 'inventars' ? 'selected' : '' }}>Nosaukums</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
    </form>

    <!-- Filtrēšana kustību sarakstam -->
    <form method="GET" class="table-controls" style="margin-top: 10px;">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <input type="hidden" name="column" value="{{ request('column', 'all') }}">
        <input type="hidden" name="q" value="{{ request('q') }}">
        <select name="filter_veids" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
            <option style="color:#0F1931;" value="">Kustības veids (visi)</option>
            @foreach($kustibasVeidiFiltram as $veids)
                <option style="color:#0F1931;" value="{{ $veids->kustibas_veids_id }}" {{ (string) request('filter_veids') === (string) $veids->kustibas_veids_id ? 'selected' : '' }}>{{ $veids->nosaukums }}</option>
            @endforeach
        </select>
        <select name="filter_atbildigais" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
            <option style="color:#0F1931;" value="">Atbildīgais (visi)</option>
            @foreach($lietotajiFiltram as $lietotajs)
                <option style="color:#0F1931;" value="{{ $lietotajs->lietotajs_id }}" {{ (string) request('filter_atbildigais') === (string) $lietotajs->lietotajs_id ? 'selected' : '' }}>{{ $lietotajs->pilnais_vards }}</option>
            @endforeach
        </select>
        datums no <input type="date" name="datums_no" value="{{ request('datums_no') }}" class="table-search-input" style="max-width: 170px;" title="Datums no">
        datums līdz <input type="date" name="datums_lidz" value="{{ request('datums_lidz') }}" class="table-search-input" style="max-width: 170px;" title="Datums līdz">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Filtrēt</button>
        <a href="{{ url('/inventara_kustiba') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#2D4159;">Nav rezultātu.</span>
    </form>

    <div style="color: #E2D4BB; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0F1931; color: #E2D4BB; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #2D4159; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($kustibas->isEmpty())
        <p style="color: #E2D4BB;">Nav ierakstu.</p>
    @else
        <!-- Kustību tabula ar dinamiskiem kārtošanas linkiem -->
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="sortable {{ request('sort') === 'datums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'datums' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'datums', 'direction' => $dir]) }}">Datums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'inventars' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'inventars' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'inventars', 'direction' => $dir]) }}">Nosaukums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'kustibas_veids' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'kustibas_veids' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'kustibas_veids', 'direction' => $dir]) }}">Kustības veids</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'veca_telpa' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'veca_telpa' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'veca_telpa', 'direction' => $dir]) }}">Vecā telpa</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'jauna_telpa' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'jauna_telpa' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'jauna_telpa', 'direction' => $dir]) }}">Jaunā telpa</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'lietotajs' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'lietotajs' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'lietotajs', 'direction' => $dir]) }}">Atbildīgais</a>
                        </th>
                          @if(Auth::user()->admina_tiesibas) <th>Darbības</th> @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Attēlo katru kustību kā atsevišķu tabulas rindu -->
                    @foreach ($kustibas as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->datums)->locale('lv')->translatedFormat('j. F Y') }}</td>
                            <td>{{ $item->inventars->nosaukums ?? ('ID: '.$item->inventars_id) }}</td>
                            <td>{{ optional($item->kustibasVeids)->nosaukums ?? ('ID: '.$item->kustibas_veids_id) }}</td>
                            <td>{{ optional($item->vecaTelpa)->nosaukums ?? ('ID: '.$item->veca_telpa_id) }}</td>
                            <td>{{ optional($item->jaunaTelpa)->nosaukums ?? ('ID: '.$item->jauna_telpa_id) }}</td>
                            <td>{{ optional($item->lietotajs)->pilnais_vards ?? ('ID: '.$item->atbildigais_lietotajs_id) }}</td>
                                 @if(Auth::user()->admina_tiesibas)  <td>
                                <div class="actions">
                             
                                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->kustiba_id }}">Dzēst</a>
                                        <a href="/inventara_kustiba/{{ $item->kustiba_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                   
                                </div>
                            </td> @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $kustibas->links() }}
        </div>
    @endif

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ātra veiksmīgā paziņojuma paslēpšana, uzklikšķinot uz tā.
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.addEventListener('click', function() {
                this.remove();
            });
        }

        // Dzēšanas poga pāradresē tikai pēc lietotāja apstiprinājuma.
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
