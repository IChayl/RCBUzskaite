@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Visi darbinieki</p>

   <div class="auth-links">
        @if(Auth::user()->admina_tiesibas)
            <a href="/lietotajs/create" class="bloom-button sm icon-button" title="Jauns darbinieks" aria-label="Jauns darbinieks"><i class="fas fa-plus" aria-hidden="true"></i><span class="sr-only">Jauns darbinieks</span></a>
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

    <h2 style="color: #E2D4BB;">Darbinieki</h2>

    @if(!($canViewDarbiniekiTable ?? false))
        <p style="color: #E2D4BB;">Darbinieku tabula pieejama tikai amatiem "Direktors" un "Dir.Vietnieks".</p>
    @elseif($lietotaji->isEmpty())
        <p style="color: #E2D4BB;">Nav darbinieku.</p>
    @else
        <div class="table-wrap">
            <table class="data-table" data-print-group-column="5" data-print-group-label="Amats">
                <thead>
                    <tr>
                        <th>Vārds</th>
                        <th>Uzvārds</th>
                        <th>E-pasts</th>
                        <th>Telefons</th>
                        <th>Amats</th>
                        <th>Admins</th>
                        @if(Auth::user()->admina_tiesibas)
                            <th>Darbības</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lietotaji as $item)
                        <tr>
                            <td>{{ $item->vards ?? '-' }}</td>
                            <td>{{ $item->uzvards ?? '-' }}</td>
                            <td>{{ $item->epasts ?? '-' }}</td>
                            <td>{{ $item->telefons ?? '-' }}</td>
                            <td>{{ $item->amats ?? '-' }}</td>
                            <td>{{ $item->admina_tiesibas ? 'Jā' : 'Nē' }}</td>
                            @if(Auth::user()->admina_tiesibas)
                                <td>
                                    <div class="actions">
                                        <a href="#" class="bloom-button sm icon-button delete-btn" data-id="{{ $item->lietotajs_id }}" title="Dzēst" aria-label="Dzēst"><i class="fas fa-trash" aria-hidden="true"></i><span class="sr-only">Dzēst</span></a>
                                        <a href="/lietotajs/{{ $item->lietotajs_id }}/edit" class="bloom-button sm icon-button" title="Rediģēt" aria-label="Rediģēt"><i class="fas fa-edit" aria-hidden="true"></i><span class="sr-only">Rediģēt</span></a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flash ziņojums pazūd pēc klikšķa.
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.addEventListener('click', function() {
                this.remove();
            });
        }

        // Dzēšanas darbībai pieprasa apstiprinājumu.
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                // Darbinieka dzēšana notiek tikai pēc modalā dialoga apstiprinājuma.
                window.appConfirm('Vai vēlaties dzēst šo ierakstu?', {
                    title: 'Dzēšanas apstiprinājums',
                    acceptText: 'Dzēst',
                    cancelText: 'Atcelt'
                }).then((accepted) => {
                    if (!accepted) {
                        // Atteikuma gadījumā izpildi neturpinām.
                        return;
                    }
                    // Apstiprinot darbību, pāradresējam uz dzēšanas maršrutu.
                    window.location.href = `/lietotajs/${id}/delete`;
                });
            });
        });
    });
</script>
