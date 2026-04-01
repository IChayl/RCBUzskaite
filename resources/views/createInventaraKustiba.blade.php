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
        @php($today = now()->toDateString())
        <div class="mb-3">
            <label for="datums" class="form-label">Datums</label>
            <input type="date" class="form-control" id="datums" value="{{ $today }}" disabled lang="lv">
            <input type="hidden" name="datums" value="{{ $today }}">
        </div>
        <div class="mb-3">
            <label for="inventars_id" class="form-label">Inventārs</label>
            <select class="form-control" id="inventars_id" name="inventars_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option style="color: #0F1931;" value="{{ $inv->inventars_id }}" data-current-telpa-id="{{ $inv->telpas_id ?? '' }}" data-current-atbildigais-id="{{ $inv->atbildigais_id ?? '' }}">{{ $inv->nosaukums }}</option>
                @endforeach
            </select>
        </div>
                <div class="mb-3">
            <label for="kustibas_veids_id" class="form-label">Kustības veids</label>
            <select class="form-control" id="kustibas_veids_id" name="kustibas_veids_id">
                <option value="">-- Izvēlieties kustības veidu --</option>
                @foreach($kustibasVeidi as $kv)
                    @php($isNorakstisana = str_contains(mb_strtolower($kv->nosaukums, 'UTF-8'), 'norakst'))
                    @if(! $isNorakstisana)
                        <option value="{{ $kv->kustibas_veids_id }}">{{ $kv->nosaukums }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="veca_telpa_id" class="form-label">Vecā telpa</label>
            <select class="form-control" id="veca_telpa_id" name="veca_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}">{{ $t->nosaukums }}</option>
                @endforeach
            </select>
            <small id="veca-telpa-lock-note" style="display:none; color:#E2D4BB; opacity:0.85;">Izvēloties inventāru, vecā telpa tiek iestatīta automātiski un nav maināma.</small>
        </div>
        <div class="mb-3">
            <label for="jauna_telpa_id" class="form-label">Jaunā telpa</label>
            <select class="form-control" id="jauna_telpa_id" name="jauna_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}">{{ $t->nosaukums }}</option>
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
                    <option style="color: #0F1931;" value="{{ $lt->lietotajs_id }}">{{ $lt->pilnais_vards }}</option>
                @endforeach
            </select>
            <small id="atbildigais-lock-note" style="display:none; color:#E2D4BB; opacity:0.85;">Izvēloties inventāru, atbildīgais darbinieks tiek iestatīts automātiski un nav maināms.</small>
        </div>
        <!-- Saglabā kustības ierakstu -->
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inventarsSelect = document.getElementById('inventars_id');
        const vecaTelpaSelect = document.getElementById('veca_telpa_id');
        const vecaTelpaLockNote = document.getElementById('veca-telpa-lock-note');
        const atbildigaisSelect = document.getElementById('atbildigais_lietotajs_id');
        const atbildigaisLockNote = document.getElementById('atbildigais-lock-note');

        const applyInventoryLockRules = () => {
            const selectedInventars = inventarsSelect.options[inventarsSelect.selectedIndex];
            if (!selectedInventars || !inventarsSelect.value) {
                vecaTelpaSelect.disabled = false;
                vecaTelpaLockNote.style.display = 'none';
                atbildigaisSelect.disabled = false;
                atbildigaisLockNote.style.display = 'none';
                return;
            }

            const currentTelpaId = selectedInventars ? selectedInventars.getAttribute('data-current-telpa-id') : '';
            const currentAtbildigaisId = selectedInventars ? selectedInventars.getAttribute('data-current-atbildigais-id') : '';

            vecaTelpaSelect.value = currentTelpaId || '';
            vecaTelpaSelect.disabled = true;
            vecaTelpaLockNote.style.display = 'block';

            atbildigaisSelect.value = currentAtbildigaisId || '';
            atbildigaisSelect.disabled = true;
            atbildigaisLockNote.style.display = 'block';
        };

        inventarsSelect.addEventListener('change', applyInventoryLockRules);

        applyInventoryLockRules();
    });
</script>