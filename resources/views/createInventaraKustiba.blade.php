@extends('layout.app')

@section('content')
    <h2>Jauna inventāra kustība</h2>
    <a href="/inventara_kustiba" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    <hr>

    <form method="POST" action="{{ route('inventara_kustiba.store') }}">
        @csrf
        <div class="mb-3">
            <label for="datums" class="form-label">Datums</label>
            <input type="date" class="form-control" id="datums" name="datums" required>
        </div>
        <div class="mb-3">
            <label for="inventars_id" class="form-label">Inventārs</label>
            <select class="form-control" id="inventars_id" name="inventars_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option value="{{ $inv->inventars_id }}">{{ $inv->nosaukums }} (ID: {{ $inv->inventars_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="no_atrasanas_vietas_id" class="form-label">No vietas</label>
            <select class="form-control" id="no_atrasanas_vietas_id" name="no_atrasanas_vietas_id" required>
                <option value="">-- Izvēlieties vietu --</option>
                @foreach($vietas as $v)
                    <option value="{{ $v->atrasanas_vieta_id }}">{{ $v->nodala }} (ID: {{ $v->atrasanas_vieta_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="uz_atrasanas_vietas_id" class="form-label">Uz vietas</label>
            <select class="form-control" id="uz_atrasanas_vietas_id" name="uz_atrasanas_vietas_id" required>
                <option value="">-- Izvēlieties vietu --</option>
                @foreach($vietas as $v)
                    <option value="{{ $v->atrasanas_vieta_id }}">{{ $v->nodala }} (ID: {{ $v->atrasanas_vieta_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="atbildigais_lietotajs_id" class="form-label">Atbildīgais lietotājs</label>
            <select class="form-control" id="atbildigais_lietotajs_id" name="atbildigais_lietotajs_id" required>
                <option value="">-- Izvēlieties lietotāju --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}">{{ $lt->lietotajvards }} (ID: {{ $lt->lietotajs_id }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection