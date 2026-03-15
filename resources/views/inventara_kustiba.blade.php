@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Visas inventāra kustības</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/inventara_kustiba/create">Jauna kustība</a>
        <button type="button" class="bloom-button sm" onclick="window.print()">Printēt</button>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Inventāra kustība</h2>

    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#ffffff; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.06); color:#ffffff;">
                <option value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option value="datums" {{ request('column') === 'datums' ? 'selected' : '' }}>Datums</option>
                <option value="inventars" {{ request('column') === 'inventars' ? 'selected' : '' }}>Inventārs</option>
                <option value="kustibas_veids" {{ request('column') === 'kustibas_veids' ? 'selected' : '' }}>Kustības veids</option>
                <option value="lietotajs" {{ request('column') === 'lietotajs' ? 'selected' : '' }}>Atbildīgais</option>
                <option value="veca_telpa" {{ request('column') === 'veca_telpa' ? 'selected' : '' }}>Vecā telpa</option>
                <option value="jauna_telpa" {{ request('column') === 'jauna_telpa' ? 'selected' : '' }}>Jaunā telpa</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
        <a href="{{ url('/inventara_kustiba') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#f88;">Nav rezultātu.</span>
    </form>

    <div style="color: #ffffff; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($kustibas->isEmpty())
        <p style="color: #ffffff;">Nav ierakstu.</p>
    @else
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
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'inventars', 'direction' => $dir]) }}">Inventārs</a>
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
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kustibas as $item)
                        <tr>
                            <td>{{ $item->datums }}</td>
                            <td>{{ $item->inventars->nosaukums ?? ('ID: '.$item->inventars_id) }}</td>
                            <td>{{ optional($item->kustibasVeids)->nosaukums ?? ('ID: '.$item->kustibas_veids_id) }}</td>
                            <td>{{ optional($item->vecaTelpa)->nosaukums ?? ('ID: '.$item->veca_telpa_id) }}</td>
                            <td>{{ optional($item->jaunaTelpa)->nosaukums ?? ('ID: '.$item->jauna_telpa_id) }}</td>
                            <td>{{ optional($item->lietotajs)->lietotajvards ?? ('ID: '.$item->atbildigais_lietotajs_id) }}</td>
                            <td>
                                <div class="actions">
                                    @if(Auth::user()->admina_tiesibas)
                                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->kustiba_id }}">Dzēst</a>
                                        <a href="/inventara_kustiba/{{ $item->kustiba_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                    @endif
                                    <a href="/inventara_kustiba/{{ $item->kustiba_id }}/details" class="bloom-button sm">Detalizēta</a>
                                </div>
                            </td>
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
                    window.location.href = `/inventara_kustiba/${id}/delete`;
                }
            });
        });
    });
</script>
