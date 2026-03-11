@extends('layout.app')

@section('content')
    <h2>Jauns lietotājs</h2>
    <a href="/lietotajs" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    <hr>

    <form method="POST" action="{{ route('lietotaji.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="lietotajvards" class="form-label">Lietotājvārds</label>
            <input type="text" class="form-control" id="lietotajvards" name="lietotajvards" required>
        </div>
        <div class="mb-3">
            <label for="parole" class="form-label">Parole</label>
            <input type="password" class="form-control" id="parole" name="parole" required>
        </div>
        <div class="mb-3">
            <label for="avatar" class="form-label">Profila attēls</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="admina_tiesibas" name="admina_tiesibas">
            <label class="form-check-label" for="admina_tiesibas">Admina tiesības</label>
        </div>
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection