@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Viss inventārs</p>

    <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/inventars/create">Jauns inventārs</a>
        @endif
        <a type="button" class="auth-links" onclick="window.print()" title="Printēt dokumentu"><i class="fas fa-print"></i> Printēt</a>
    </div>

    <hr>
    <h2 style="color: #E2D4BB;">Inventārs</h2>



   
        @if(Auth::user()->admina_tiesibas)
            <a href="{{ request()->fullUrlWithQuery(['inventory_status' => 'all']) }}" class="bloom-button sm" style="{{ $inventoryStatus === 'all' ? 'box-shadow: 0 0 0 2px rgba(226, 212, 187, 0.45), 0 16px 40px rgba(15, 25, 49, 0.45);' : '' }}">Viss inventārs</a>
            <a href="{{ request()->fullUrlWithQuery(['inventory_status' => 'written_off']) }}" class="bloom-button sm" style="{{ $inventoryStatus === 'written_off' ? 'box-shadow: 0 0 0 2px rgba(226, 212, 187, 0.45), 0 16px 40px rgba(15, 25, 49, 0.45);' : '' }}">Tikai norakstītais</a>
            <a href="{{ request()->fullUrlWithQuery(['inventory_status' => 'active']) }}" class="bloom-button sm" style="{{ $inventoryStatus === 'active' ? 'box-shadow: 0 0 0 2px rgba(226, 212, 187, 0.45), 0 16px 40px rgba(15, 25, 49, 0.45);' : '' }}">Tikai nenorakstītais</a>
        @else

        @endif
    

    <form method="GET" class="table-controls inventory-toolbar">
        <input type="hidden" name="inventory_status" value="{{ $inventoryStatus }}">
        <input type="hidden" name="inventory_scope" value="{{ $inventoryScope }}">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:6px;">
            <span style="color:#E2D4BB; font-size:0.82rem;">Meklēt</span>
            <select name="column" class="inventory-search-scope" style="border-radius:999px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="nosaukums" {{ request('column') === 'nosaukums' ? 'selected' : '' }}>Nosaukums</option>
                <option style="color:#0F1931;" value="inventara_numurs" {{ request('column') === 'inventara_numurs' ? 'selected' : '' }}>Inv.Nr.</option>
            </select>
        </label>
        <input class="table-search-input inventory-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <select name="filter_kategorija" style="border-radius:999px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
            <option style="color:#0F1931;" value="">Kategorija</option>
            @foreach($kategorijas as $kategorija)
                <option style="color:#0F1931;" value="{{ $kategorija->kategorija_id }}" {{ (string) request('filter_kategorija') === (string) $kategorija->kategorija_id ? 'selected' : '' }}>{{ $kategorija->nosaukums }}</option>
            @endforeach
        </select>
        <select name="filter_telpa" style="border-radius:999px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
            <option style="color:#0F1931;" value="">Telpa</option>
            @foreach($telpas as $telpa)
                <option style="color:#0F1931;" value="{{ $telpa->telpas_id }}" {{ (string) request('filter_telpa') === (string) $telpa->telpas_id ? 'selected' : '' }}>{{ $telpa->nosaukums }}</option>
            @endforeach
        </select>
        <select name="filter_atbildigais" style="border-radius:999px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
            <option style="color:#0F1931;" value="">Atbild.</option>
            @foreach($lietotaji as $lietotajs)
                <option style="color:#0F1931;" value="{{ $lietotajs->lietotajs_id }}" {{ (string) request('filter_atbildigais') === (string) $lietotajs->lietotajs_id ? 'selected' : '' }}>{{ $lietotajs->pilnais_vards }}</option>
            @endforeach
        </select>
        <span class="inventory-date-label" style="color:#E2D4BB;">No</span>
        <input type="date" name="iegades_datums_no" value="{{ request('iegades_datums_no') }}" class="table-search-input" title="Iegādes datums no">
        <span class="inventory-date-label" style="color:#E2D4BB;">Līdz</span>
        <input type="date" name="iegades_datums_lidz" value="{{ request('iegades_datums_lidz') }}" class="table-search-input" title="Iegādes datums līdz">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Filtrēt</button>
        <a href="{{ url('/inventars') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#2D4159;">Nav rezultātu.</span>
    </form>

    <div style="color: #E2D4BB; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0F1931; color: #E2D4BB; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #2D4159; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($inventari->isEmpty())
        <p style="color: #E2D4BB;">Nav ierakstu.</p>
    @else
        <!-- Inventāra tabula ar servera puses kārtošanu pa kolonnām -->
        <div class="table-wrap">
            <table class="data-table" data-print-group-column="2" data-print-group-label="Kategorija">
                <thead>
                    <tr>
                        <th class="sortable {{ request('sort') === 'inventara_numurs' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php $dir = request('sort') === 'inventara_numurs' && request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'inventara_numurs', 'direction' => $dir]) }}">InvNumurs</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'nosaukums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php $dir = request('sort') === 'nosaukums' && request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nosaukums', 'direction' => $dir]) }}">Nosaukums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'kategorija' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php $dir = request('sort') === 'kategorija' && request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'kategorija', 'direction' => $dir]) }}">Kategorija</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'telpa' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php $dir = request('sort') === 'telpa' && request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'telpa', 'direction' => $dir]) }}">Telpa</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'atbildigais' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php $dir = request('sort') === 'atbildigais' && request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'atbildigais', 'direction' => $dir]) }}">Atbildīgais</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'iegades_datums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php $dir = request('sort') === 'iegades_datums' && request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'iegades_datums', 'direction' => $dir]) }}">Iegādes datums</a>
                        </th>
                        @if(Auth::user()->admina_tiesibas) <th>Darbības</th> @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Tabulas rinda katram inventāra ierakstam -->
                    @foreach ($inventari as $item)
                        <tr>
                            <td>{{ $item->inventara_numurs ?? '-' }}</td>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->kategorija->nosaukums ?? ('ID: '.$item->kategorija_id) }}</td>
                            <td>{{ optional($item->telpa)->nosaukums ?? ('ID: '.$item->telpas_id) }}</td>
                            <td>{{ optional($item->atbildigais)->pilnais_vards ?? ('ID: '.$item->atbildigais_id) }}</td>
                            <td>@lvDate($item->iegades_datums)</td>
                            @if(Auth::user()->admina_tiesibas)
                            <td>
                                <div class="actions actions--inventory">
                                    <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->inventars_id }}">Dzēst</a>
                                    <a href="/inventars/{{ $item->inventars_id }}/edit" class="bloom-button sm">Red.</a>
                                    <select class="form-control quick-action-select bloom-button sm" style="min-width: 170px; display: inline-block;" aria-label="Izvēlieties darbību">
                                        <option value="">Darbība</option>
                                        @foreach($kustibasVeidiQuickActions as $kv)
                                            @php($isNorakstisana = str_contains(mb_strtolower($kv->nosaukums, 'UTF-8'), 'norakst'))
                                            @if(! $isNorakstisana)
                                                <option value="{{ route('inventara_kustiba.create', ['inventars_id' => $item->inventars_id, 'kustibas_veids_id' => $kv->kustibas_veids_id]) }}">{{ $kv->nosaukums }}</option>
                                            @endif
                                        @endforeach
                                        <option value="{{ route('norakstishana.create', ['inventara_id' => $item->inventars_id]) }}">Norakstīšana</option>
                                    </select>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $inventari->links() }}
        </div>
    @endif

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Klikšķis uz paziņojuma aizver veiksmīgo ziņojumu bez lapas pārlādes.
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.addEventListener('click', function() {
                this.remove();
            });
        }

        // Dzēšanas darbībai prasām lietotāja apstiprinājumu.
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (confirm('Vai vēlaties dzēst šo ierakstu ?')) {
                    window.location.href = `/inventars/${id}/delete`;
                }
            });
        });

        // Ātrā pāreja notiek uzreiz pēc darbības izvēles.
        document.querySelectorAll('.quick-action-select').forEach(select => {
            select.addEventListener('change', function() {
                if (!this.value) {
                    return;
                }

                window.location.href = this.value;
            });
        });
    });
</script>