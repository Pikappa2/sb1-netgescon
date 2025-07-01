{{-- Assumendo che tu abbia un layout base come layouts.app --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Elenco Condomini</h1>

    @can('create-condomini')
        <a href="{{ route('condomini.create') }}" class="btn btn-primary mb-3">Nuovo Condominio</a>
    @endcan

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Denominazione</th>
                <th>Indirizzo</th>
                <th>Amministratore</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($condomini as $condominio)
                <tr>
                    <td>{{ $condominio->denominazione }}</td>
                    <td>{{ $condominio->indirizzo }}, {{ $condominio->cap }} {{ $condominio->citta }} ({{ $condominio->provincia }})</td>
                    <td>{{ $condominio->amministratore->user->name ?? 'N/A' }}</td>
                    <td>
                        @can('view-condomini')
                            <a href="{{ route('condomini.show', $condominio->id_condominio) }}" class="btn btn-info btn-sm">Vedi</a>
                        @endcan
                        @can('edit-condomini')
                            <a href="{{ route('condomini.edit', $condominio->id_condominio) }}" class="btn btn-warning btn-sm">Modifica</a>
                        @endcan
                        @can('delete-condomini')
                            <form action="{{ route('condomini.destroy', $condominio->id_condominio) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro?')">Elimina</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nessun condominio trovato.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {!! $condomini->links() !!}
</div>
@endsection
