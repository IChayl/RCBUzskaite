@extends('layout.app')

@section('content')
    <p style="color: #E2D4BB;">Jauna telpa</p>

    <div class="auth-links">
        <a href="/telpa" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    </div>

    <hr>

    <h2 style="color: #E2D4BB;">Jauna telpa</h2>

    @include('partials.validation-errors')

    <!-- Forma jaunas telpas pievienošanai -->
    <form method="POST" action="{{ route('telpa.store') }}" novalidate>
        @csrf
        <div class="mb-3">
            <label for="nosaukums" class="form-label">Nosaukums</label>
            <input type="text" class="form-control" id="nosaukums" name="nosaukums" required>
        </div>
        <div class="mb-3">
            <label for="platiba" class="form-label">Platība</label>
            <input type="text" class="form-control" id="platiba" name="platiba">
        </div>
        <div class="mb-3">
            <label for="numurs" class="form-label">Numurs</label>
            <input type="number" class="form-control" id="numurs" name="numurs">
        </div>
        <!-- Saglabā telpas ierakstu -->
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection
