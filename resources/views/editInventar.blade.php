@extends('layout.app')

@section('content')
    <h2>Rediģēt inventāru</h2>
    <a href="/inventars" class="btn btn-secondary">Atpakaļ</a>
    <hr>

    <form method="POST" action="/inventars/{{ $inventar->inventars_id }}/editSubmit">
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" value="{{ $inventar->nosaukums }}" required>
        </div>
        <div class="mb-3">
            <label for="apraksts" class="form-label">Apraksts</label>
            <textarea class="form-control" id="apraksts" name="apraksts">{{ $inventar->apraksts }}</textarea>
        </div>
        <div class="mb-3">
            <label for="nolietojums" class="form-label">Nolietojums</label>
            <input type="text" class="form-control" id="nolietojums" name="nolietojums" value="{{ $inventar->nolietojums }}">
        </div>
        <div class="mb-3">
            <label for="statuss" class="form-label">Statuss</label>
            <input type="text" class="form-control" id="statuss" name="statuss" value="{{ $inventar->statuss }}">
        </div>
        <div class="mb-3">
            <label for="kategorija_id" class="form-label">Kategorijas ID</label>
            <select class="form-control" id="kategorija_id" name="kategorija_id" required>
                <option value="">-- Izvēlieties kategoriju --</option>
                @foreach($kategorijas as $k)
                    <option value="{{ $k->kategorija_id }}" @if($inventar->kategorija_id == $k->kategorija_id) selected @endif>{{ $k->nosaukums }} (ID: {{ $k->kategorija_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="telpas_id" class="form-label">Telpa</label>
            <select class="form-control" id="telpas_id" name="telpas_id" required>
                <option value="">-- Izvēlieties telpu --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}" @if($inventar->telpas_id == $t->telpas_id) selected @endif>{{ $t->nosaukums }} (ID: {{ $t->telpas_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="atbildigais_id" class="form-label">Atbildīgais lietotājs</label>
            <select class="form-control" id="atbildigais_id" name="atbildigais_id">
                <option value="">-- Izvēlieties lietotāju (pēc izvēles) --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}" @if($inventar->atbildigais_id == $lt->lietotajs_id) selected @endif>{{ $lt->lietotajvards }} (ID: {{ $lt->lietotajs_id }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection