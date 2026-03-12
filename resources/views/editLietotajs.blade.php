@extends('layout.app')

@section('content')
    <p style="color: #ffffff;">Rediģēt lietotāju</p>

    <div class="auth-links">
        <a href="/lietotajs" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #ffffff;">Rediģēt lietotāju</h2>

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
            <label for="avatar" class="form-label">Profila attēls</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
            @if($lietotajs->avatar)
                <img src="{{ asset('storage/' . $lietotajs->avatar) }}" alt="Profila attēls" style="max-width: 120px; margin-top: 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2);">
            @endif
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="admina_tiesibas" name="admina_tiesibas" {{ $lietotajs->admina_tiesibas ? 'checked' : '' }}>
            <label class="form-check-label" for="admina_tiesibas">Admina tiesības</label>
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection