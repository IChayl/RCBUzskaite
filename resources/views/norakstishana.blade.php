@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Norakstīšanas</p>

    <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/norakstishana/create">Jauna norakstīšana</a>
        @endif
        <a type="button" class="auth-links" onclick="window.print()">Printēt</a>
    </div>

    <hr>
    <h2 style="color: #E2D4BB;">Norakstīšanas</h2>

    <!-- Filtrēšanas forma: saglabā kārtošanas parametrus un meklēšanas frāzi -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="inventara_id" {{ request('column') === 'inventara_id' ? 'selected' : '' }}>Inventars</option>
                <option style="color:#0F1931;" value="norDatums" {{ request('column') === 'norDatums' ? 'selected' : '' }}>Datums</option>
                <option style="color:#0F1931;" value="iemesls" {{ request('column') === 'iemesls' ? 'selected' : '' }}>Iemesls</option>
                <option style="color:#0F1931;" value="talaka_riciba" {{ request('column') === 'talaka_riciba' ? 'selected' : '' }}>Tālākā rīcība</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
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
                        <th class="sortable {{ request('sort') === 'inventara_id' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'inventara_id' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'inventara_id', 'direction' => $dir]) }}">Inventars</a>
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
                      @if(Auth::user()->admina_tiesibas) <th>Darbības</th> @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($norakstishanas as $item)
                        <tr>
                            <td>{{ optional($item->inventars)->nosaukums ?? ('ID: '.$item->inventara_id) }}</td>
                            <td>{{ $item->norDatums }}</td>
                            <td>{{ $item->iemesls }}</td>
                            <td>{{ $item->talaka_riciba }}</td>
                               @if(Auth::user()->admina_tiesibas) <td>
                                <div class="actions">
                                    <a href="/norakstishana/{{ $item->norakstishana_id }}/details" class="bloom-button sm">Skatīt</a>
                                
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
