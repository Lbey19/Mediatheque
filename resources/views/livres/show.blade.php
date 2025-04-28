@extends('layouts.app') <!-- si tu as un layout général sinon mets directement le code -->

@section('content')
<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width: 700px;">
        @if ($livre->image_url)
            <img src="{{ asset('storage/' . $livre->image) }}" alt="{{ $livre->titre }}" class="img-fluid rounded" style="max-height: 300px;">
        @endif
        <div class="card-body">
            <h1 class="card-title text-center mb-4">{{ $livre->titre }}</h1>

            <h5 class="text-muted mb-2">Auteur : <span class="text-dark">{{ $livre->auteur }}</span></h5>
            <h6 class="text-muted mb-2">Genre : <span class="text-dark">{{ ucfirst($livre->genre) }}</span></h6>
            <p class="mt-3">{{ $livre->description }}</p>

            <div class="mt-4">
                @if ($livre->nb_exemplaires > 0)
                    <form action="{{ route('livres.reserve', $livre) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Réserver ce livre</button>
                    </form>
                @else
                    <button class="btn btn-secondary" disabled>Non disponible</button>
                @endif
            </div>

            <div class="my-4">
                @if($livre->nb_exemplaires > 0)
                    <span class="badge bg-success fs-6">Disponible</span>
                @elseif($livre->emprunts()->whereNull('date_retour_effective')->exists())
                    <span class="badge bg-warning text-dark fs-6">
                        Retour prévu : {{ \Carbon\Carbon::parse($livre->emprunts()->whereNull('date_retour_effective')->first()->date_retour_prevue)->format('d/m/Y') }}
                    </span>
                @else
                    <span class="badge bg-danger fs-6">Indisponible</span>
                @endif
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('livres.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection