@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifica Condominio: {{ $condominio->denominazione }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('condomini.update', $condominio->id_condominio) }}" method="POST">
        @csrf
        @method('PUT')
        @include('condomini._form')
        <button type="submit" class="btn btn-primary">Aggiorna</button>
    </form>
</div>
@endsection
