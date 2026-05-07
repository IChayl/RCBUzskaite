@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visas inventāra kustības</p>
    
 <div class="auth-links">
        <a href="/inventara_kustiba/create" class="bloom-button sm icon-button" title="Jauna inventāra kustība" aria-label="Jauna inventāra kustība"><i class="fas fa-plus" aria-hidden="true"></i><span class="sr-only">Jauna inventāra kustība</span></a>
        <a type="button" class="auth-links" onclick="window.print()" title="Printēt dokumentu"><i class="fas fa-print"></i> Printēt</a>
    </div>
    <hr>
    <h2 style="color: #E2D4BB;">Inventāra kustība</h2>

    <!-- Meklēšana kustību sarakstam -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="inventars" {{ request('column') === 'inventars' ? 'selected' : '' }}>Nosaukums</option>
                <option style="color:#0F1931;" value="inventara_numurs" {{ request('column') === 'inventara_numurs' ? 'selected' : '' }}>InvNumurs</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
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
        <span style="color:#E2D4BB;">Datums no</span>
        <input type="date" name="datums_no" value="{{ request('datums_no') }}" class="table-search-input" style="max-width: 170px; min-width:170px;" title="Datums no">
        <span style="color:#E2D4BB;">līdz</span>
        <input type="date" name="datums_lidz" value="{{ request('datums_lidz') }}" class="table-search-input" style="max-width: 170px; min-width:170px;" title="Datums līdz">
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
        <div class="table-wrap table-wrap--fit">
            <table class="data-table movement-table" data-print-group-column="3" data-print-group-label="Kustības veids">
                <thead>
                    <tr>
                        <th class="sortable {{ request('sort') === 'inventara_numurs' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'inventara_numurs' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'inventara_numurs', 'direction' => $dir]) }}">InvNumurs</a>
                        </th>
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
                        <th>Jaunais atbildīgais</th>
                        <th>Statuss</th>
                          @if(Auth::user()->admina_tiesibas) <th>Darbības</th> @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Attēlo katru kustību kā atsevišķu tabulas rindu -->
                    @foreach ($kustibas as $item)
                        <tr>
                            <td>{{ optional($item->inventars)->inventara_numurs ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->datums)->locale('lv')->translatedFormat('j. F Y') }}</td>
                            <td>{{ $item->inventars->nosaukums ?? ('ID: '.$item->inventars_id) }}</td>
                            <td>{{ optional($item->kustibasVeids)->nosaukums ?? ('ID: '.$item->kustibas_veids_id) }}</td>
                            <td>{{ optional($item->vecaTelpa)->nosaukums ?? 'Inventārs netika pārvietots' }}</td>
                            <td>{{ optional($item->jaunaTelpa)->nosaukums ?? 'Inventārs netika pārvietots' }}</td>
                            <td>{{ optional($item->lietotajs)->pilnais_vards ?? ('ID: '.$item->atbildigais_lietotajs_id) }}</td>
                            <td>{{ $item->Jatbildigais_lietotajs_id && $item->Jatbildigais_lietotajs_id != 0 ? (optional($item->jaunaisAtbildigais)->pilnais_vards ?? ('ID: '.$item->Jatbildigais_lietotajs_id)) : 'Atbildīgais netika mainīts' }}</td>
                            <td>{{ $item->apstiprinats ? 'Apstiprināts' : 'Gaida apstiprinājumu' }}</td>
                                 @if(Auth::user()->admina_tiesibas)  <td>
                                <div class="actions">
                                        <a href="#" class="bloom-button sm icon-button delete-btn" data-id="{{ $item->kustiba_id }}" title="Dzēst" aria-label="Dzēst"><i class="fas fa-trash" aria-hidden="true"></i><span class="sr-only">Dzēst</span></a>
                                        <a href="/inventara_kustiba/{{ $item->kustiba_id }}/edit" class="bloom-button sm icon-button" title="Rediģēt" aria-label="Rediģēt"><i class="fas fa-edit" aria-hidden="true"></i><span class="sr-only">Rediģēt</span></a>
                                        @if(!$item->apstiprinats)
                                        <a href="/inventara_kustiba/{{ $item->kustiba_id }}/approve" class="bloom-button sm icon-button" title="Apstiprināt" aria-label="Apstiprināt"><i class="fas fa-check" aria-hidden="true"></i><span class="sr-only">Apstiprināt</span></a>
                                        @endif
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
                // Izmantojam kopējo projekta apstiprinājuma modālo logu.
                window.appConfirm('Vai vēlaties dzēst šo ierakstu?', {
                    title: 'Dzēšanas apstiprinājums',
                    acceptText: 'Dzēst',
                    cancelText: 'Atcelt'
                }).then((accepted) => {
                    if (!accepted) {
                        // Ja apstiprinājums netiek dots, darbību pārtraucam.
                        return;
                    }
                    // Tikai pēc apstiprinājuma izsaucam dzēšanas maršrutu.
                    window.location.href = `/inventara_kustiba/${id}/delete`;
                });
            });
        });
    });
</script>
