@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center mb-5">
        📚 Livres Disponibles
    </h1>

    <div class="container my-4">
        <form action="{{ route('livres.index') }}" method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" name="search" class="form-control w-50" placeholder="Rechercher un livre ou un auteur..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">Rechercher</button>
        </form>
    </div>

    <div class="row justify-content-center">
        @foreach($livres as $livre)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                @if ($livre->image_url)
                    <img src="{{ asset('storage/' . $livre->image) }}" alt="{{ $livre->titre }}" class="img-fluid rounded" style="max-height: 300px;">
                @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $livre->titre }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $livre->auteur }}</h6>
                        <p class="text-muted mb-2">{{ ucfirst($livre->genre) }}</p>
                        <p class="flex-grow-1">{{ Str::limit($livre->description, 80) }}</p>

                        <div class="my-2">
                            @if($livre->nb_exemplaires > 0)
                                <span class="badge bg-success">Disponible</span>
                            @elseif($livre->emprunts()->whereNull('date_retour_effective')->exists())
                                <span class="badge bg-warning text-dark">
                                    Retour prévu : {{ \Carbon\Carbon::parse($livre->emprunts()->whereNull('date_retour_effective')->first()->date_retour_prevue)->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="badge bg-danger">Indisponible</span>
                            @endif
                        </div>

                        <a href="{{ route('livres.show', $livre->id) }}" class="btn btn-primary mt-2">
                            Voir plus
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $livres->links() }}
    </div>
</div>
@endsection