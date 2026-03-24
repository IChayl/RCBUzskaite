@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Rediģēt norakstīšanu</p>

    <div class="auth-links">
        <a href="/norakstishana" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Rediģēt norakstīšanu</h2>

    @include('partials.validation-errors')

    <!-- Norakstīšanas rediģēšanas forma -->
    <form method="POST" action="/norakstishana/{{ $norakstishana->norakstishana_id }}/editSubmit" novalidate>
        @csrf
        <div class="mb-3">
            <label for="inventara_id" class="form-label">Inventars</label>
            <select class="form-control" id="inventara_id" name="inventara_id" required>
                <option value="">-- Izvēlieties inventāru --</option>
                @foreach($inventari as $inv)
                    <option value="{{ $inv->inventars_id }}" @if($norakstishana->inventara_id == $inv->inventars_id) selected @endif>
                        {{ $inv->nosaukums }} (Inv. Nr.: {{ $inv->inventara_numurs ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="norDatums" class="form-label">Norakstīšanas datums</label>
            <input type="date" class="form-control" id="norDatums" name="norDatums" value="{{ $norakstishana->norDatums }}" required lang="lv">
        </div>
        <div class="mb-3">
            <label for="iemesls" class="form-label">Iemesls</label>
            <input type="text" class="form-control" id="iemesls" name="iemesls" value="{{ $norakstishana->iemesls }}" required maxlength="30">
        </div>
        <div class="mb-3">
            <label for="talaka_riciba" class="form-label">Tālākā rīcība</label>
            <input type="text" class="form-control" id="talaka_riciba" name="talaka_riciba" value="{{ $norakstishana->talaka_riciba }}" required maxlength="50">
        </div>
        <!-- Saglabā atjauninātos norakstīšanas datus -->
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection
