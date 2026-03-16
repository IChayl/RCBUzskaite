@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Jauns lietotājs</p>

    <div class="auth-links">
        <a href="/lietotajs" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Jauns lietotājs</h2>

    @include('partials.validation-errors')

    <!-- Jauna lietotāja izveides forma -->
    <form method="POST" action="{{ route('lietotajs.store') }}" enctype="multipart/form-data" novalidate>
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
            <label for="vards" class="form-label">Vārds</label>
            <input type="text" class="form-control" id="vards" name="vards">
        </div>
        <div class="mb-3">
            <label for="uzvards" class="form-label">Uzvārds</label>
            <input type="text" class="form-control" id="uzvards" name="uzvards">
        </div>
        <div class="mb-3">
            <label for="epasts" class="form-label">E-pasts</label>
            <input type="email" class="form-control" id="epasts" name="epasts">
        </div>
        <div class="mb-3">
            <label for="telefons" class="form-label">Telefons</label>
            <input type="text" class="form-control" id="telefons" name="telefons" inputmode="numeric" pattern="[0-9]+" maxlength="20">
        </div>
                   <div class="mb-3" style="margin-bottom:16px;">
            <label style="font-weight:700; display:block; margin-bottom:8px;">Amats:</label>
                <select name="amats" style="width:90%; padding:10px; border-radius:6px;">
                    <option value="">-- izvēlēties --</option>
                    <option value="Direktors" {{ (old('amats', $lietotajs->amats ?? '')=='Direktors') ? 'selected' : '' }}>Direktors</option>
                    <option value="Dir.Vietnieks" {{ (old('amats', $lietotajs->amats ?? '')=='Dir.Vietnieks') ? 'selected' : '' }}>Dir.Vietnieks</option>
                    <option value="Vecākais bibliotekārs" {{ (old('amats', $lietotajs->amats ?? '')=='Vecākais bibliotekārs') ? 'selected' : '' }}>Vecākais bibliotekārs</option>
                    <option value="Bibliotekārs" {{ (old('amats', $lietotajs->amats ?? '')=='Bibliotekārs') ? 'selected' : '' }}>Bibliotekārs</option>
                </select>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="aktivs" name="aktivs" checked>
            <label class="form-check-label" for="aktivs">Aktīvs</label>
        </div>
        <div class="mb-3">
            <label for="avatar" class="form-label">Profila attēls</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="admina_tiesibas" name="admina_tiesibas">
            <label class="form-check-label" for="admina_tiesibas">Admina tiesības</label>
        </div>
        <!-- Saglabā lietotāja ierakstu -->
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection