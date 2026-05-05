@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi kustību veidi</p>
 <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/kustibas_veidi/create" class="bloom-button sm icon-button" title="Jauns kustību veids" aria-label="Jauns kustību veids"><i class="fas fa-plus" aria-hidden="true"></i><span class="sr-only">Jauns kustību veids</span></a>
        @endif
        <a type="button" class="auth-links" onclick="window.print()" title="Printēt dokumentu"><i class="fas fa-print"></i> Printēt</a>
    </div>

    <hr>

    <div style="color: #E2D4BB; margin-top: 20px;">
        @if(session('success'))
            <div id="flash-message" style="background: #0F1931; color: #E2D4BB; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #2D4159; cursor: pointer;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <h2 style="color: #E2D4BB;">Kustību veidi</h2>

    <!-- Filtri kustību veidu sarakstam -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <label style="display:flex; align-items:center; gap:8px;">
            <span style="color:#E2D4BB; font-size:0.9rem;">Meklēt pēc:</span>
            <select name="column" style="border-radius:999px; padding: 8px 12px; border:1px solid rgba(226, 212, 187, 0.2); background:rgba(226, 212, 187, 0.06); color:#E2D4BB;">
                <option style="color:#0F1931;" value="all" {{ request('column') === 'all' ? 'selected' : '' }}>Visi</option>
                <option style="color:#0F1931;" value="nosaukums" {{ request('column') === 'nosaukums' ? 'selected' : '' }}>Nosaukums</option>
                <option style="color:#0F1931;" value="apraksts" {{ request('column') === 'apraksts' ? 'selected' : '' }}>Apraksts</option>
            </select>
        </label>
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
        <a href="{{ url('/kustibas_veidi') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#2D4159;">Nav rezultātu.</span>
    </form>

    @if($veidi->isEmpty())
        <p style="color: #E2D4BB;">Nav ierakstu.</p>
    @else
        <!-- Tabula kustību veidiem ar kārtošanu pa laukiem -->
        <div class="table-wrap">
            <table class="data-table" data-print-group-column="0" data-print-group-label="Nosaukuma burts" data-print-group-mode="initial">
                <thead>
                    <tr>
                        <th class="sortable {{ request('sort') === 'nosaukums' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'nosaukums' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nosaukums', 'direction' => $dir]) }}">Nosaukums</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'apraksts' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'apraksts' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'apraksts', 'direction' => $dir]) }}">Apraksts</a>
                        </th>
                       @if(Auth::user()->admina_tiesibas) <th>Darbības</th> @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterē cauri visiem paginētajiem kustību veidiem -->
                    @foreach ($veidi as $item)
                        <tr>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->apraksts ?? '-' }}</td>
                             @if(Auth::user()->admina_tiesibas) <td>
                                <div class="actions">
                                        <a href="#" class="bloom-button sm icon-button delete-btn" data-id="{{ $item->kustibas_veids_id }}" title="Dzēst" aria-label="Dzēst"><i class="fas fa-trash" aria-hidden="true"></i><span class="sr-only">Dzēst</span></a>
                                        <a href="/kustibas_veidi/{{ $item->kustibas_veids_id }}/edit" class="bloom-button sm icon-button" title="Rediģēt" aria-label="Rediģēt"><i class="fas fa-edit" aria-hidden="true"></i><span class="sr-only">Rediģēt</span></a>
                                </div>
                            </td>  @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $veidi->links() }}
        </div>
    @endif

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flash ziņojumu var aizvērt ar klikšķi.
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.addEventListener('click', function() {
                this.remove();
            });
        }

        // Dzēšanas apstiprinājums pirms faktiskās dzēšanas.
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                // Vienota projekta apstiprināšana pirms neatgriezeniskas darbības.
                window.appConfirm('Vai vēlaties dzēst šo ierakstu?', {
                    title: 'Dzēšanas apstiprinājums',
                    acceptText: 'Dzēst',
                    cancelText: 'Atcelt'
                }).then((accepted) => {
                    if (!accepted) {
                        // Atteikums: saglabājam esošo stāvokli bez izmaiņām.
                        return;
                    }
                    // Apstiprinājuma gadījumā pāradresējam uz dzēšanas URL.
                    window.location.href = `/kustibas_veidi/${id}/delete`;
                });
            });
        });
    });
</script>
