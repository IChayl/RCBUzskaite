@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Rediģēt inventāra kustību</p>

    <div class="auth-links">
        <a href="/inventara_kustiba" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Rediģēt inventāra kustību</h2>

    <!-- Inventāra kustības rediģēšanas forma -->
    <form method="POST" action="/inventara_kustiba/{{ $kustiba->kustiba_id }}/editSubmit">
        @csrf
        <div class="mb-3">
            <label for="datums" class="form-label">Datums</label>
            <input type="date" class="form-control" id="datums" name="datums" value="{{ $kustiba->datums }}" required>
        </div>
        <div class="mb-3">
            <label for="inventars_id" class="form-label">Inventārs</label>
            <select class="form-control" id="inventars_id" name="inventars_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option value="{{ $inv->inventars_id }}" @if($kustiba->inventars_id == $inv->inventars_id) selected @endif>{{ $inv->nosaukums }} (ID: {{ $inv->inventars_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="veca_telpa_id" class="form-label">Vecā telpa</label>
            <select class="form-control" id="veca_telpa_id" name="veca_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}" @if($kustiba->veca_telpa_id == $t->telpas_id) selected @endif>{{ $t->nosaukums }} (ID: {{ $t->telpas_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jauna_telpa_id" class="form-label">Jaunā telpa</label>
            <select class="form-control" id="jauna_telpa_id" name="jauna_telpa_id">
                <option value="">-- Nav --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}" @if($kustiba->jauna_telpa_id == $t->telpas_id) selected @endif>{{ $t->nosaukums }} (ID: {{ $t->telpas_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="piezimes" class="form-label">Piezīmes</label>
            <input type="text" class="form-control" id="piezimes" name="piezimes" value="{{ $kustiba->piezimes }}">
        </div>
        <div class="mb-3">
            <label for="dokuments" class="form-label">Dokuments</label>
            <input type="text" class="form-control" id="dokuments" name="dokuments" value="{{ $kustiba->dokuments }}">
        </div>
        <div class="mb-3">
            <label for="kustibas_veids_id" class="form-label">Kustības veids</label>
            <select class="form-control" id="kustibas_veids_id" name="kustibas_veids_id">
                <option value="">-- Izvēlieties kustības veidu --</option>
                @foreach($kustibasVeidi as $kv)
                    <option value="{{ $kv->kustibas_veids_id }}" @if($kustiba->kustibas_veids_id == $kv->kustibas_veids_id) selected @endif>{{ $kv->nosaukums }} (ID: {{ $kv->kustibas_veids_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="atbildigais_lietotajs_id" class="form-label">Atbildīgais lietotājs</label>
            <select class="form-control" id="atbildigais_lietotajs_id" name="atbildigais_lietotajs_id" required>
                <option value="">-- Izvēlieties lietotāju --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}" @if($kustiba->atbildigais_lietotajs_id == $lt->lietotajs_id) selected @endif>{{ $lt->lietotajvards }} (ID: {{ $lt->lietotajs_id }})</option>
                @endforeach
            </select>
        </div>
        <!-- Saglabā atjaunināto kustības ierakstu -->
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection