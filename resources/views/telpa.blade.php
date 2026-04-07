@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visas telpas</p>

 <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/telpa/create">Jauna telpa</a>
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

    <h2 style="color: #E2D4BB;">Telpas</h2>

    <!-- Telpu meklēšanas un kārtošanas vadīklas -->
    <form method="GET" class="table-controls">
        <input type="hidden" name="sort" value="{{ request('sort') }}">
        <input type="hidden" name="direction" value="{{ request('direction') }}">
        <input class="table-search-input" name="q" type="text" value="{{ request('q') }}" placeholder="Meklēt pēc telpas nosaukuma...">
        <button type="submit" class="bloom-button sm" style="height: 36px;">Meklēt</button>
        <a href="{{ url('/telpa') }}" class="bloom-button sm" style="height: 36px;">Notīrīt</a>
        <span class="no-results-message" style="display:none; color:#2D4159;">Nav rezultātu.</span>
    </form>

    @if($telpas->isEmpty())
        <p style="color: #E2D4BB;">Nav telpu.</p>
    @else
        <!-- Telpu tabula ar kārtojamām kolonnām -->
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
                        <th class="sortable {{ request('sort') === 'platiba' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'platiba' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'platiba', 'direction' => $dir]) }}">Platība</a>
                        </th>
                        <th class="sortable {{ request('sort') === 'numurs' ? 'sorted-'.request('direction','asc') : '' }}">
                            @php
                                $dir = request('sort') === 'numurs' && request('direction') === 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'numurs', 'direction' => $dir]) }}">Numurs</a>
                        </th>
                        @if(Auth::user()->admina_tiesibas) <th>Darbības</th> @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Attēlo katru telpu kā tabulas rindu -->
                    @foreach ($telpas as $item)
                        <tr>
                            <td>{{ $item->nosaukums }}</td>
                            <td>{{ $item->platiba ?? '-' }}m²</td>
                            <td>{{ $item->numurs ?? '-' }}</td>
                           @if(Auth::user()->admina_tiesibas) <td>
                                <div class="actions">
                                    
                                        <a href="#" class="bloom-button sm delete-btn" data-id="{{ $item->telpas_id }}">Dzēst</a>
                                        <a href="/telpa/{{ $item->telpas_id }}/edit" class="bloom-button sm">Rediģēt</a>
                                   
                                </div>
                            </td> @endif
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
                // Pirms telpas dzēšanas prasa skaidru lietotāja apstiprinājumu.
                window.appConfirm('Vai vēlaties dzēst šo ierakstu?', {
                    title: 'Dzēšanas apstiprinājums',
                    acceptText: 'Dzēst',
                    cancelText: 'Atcelt'
                }).then((accepted) => {
                    if (!accepted) {
                        // Ja nav apstiprināts, lapas stāvokli nemainām.
                        return;
                    }
                    // Apstiprināta darbība: virzāmies uz servera dzēšanas maršrutu.
                    window.location.href = `/telpa/${id}/delete`;
                });
            });
        });
    });
</script>
