@extends('layout.app')

@section('content')
    <h2>Jauns inventārs</h2>
    <a href="/inventars" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    <hr>

    <form method="POST" action="{{ route('inventars.store') }}">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" required>
        </div>
        <div class="mb-3">
            <label for="apraksts" class="form-label">Apraksts</label>
            <textarea class="form-control" id="apraksts" name="apraksts"></textarea>
        </div>
        <div class="mb-3">
            <label for="statuss" class="form-label">Statuss</label>
            <input type="text" class="form-control" id="statuss" name="statuss">
        </div>
        <div class="mb-3">
            <label for="kategorija_id" class="form-label">Kategorijas ID</label>
            <select class="form-control" id="kategorija_id" name="kategorija_id" required>
                <option value="">-- Izvēlieties kategoriju --</option>
                @foreach($kategorijas as $k)
                    <option value="{{ $k->kategorija_id }}">{{ $k->nosaukums }} (ID: {{ $k->kategorija_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="telpas_id" class="form-label">Telpa</label>
            <select class="form-control" id="telpas_id" name="telpas_id" required>
                <option value="">-- Izvēlieties telpu --</option>
                @foreach($telpas as $t)
                    <option value="{{ $t->telpas_id }}">{{ $t->nosaukums }} (ID: {{ $t->telpas_id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="atbildigais_id" class="form-label">Atbildīgais lietotājs</label>
            <select class="form-control" id="atbildigais_id" name="atbildigais_id">
                <option value="">-- Izvēlieties lietotāju (pēc izvēles) --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}">{{ $lt->lietotajvards }} (ID: {{ $lt->lietotajs_id }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection