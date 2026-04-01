@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Norakstīšanas</p>

    <div class="auth-links">
        <a href="/norakstishana/create">Pieteikt norakstīšanu</a>
        <a type="button" class="auth-links" onclick="window.print()" title="Printēt dokumentu"><i class="fas fa-print"></i> Printēt</a>
    </div>

    <hr>
    <h2 style="color: #E2D4BB;">Norakstīšanas</h2>

    <!-- Meklēšanas forma -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="nosaukums" {{ request('column') === 'nosaukums' ? 'selected' : '' }}>Inventāra nosaukums</option>
                <option style="color:#0F1931;" value="inventara_numurs" {{ request('column') === 'inventara_numurs' ? 'selected' : '' }}>Inv. numurs</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <span style="color:#E2D4BB;">Datums no</span>
        <input type="date" name="nor_datums_no" value="{{ request('nor_datums_no') }}" class="table-search-input" style="max-width: 170px;" title="Norakstīšanas datums no">
        <span style="color:#E2D4BB;">līdz</span>
        <input type="date" name="nor_datums_lidz" value="{{ request('nor_datums_lidz') }}" class="table-search-input" style="max-width: 170px;" title="Norakstīšanas datums līdz">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Filtrēt</button>
        <a href="{{ url('/norakstishana') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#2D4159;">Nav rezultātu.</span>
    </form>

    <div style="color: #E2D4BB; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0F1931; color: #E2D4BB; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #2D4159; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="flash-error" style="background: #3b1010; color: #F9D8D8; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #a94442; cursor: pointer; margin-top: 8px;">
                {{ session('error') }}
            </div>
        @endif
    </div>

    @if(Auth::user()->admina_tiesibas && $pendingNorakstishanaCount > 0)
        <div style="margin: 20px 0; padding: 18px 20px; border-radius: 16px; border: 1px solid rgba(226, 212, 187, 0.3); background: rgba(226, 212, 187, 0.08); color: #E2D4BB; box-shadow: 0 10px 24px rgba(15, 25, 49, 0.22);">
            <div style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:12px; align-items:center; margin-bottom: 14px;">
                <div>
                    <strong style="font-size: 1.05rem;">Administratora paziņojums</strong>
                    <div style="opacity: 0.9; margin-top: 4px;">Ir saņemti {{ $pendingNorakstishanaCount }} neakceptēti inventāra norakstīšanas pieteikumi.</div>
                </div>
                <span style="display:inline-flex; align-items:center; justify-content:center; min-width:38px; height:38px; padding:0 12px; border-radius:999px; background:#E2D4BB; color:#0F1931; font-weight:700;">
                    {{ $pendingNorakstishanaCount }}
                </span>
            </div>

            @if($pendingNorakstishanaRequests->isNotEmpty())
                <div style="display:grid; gap:10px;">
                    @foreach($pendingNorakstishanaRequests as $pendingItem)
                        <div style="padding: 12px 14px; border-radius: 12px; background: rgba(15, 25, 49, 0.35); border: 1px solid rgba(226, 212, 187, 0.14);">
                            <div style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; align-items:center;">
                                <div>
                                    <strong>{{ optional($pendingItem->inventars)->nosaukums ?? ('Inventārs ID: '.$pendingItem->inventara_id) }}</strong>
                                    <div style="opacity:0.88; margin-top:4px;">
                                        Pieteica: {{ optional($pendingItem->pieteicejs)->pilnais_vards ?? optional($pendingItem->pieteicejs)->lietotajvards ?? 'Nezināms lietotājs' }}
                                        , datums: @lvDate($pendingItem->pieteikshanas_dat)
                                        , iemesls: {{ $pendingItem->iemesls }}
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('norakstishana.accept', $pendingItem->norakstishana_id) }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="bloom-button sm">Akceptēt pieteikumu</button>
                                </form>
                                <form method="POST" action="{{ route('norakstishana.cancel', $pendingItem->norakstishana_id) }}" style="margin:0;" onsubmit="return confirm('Vai tiešām vēlaties atcelt šo pieteikumu?');">
                                    @csrf
                                    <button type="submit" class="bloom-button sm" style="background:#5a1b1b; border-color:#7d2d2d;">Atcelt pieteikumu</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if($norakstishanas->isEmpty())
        <p style="color: #E2D4BB;">Nav ierakstu.</p>
    @else
        <!-- Norakstīšanas tabula -->
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Inv. numurs</th>
                        <th class="sortable {{ request('sort') === 'inventars' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'inventars' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'inventars', 'direction' => $dir]) }}">Nosaukums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'norDatums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'norDatums' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'norDatums', 'direction' => $dir]) }}">Datums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'iemesls' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'iemesls' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'iemesls', 'direction' => $dir]) }}">Iemesls</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'talaka_riciba' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'talaka_riciba' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'talaka_riciba', 'direction' => $dir]) }}">Tālākā rīcība</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'pieteikuma_datums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'pieteikuma_datums' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'pieteikuma_datums', 'direction' => $dir]) }}">Pieteikuma datums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'apstiprinasanas_datums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'apstiprinasanas_datums' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'apstiprinasanas_datums', 'direction' => $dir]) }}">Apstiprināšanas datums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'akceptets' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'akceptets' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'akceptets', 'direction' => $dir]) }}">Akceptēts</a>
                        </th>
                      @if(Auth::user()->admina_tiesibas) <th>Darbības</th> @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($norakstishanas as $item)
                        <tr>
                            <td>{{ optional($item->inventars)->inventara_numurs ?? '-' }}</td>
                            <td>
                                {{ optional($item->inventars)->nosaukums ?? ('ID: '.$item->inventara_id) }}
                            </td>
                            <td>@lvDate($item->norDatums)</td>
                            <td>{{ $item->iemesls }}</td>
                            <td>{{ $item->talaka_riciba }}</td>
                            <td>@lvDate($item->pieteikshanas_dat)</td>
                            <td>
                                @if($item->akceptets)
                                    @lvDate($item->apstiprinashanas_dat)
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->akceptets ? 'Jā' : 'Nē' }}</td>
                               @if(Auth::user()->admina_tiesibas) <td>
                                <div class="actions">
                                 
                                    @if(!$item->akceptets)
                                        <form method="POST" action="{{ route('norakstishana.accept', $item->norakstishana_id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="bloom-button sm">Akceptēt</button>
                                        </form>
                                        <form method="POST" action="{{ route('norakstishana.cancel', $item->norakstishana_id) }}" style="display:inline;" onsubmit="return confirm('Vai tiešām vēlaties atcelt šo pieteikumu?');">
                                            @csrf
                                            <button type="submit" class="bloom-button sm" style="background:#5a1b1b; border-color:#7d2d2d;">Atcelt pieteikumu</button>
                                        </form>
                                    @endif
                                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->norakstishana_id }}">Dzēst</a>
                                        <a href="/norakstishana/{{ $item->norakstishana_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                </div>
                            </td>@endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $norakstishanas->links() }}
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

        const flashError = document.getElementById('flash-error');
        if (flashError) {
            flashError.addEventListener('click', function() {
                this.remove();
            });
        }

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (confirm('Vai vēlaties dzēst šo ierakstu ?')) {
                    window.location.href = `/norakstishana/${id}/delete`;
                }
            });
        });
    });
</script>
