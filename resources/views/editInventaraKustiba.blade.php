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
        <div class="mb-3">
            <label for="datums" class="form-label">Datums</label>
            <input type="date" class="form-control" id="datums" name="datums" value="{{ $kustiba->datums }}" required lang="lv">
        </div>
        <div class="mb-3">
            <label for="inventars_id" class="form-label">Inventārs</label>
            <select class="form-control" id="inventars_id" name="inventars_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option value="{{ $inv->inventars_id }}" data-current-telpa-id="{{ $inv->telpas_id ?? '' }}" data-current-atbildigais-id="{{ $inv->atbildigais_id ?? '' }}" @if($kustiba->inventars_id == $inv->inventars_id) selected @endif>{{ $inv->nosaukums }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="veca_telpa_id" class="form-label">Vecā telpa</label>
            <select class="form-control" id="veca_telpa_id" name="veca_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}" @if($kustiba->veca_telpa_id == $t->telpas_id) selected @endif>{{ $t->nosaukums }}</option>
                @endforeach
            </select>
            <small id="veca-telpa-lock-note" style="display:none; color:#E2D4BB; opacity:0.85;">Izvēloties inventāru, vecā telpa tiek iestatīta automātiski un nav maināma.</small>
        </div>
        <div class="mb-3">
            <label for="jauna_telpa_id" class="form-label">Jaunā telpa</label>
            <select class="form-control" id="jauna_telpa_id" name="jauna_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}" @if($kustiba->jauna_telpa_id == $t->telpas_id) selected @endif>{{ $t->nosaukums }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="piezimes" class="form-label">Piezīmes</label>
            <input type="text" class="form-control" id="piezimes" name="piezimes" value="{{ $kustiba->piezimes }}">
        </div>
        <div class="mb-3">
            <label for="kustibas_veids_id" class="form-label">Kustības veids</label>
            <select class="form-control" id="kustibas_veids_id" name="kustibas_veids_id">
                <option value="">-- Izvēlieties kustības veidu --</option>
                @foreach($kustibasVeidi as $kv)
                    @php($isNorakstisana = str_contains(mb_strtolower($kv->nosaukums, 'UTF-8'), 'norakst'))
                    @if(! $isNorakstisana || (int) $kustiba->kustibas_veids_id === (int) $kv->kustibas_veids_id)
                        <option value="{{ $kv->kustibas_veids_id }}" @if($kustiba->kustibas_veids_id == $kv->kustibas_veids_id) selected @endif>{{ $kv->nosaukums }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="atbildigais_lietotajs_id" class="form-label">Atbildīgais darbinieks</label>
            <select class="form-control" id="atbildigais_lietotajs_id" name="atbildigais_lietotajs_id" required>
                <option value="">-- Izvēlieties darbinieku --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}" @if($kustiba->atbildigais_lietotajs_id == $lt->lietotajs_id) selected @endif>{{ $lt->pilnais_vards }}</option>
                @endforeach
            </select>
            <small id="atbildigais-lock-note" style="display:none; color:#E2D4BB; opacity:0.85;">Izvēloties inventāru, atbildīgais darbinieks tiek iestatīts automātiski un nav maināms.</small>
        </div>
        <!-- Saglabā atjaunināto kustības ierakstu -->
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
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