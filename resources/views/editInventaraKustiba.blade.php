@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Rediģēt inventāra kustību</p>

    <div class="auth-links">
        <a href="/inventara_kustiba" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Rediģēt inventāra kustību</h2>

    @include('partials.validation-errors')

    <!-- Inventāra kustības rediģēšanas forma -->
    <form method="POST" action="/inventara_kustiba/{{ $kustiba->kustiba_id }}/editSubmit" novalidate>
        @csrf
        @php($selectedDatums = old('datums', $kustiba->datums))
        @php($selectedInventarsId = old('inventars_id', $kustiba->inventars_id))
        @php($selectedKustibasVeidsId = old('kustibas_veids_id', $kustiba->kustibas_veids_id))
        @php($selectedVecaTelpaId = old('veca_telpa_id', $kustiba->veca_telpa_id))
        @php($selectedJaunaTelpaId = old('jauna_telpa_id', $kustiba->jauna_telpa_id))
        @php($selectedAtbildigaisId = old('atbildigais_lietotajs_id', $kustiba->atbildigais_lietotajs_id))
        @php($selectedJaunaisAtbildigaisId = old('Jatbildigais_lietotajs_id', $kustiba->Jatbildigais_lietotajs_id))
        @php($selectedPiezimes = old('piezimes', $kustiba->piezimes))
        <div class="mb-3">
            <label for="datums" class="form-label">Datums</label>
            <input type="date" class="form-control" id="datums" name="datums" value="{{ $selectedDatums }}" required lang="lv">
        </div>
        <div class="mb-3">
            <label for="inventars_id" class="form-label">Inventārs</label>
            <select class="form-control" id="inventars_id" name="inventars_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option value="{{ $inv->inventars_id }}" data-current-telpa-id="{{ $inv->telpas_id ?? '' }}" data-current-atbildigais-id="{{ $inv->atbildigais_id ?? '' }}" {{ (string) $selectedInventarsId === (string) $inv->inventars_id ? 'selected' : '' }}>{{ $inv->nosaukums }} (Inv. Nr.: {{ $inv->inventara_numurs ?? '-' }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3" data-field="veca_telpa">
            <label for="veca_telpa_id" class="form-label">Tekošā telpa</label>
            <select class="form-control" id="veca_telpa_id" name="veca_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}" {{ (string) $selectedVecaTelpaId === (string) $t->telpas_id ? 'selected' : '' }}>{{ $t->nosaukums }}</option>
                @endforeach
            </select>
            <small id="veca-telpa-lock-note" style="display:none; color:#E2D4BB; opacity:0.85;">Izvēloties inventāru, tekošā telpa tiek iestatīta automātiski un nav maināma.</small>
        </div>
        <div class="mb-3" data-field="jauna_telpa">
            <label for="jauna_telpa_id" class="form-label">Jaunā telpa</label>
            <select class="form-control" id="jauna_telpa_id" name="jauna_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}" {{ (string) $selectedJaunaTelpaId === (string) $t->telpas_id ? 'selected' : '' }}>{{ $t->nosaukums }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3" data-field="piezimes">
            <label for="piezimes" class="form-label">Piezīmes</label>
            <input type="text" class="form-control" id="piezimes" name="piezimes" value="{{ $selectedPiezimes }}">
        </div>
        <div class="mb-3">
            <label for="kustibas_veids_id" class="form-label">Kustības veids</label>
            <select class="form-control" id="kustibas_veids_id" name="kustibas_veids_id">
                <option value="">-- Izvēlieties kustības veidu --</option>
                @foreach($kustibasVeidi as $kv)
                    @php($isNorakstisana = str_contains(mb_strtolower($kv->nosaukums, 'UTF-8'), 'norakst'))
                    @if(! $isNorakstisana || (int) $selectedKustibasVeidsId === (int) $kv->kustibas_veids_id)
                        <option value="{{ $kv->kustibas_veids_id }}" {{ (string) $selectedKustibasVeidsId === (string) $kv->kustibas_veids_id ? 'selected' : '' }}>{{ $kv->nosaukums }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="mb-3" data-field="atbildigais">
            <label for="atbildigais_lietotajs_id" class="form-label">Atbildīgais darbinieks</label>
            <select class="form-control" id="atbildigais_lietotajs_id" name="atbildigais_lietotajs_id" required>
                <option value="">-- Izvēlieties darbinieku --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}" {{ (string) $selectedAtbildigaisId === (string) $lt->lietotajs_id ? 'selected' : '' }}>{{ $lt->pilnais_vards }}</option>
                @endforeach
            </select>
            <small id="atbildigais-lock-note" style="display:none; color:#E2D4BB; opacity:0.85;">Izvēloties inventāru, atbildīgais darbinieks tiek iestatīts automātiski un nav maināms.</small>
        </div>
        <div class="mb-3" data-field="jauns_atbildigais">
            <label for="Jatbildigais_lietotajs_id" class="form-label">Jauns atbildīgais darbinieks</label>
            <select class="form-control" id="Jatbildigais_lietotajs_id" name="Jatbildigais_lietotajs_id">
                <option value="">-- Izvēlieties jauno atbildīgo --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}" {{ (string) $selectedJaunaisAtbildigaisId === (string) $lt->lietotajs_id ? 'selected' : '' }}>{{ $lt->pilnais_vards }}</option>
                @endforeach
            </select>
        </div>
        <!-- Saglabā atjaunināto kustības ierakstu -->
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inventarsSelect = document.getElementById('inventars_id');
        const kustibasVeidsSelect = document.getElementById('kustibas_veids_id');
        const vecaTelpaSelect = document.getElementById('veca_telpa_id');
        const jaunaTelpaSelect = document.getElementById('jauna_telpa_id');
        const vecaTelpaLockNote = document.getElementById('veca-telpa-lock-note');
        const atbildigaisSelect = document.getElementById('atbildigais_lietotajs_id');
        const jaunsAtbildigaisSelect = document.getElementById('Jatbildigais_lietotajs_id');
        const atbildigaisLockNote = document.getElementById('atbildigais-lock-note');

        const fieldContainers = {
            veca_telpa: document.querySelector('[data-field="veca_telpa"]'),
            jauna_telpa: document.querySelector('[data-field="jauna_telpa"]'),
            piezimes: document.querySelector('[data-field="piezimes"]'),
            atbildigais: document.querySelector('[data-field="atbildigais"]'),
            jauns_atbildigais: document.querySelector('[data-field="jauns_atbildigais"]'),
        };

        const normalize = (value) => (value || '').toLowerCase();

        const getVeidsKey = () => {
            const selected = kustibasVeidsSelect.options[kustibasVeidsSelect.selectedIndex];
            const text = normalize(selected ? selected.textContent : '');

            if (text.includes('pārvietošan') || text.includes('parvietosan')) {
                return 'parvietosana';
            }
            if (text.includes('nodo')) {
                return 'nodosana';
            }
            if (text.includes('remont')) {
                return 'remonts';
            }

            return '';
        };

        const setFieldVisible = (key, visible) => {
            const el = fieldContainers[key];
            if (!el) return;
            el.style.display = visible ? '' : 'none';
        };

        const applyVisibleFieldsByVeids = () => {
            const veidsKey = getVeidsKey();

            setFieldVisible('veca_telpa', false);
            setFieldVisible('jauna_telpa', false);
            setFieldVisible('piezimes', false);
            setFieldVisible('atbildigais', false);
            setFieldVisible('jauns_atbildigais', false);

            jaunaTelpaSelect.required = false;
            jaunsAtbildigaisSelect.required = false;

            if (!veidsKey) {
                return;
            }

            if (veidsKey === 'parvietosana') {
                setFieldVisible('veca_telpa', true);
                setFieldVisible('jauna_telpa', true);
                setFieldVisible('piezimes', true);
                setFieldVisible('atbildigais', true);
                jaunaTelpaSelect.required = true;
                return;
            }

            if (veidsKey === 'nodosana') {
                setFieldVisible('atbildigais', true);
                setFieldVisible('jauns_atbildigais', true);
                setFieldVisible('piezimes', true);
                jaunsAtbildigaisSelect.required = true;
                return;
            }

            if (veidsKey === 'remonts') {
                setFieldVisible('veca_telpa', true);
                setFieldVisible('atbildigais', true);
                setFieldVisible('piezimes', true);
            }
        };

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
        kustibasVeidsSelect.addEventListener('change', applyVisibleFieldsByVeids);

        applyInventoryLockRules();
        applyVisibleFieldsByVeids();
    });
</script>