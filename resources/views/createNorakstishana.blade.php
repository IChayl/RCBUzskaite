@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Jauna norakstīšana</p>

    <div class="auth-links">
        <a href="/norakstishana" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Jauna norakstīšana</h2>

    @include('partials.validation-errors')

    <!-- Jauna norakstīšanas izveides forma -->
    <form method="POST" action="{{ route('norakstishana.store') }}" novalidate>
        @csrf
        @php($selectedInventaraId = old('inventara_id', request('inventara_id')))
        <div class="mb-3">
            <label for="inventara_id" class="form-label">Inventars</label>
            <select class="form-control" id="inventara_id" name="inventara_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option value="{{ $inv->inventars_id }}" {{ (string) $selectedInventaraId === (string) $inv->inventars_id ? 'selected' : '' }}>
                        {{ $inv->nosaukums }} (Inv. Nr.: {{ $inv->inventara_numurs ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="norDatums" class="form-label">Norakstīšanas datums</label>
            <input type="date" class="form-control" id="norDatums" name="norDatums" required lang="lv" value="{{ old('norDatums') }}">
        </div>
        <div class="mb-3">
            <label for="iemesls" class="form-label">Iemesls</label>
            <input type="text" class="form-control" id="iemesls" name="iemesls" required maxlength="30" value="{{ old('iemesls') }}">
        </div>
        <div class="mb-3">
            <label for="talaka_riciba" class="form-label">Tālākā rīcība</label>
            <input type="text" class="form-control" id="talaka_riciba" name="talaka_riciba" required maxlength="50" value="{{ old('talaka_riciba') }}">
        </div>
        <!-- Saglabā norakstīšanas ierakstu -->
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection
