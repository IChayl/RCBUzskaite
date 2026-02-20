@extends('layout.app')

@section('content')
    <h2>Rediģēt atrašanās vietu</h2>
    <a href="/atrasanas_vieta" class="btn btn-secondary">Atpakaļ</a>
    <hr>

    <form method="POST" action="/atrasanas_vieta/{{ $vieta->atrasanas_vieta_id }}/editSubmit">
        @csrf
        <div class="mb-3">
            <label for="nodala" class="form-label">Nodaļa</label>
            <input type="text" class="form-control" id="nodala" name="nodala" value="{{ $vieta->nodala }}" required>
        </div>
        <div class="mb-3">
            <label for="telpas_id" class="form-label">Telpas ID</label>
            <input type="number" class="form-control" id="telpas_id" name="telpas_id" value="{{ $vieta->telpas_id }}">
        </div>
        <div class="mb-3">
            <label for="stavs" class="form-label">Stāvoklis</label>
            <input type="number" class="form-control" id="stavs" name="stavs" value="{{ $vieta->stavs }}">
        </div>
        <button type="submit" class="btn btn-primary">Atjaunināt</button>
    </form>
@endsection