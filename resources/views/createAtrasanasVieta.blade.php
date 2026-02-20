@extends('layout.app')

@section('content')
    <h2>Jauna atrašanās vieta</h2>
    <a href="/atrasanas_vieta" class="btn btn-secondary">Atpakaļ uz sarakstu</a>
    <hr>

    <form method="POST" action="{{ route('atrasanas_vieta.store') }}">
        @csrf
        <div class="mb-3">
            <label for="nodala" class="form-label">Nodaļa</label>
            <input type="text" class="form-control" id="nodala" name="nodala" required>
        </div>
        <div class="mb-3">
            <label for="telpas_id" class="form-label">Telpas ID</label>
            <input type="number" class="form-control" id="telpas_id" name="telpas_id">
        </div>
        <div class="mb-3">
            <label for="stavs" class="form-label">Stāvoklis</label>
            <input type="number" class="form-control" id="stavs" name="stavs">
        </div>
        <button type="submit" class="btn btn-primary">Saglabāt</button>
    </form>
@endsection