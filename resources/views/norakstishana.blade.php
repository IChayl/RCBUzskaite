@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Norakstīšanas</p>

    <div class="auth-links">
        <a href="/norakstishana/create">Pieteikt norakstīšanu</a>
        <a type="button" class="auth-links" onclick="window.print()">Printēt</a>
    </div>

    <hr>
    <h2 style="color: #E2D4BB;">Norakstīšanas</h2>

    <!-- Meklēšanas forma -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <input type="hidden" name="nor_datums_no" value="{{ request('nor_datums_no') }}">
        <input type="hidden" name="nor_datums_lidz" value="{{ request('nor_datums_lidz') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="nosaukums" {{ request('column') === 'nosaukums' ? 'selected' : '' }}>Inventāra nosaukums</option>
                <option style="color:#0F1931;" value="inventara_numurs" {{ request('column') === 'inventara_numurs' ? 'selected' : '' }}>Inv. numurs</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
    </form>

    <!-- Filtrēšanas forma -->
    <form method="GET" class="table-controls" style="margin-top: 10px;">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <input type="hidden" name="column" value="{{ request('column', 'all') }}">
        <input type="hidden" name="q" value="{{ request('q') }}">
        datums no <input type="date" name="nor_datums_no" value="{{ request('nor_datums_no') }}" class="table-search-input" style="max-width: 170px;" title="Norakstīšanas datums no">
        datums līdz <input type="date" name="nor_datums_lidz" value="{{ request('nor_datums_lidz') }}" class="table-search-input" style="max-width: 170px;" title="Norakstīšanas datums līdz">
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
    </div>

    @if($norakstishanas->isEmpty())
        <p style="color: #E2D4BB;">Nav ierakstu.</p>
    @else
        <!-- Norakstīšanas tabula -->
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
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
                            <td>
                                {{ optional($item->inventars)->nosaukums ?? ('ID: '.$item->inventara_id) }}
                                @if(optional($item->inventars)->inventara_numurs)
                                    <br><small>Inv. Nr.: {{ $item->inventars->inventara_numurs }}</small>
                                @endif
                            </td>
                            <td>{{ $item->norDatums->toDateString() }}</td>
                            <td>{{ $item->iemesls }}</td>
                            <td>{{ $item->talaka_riciba }}</td>
                            <td>{{ $item->pieteikshanas_dat ?? '-' }}</td>
                            <td>{{ $item->apstiprinashanas_dat ?? '-' }}</td>
                            <td>{{ $item->akceptets ? 'Jā' : 'Nē' }}</td>
                               @if(Auth::user()->admina_tiesibas) <td>
                                <div class="actions">
                                    <a href="/norakstishana/{{ $item->norakstishana_id }}/details" class="bloom-button sm">Skatīt</a>
                                    @if(!$item->akceptets)
                                        <form method="POST" action="{{ route('norakstishana.accept', $item->norakstishana_id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="bloom-button sm">Akceptēt</button>
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
