@extends('layout.app')

@section('content')
    <h2>Rediģēt lietotāju</h2>
    <a href="/lietotajs" class="btn btn-secondary">Atpakaļ</a>
    <hr>

    <form method="POST" action="/lietotajs/{{ $lietotajs->lietotajs_id }}/editSubmit">
        @csrf
        <div class="mb-3">
            <label for="lietotajvards" class="form-label">Lietotājvārds</label>
            <input type="text" class="form-control" id="lietotajvards" name="lietotajvards" value="{{ $lietotajs->lietotajvards }}" required>
        </div>
        <div class="mb-3">
            <label for="parole" class="form-label">Parole</label>
            <input type="password" class="form-control" id="parole" name="parole" value="{{ $lietotajs->parole }}" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="admina_tiesibas" name="admina_tiesibas" {{ $lietotajs->admina_tiesibas ? 'checked' : '' }}>
            <label class="form-check-label" for="admina_tiesibas">Admina tiesības</label>
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection