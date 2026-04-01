@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Jauna inventāra kustība</p>

    <div class="auth-links">
        <a href="/inventara_kustiba" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Jauna inventāra kustība</h2>

    @include('partials.validation-errors')

    <!-- Jaunas kustības izveides forma -->
    <form method="POST" action="{{ route('inventara_kustiba.store') }}" novalidate>
        @csrf
        <div class="mb-3">
            <label for="datums" class="form-label">Datums</label>
            <input type="date" class="form-control" id="datums" name="datums" required lang="lv">
        </div>
        <div class="mb-3">
            <label for="inventars_id" class="form-label">Inventārs</label>
            <select class="form-control" id="inventars_id" name="inventars_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option value="{{ $inv->inventars_id }}" data-current-telpa-id="{{ $inv->telpas_id ?? '' }}">{{ $inv->nosaukums }} (ID: {{ $inv->inventars_id }})</option>
                @endforeach
            </select>
        </div>
                <div class="mb-3">
            <label for="kustibas_veids_id" class="form-label">Kustības veids</label>
            <select class="form-control" id="kustibas_veids_id" name="kustibas_veids_id">
                <option value="">-- Izvēlieties kustības veidu --</option>
                @foreach($kustibasVeidi as $kv)
                    <option value="{{ $kv->kustibas_veids_id }}">{{ $kv->nosaukums }} (ID: {{ $kv->kustibas_veids_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="veca_telpa_id" class="form-label">Vecā telpa</label>
            <select class="form-control" id="veca_telpa_id" name="veca_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}">{{ $t->nosaukums }} (ID: {{ $t->telpas_id }})</option>
                @endforeach
            </select>
            <small id="veca-telpa-lock-note" style="display:none; color:#E2D4BB; opacity:0.85;">Pie veida "Pārvietošana" vecā telpa tiek iestatīta automātiski no izvēlētā inventāra.</small>
        </div>
        <div class="mb-3">
            <label for="jauna_telpa_id" class="form-label">Jaunā telpa</label>
            <select class="form-control" id="jauna_telpa_id" name="jauna_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}">{{ $t->nosaukums }} (ID: {{ $t->telpas_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="piezimes" class="form-label">Piezīmes</label>
            <input type="text" class="form-control" id="piezimes" name="piezimes">
        </div>

        <div class="mb-3">
            <label for="atbildigais_lietotajs_id" class="form-label">Atbildīgais darbinieks</label>
            <select class="form-control" id="atbildigais_lietotajs_id" name="atbildigais_lietotajs_id" required>
                <option value="">-- Izvēlieties darbinieku --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}">{{ $lt->lietotajvards }} (ID: {{ $lt->lietotajs_id }})</option>
                @endforeach
            </select>
        </div>
        <!-- Saglabā kustības ierakstu -->
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inventarsSelect = document.getElementById('inventars_id');
        const kustibasVeidsSelect = document.getElementById('kustibas_veids_id');
        const vecaTelpaSelect = document.getElementById('veca_telpa_id');
        const lockNote = document.getElementById('veca-telpa-lock-note');

        const isParvietosanaSelected = () => {
            const selected = kustibasVeidsSelect.options[kustibasVeidsSelect.selectedIndex];
            if (!selected) return false;

            const text = (selected.textContent || '').toLowerCase();
            return text.includes('pārvietošan') || text.includes('parvietosan');
        };

        const applyVecaTelpaRule = () => {
            if (!isParvietosanaSelected()) {
                vecaTelpaSelect.disabled = false;
                lockNote.style.display = 'none';
                return;
            }

            const selectedInventars = inventarsSelect.options[inventarsSelect.selectedIndex];
            const currentTelpaId = selectedInventars ? selectedInventars.getAttribute('data-current-telpa-id') : '';

            vecaTelpaSelect.value = currentTelpaId || '';
            vecaTelpaSelect.disabled = true;
            lockNote.style.display = 'block';
        };

        inventarsSelect.addEventListener('change', applyVecaTelpaRule);
        kustibasVeidsSelect.addEventListener('change', applyVecaTelpaRule);

        applyVecaTelpaRule();
    });
</script>