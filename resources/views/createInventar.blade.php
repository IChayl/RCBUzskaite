@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Jauns inventārs</p>

    <div class="auth-links">
        <a href="/inventars" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Jauns inventārs</h2>

    @include('partials.validation-errors')

    <!-- Jauna inventāra izveides forma -->
    <form method="POST" action="{{ route('inventars.store') }}" novalidate>
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" required>
        </div>
        <div class="mb-3">
            <label for="inventara_numurs" class="form-label">Inventāra numurs</label>
            <input type="text" class="form-control" id="inventara_numurs" name="inventara_numurs">
        </div>
        <div class="mb-3">
            <label for="iegades_datums" class="form-label">Iegādes datums</label>
            <input type="date" class="form-control" id="iegades_datums" name="iegades_datums" lang="lv">
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
            <label for="atbildigais_id" class="form-label">Atbildīgais darbinieks</label>
            <select class="form-control" id="atbildigais_id" name="atbildigais_id">
                <option value="">-- Izvēlieties darbinieku (pēc izvēles) --</option>
                @foreach($lietotaji as $lt)
                    <option value="{{ $lt->lietotajs_id }}">{{ $lt->lietotajvards }} (ID: {{ $lt->lietotajs_id }})</option>
                @endforeach
            </select>
        </div>
        <!-- Saglabā inventāra ierakstu -->
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection