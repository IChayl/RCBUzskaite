@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi inventāri</p>

 <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/inventars/create">Jauns inventārs</a>
        @endif
        <a type="button" class="auth-links" onclick="window.print()">Printēt</a>
    </div>

    <hr>
    <h2 style="color: #E2D4BB;">Inventāri</h2>

    <!-- Filtrēšanas forma: saglabā kārtošanas parametrus un meklēšanas frāzi -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="nosaukums" {{ request('column') === 'nosaukums' ? 'selected' : '' }}>Nosaukums</option>
                <option style="color:#0F1931;" value="kategorija" {{ request('column') === 'kategorija' ? 'selected' : '' }}>Kategorija</option>
                <option style="color:#0F1931;" value="telpa" {{ request('column') === 'telpa' ? 'selected' : '' }}>Telpa</option>
                <option value="atbildigais" {{ request('column') === 'atbildigais' ? 'selected' : '' }}>Atbildīgais</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
        <a href="{{ url('/inventars') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#2D4159;">Nav rezultātu.</span>
    </form>

    <div style="color: #E2D4BB; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0F1931; color: #2D4159; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #2D4159; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($inventari->isEmpty())
        <p style="color: #E2D4BB;">Nav ierakstu.</p>
    @else
        <!-- Inventāra tabula ar servera puses kārtošanu pa kolonnām -->
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
                        <th class="sortable {{ request('sort') === 'kategorija' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'kategorija' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'kategorija', 'direction' => $dir]) }}">Kategorija</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'telpa' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'telpa' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'telpa', 'direction' => $dir]) }}">Telpa</a>
                        </th>
        
                        <th class="sortable {{ request('sort') === 'atbildigais' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'atbildigais' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'atbildigais', 'direction' => $dir]) }}">Atbildīgais</a>
                        </th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Tabulas rinda katram inventāra ierakstam -->
                    @foreach ($inventari as $item)
                        <tr>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->kategorija->nosaukums ?? ('ID: '.$item->kategorija_id) }}</td>
                            <td>{{ optional($item->telpa)->nosaukums ?? ('ID: '.$item->telpas_id) }}</td>
                            <td>{{ optional($item->atbildigais)->lietotajvards ?? ('ID: '.$item->atbildigais_id) }}</td>
                            <td>
                                <div class="actions">
                                    @if(Auth::user()->admina_tiesibas)
                                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->inventars_id }}">Dzēst</a>
                                        <a href="/inventars/{{ $item->inventars_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                    @endif
                                </div>
                            </td>
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
    });
</script>
