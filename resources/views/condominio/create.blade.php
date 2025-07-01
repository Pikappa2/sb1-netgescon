@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nuovo Condominio</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('condomini.store') }}" method="POST">
        @csrf
        @include('condomini._form', ['condominio' => new App\Models\Condominio()])
        <button type="submit" class="btn btn-primary">Salva</button>
    </form>
</div>
@endsection
