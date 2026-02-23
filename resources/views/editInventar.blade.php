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
            <label for="atrasanas_vieta_id" class="form-label">Atrašanās vietas ID</label>
            <select class="form-control" id="atrasanas_vieta_id" name="atrasanas_vieta_id" required>
                <option value="">-- Izvēlieties atrašanās vietu --</option>
                @foreach($vietas as $v)
                    <option value="{{ $v->atrasanas_vieta_id }}" @if($inventar->atrasanas_vieta_id == $v->atrasanas_vieta_id) selected @endif>{{ $v->nodala }} (ID: {{ $v->atrasanas_vieta_id }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection