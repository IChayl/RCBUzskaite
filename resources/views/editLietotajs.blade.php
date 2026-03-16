@extends('layout.app')

@section('content')
    <p style="color: #FAF8F2;">Rediģēt lietotāju</p>

    <div class="auth-links">
        <a href="/lietotajs" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #FAF8F2;">Rediģēt lietotāju</h2>

    <form method="POST" action="/lietotajs/{{ $lietotajs->lietotajs_id }}/editSubmit" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="lietotajvards" class="form-label">Lietotājvārds</label>
            <input type="text" class="form-control" id="lietotajvards" name="lietotajvards" value="{{ $lietotajs->lietotajvards }}" required>
        </div>
        <div class="mb-3">
            <label for="parole" class="form-label">Parole</label>
            <input type="password" class="form-control" id="parole" name="parole" value="{{ $lietotajs->parole }}" required>
        </div>
        <div class="mb-3">
            <label for="vards" class="form-label">Vārds</label>
            <input type="text" class="form-control" id="vards" name="vards" value="{{ $lietotajs->vards }}">
        </div>
        <div class="mb-3">
            <label for="uzvards" class="form-label">Uzvārds</label>
            <input type="text" class="form-control" id="uzvards" name="uzvards" value="{{ $lietotajs->uzvards }}">
        </div>
        <div class="mb-3">
            <label for="epasts" class="form-label">E-pasts</label>
            <input type="email" class="form-control" id="epasts" name="epasts" value="{{ $lietotajs->epasts }}">
        </div>
        <div class="mb-3">
            <label for="telefons" class="form-label">Telefons</label>
            <input type="text" class="form-control" id="telefons" name="telefons" value="{{ $lietotajs->telefons }}">
        </div>
        <div class="mb-3">
            <label for="amats" class="form-label">Amats</label>
            <input type="text" class="form-control" id="amats" name="amats" value="{{ $lietotajs->amats }}">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="aktivs" name="aktivs" {{ $lietotajs->aktivs ? 'checked' : '' }}>
            <label class="form-check-label" for="aktivs">Aktīvs</label>
        </div>
        <div class="mb-3">
            <label for="avatar" class="form-label">Profila attēls</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
            @if($lietotajs->avatar)
                <img src="{{ asset('storage/' . $lietotajs->avatar) }}" alt="Profila attēls" style="max-width: 120px; margin-top: 10px; border-radius: 12px; border: 1px solid rgba(209, 195, 165, 0.2);">
            @endif
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="admina_tiesibas" name="admina_tiesibas" {{ $lietotajs->admina_tiesibas ? 'checked' : '' }}>
            <label class="form-check-label" for="admina_tiesibas">Admina tiesības</label>
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection