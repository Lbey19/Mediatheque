
@extends('layouts.app')

@section('title', 'CDs') {{-- Modifié --}}

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Catalogue CDs</h1> {{-- Modifié --}}
        {{-- Barre de recherche (identique à celle des livres) --}}
        <form action="{{ route('cds.index') }}" method="GET" class="d-flex"> {{-- Modifié --}}
            <input class="form-control me-2" type="search" name="search" placeholder="Rechercher un CD ou un artiste..." aria-label="Search" value="{{ request('search') }}"> {{-- Modifié --}}
            <button class="btn btn-outline-success" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    @if($cds->isEmpty()) {{-- Modifié --}}
        <div class="alert alert-info text-center">
            Aucun CD trouvé correspondant à votre recherche. {{-- Modifié --}}
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($cds as $cd) {{-- Modifié --}}
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm item-card">
                        <a href="{{ route('cds.show', $cd) }}" class="text-decoration-none text-dark"> {{-- Modifié --}}
                            @if ($cd->image_url) {{-- Modifié --}}
                                <img src="{{ $cd->image_url }}" class="card-img-top item-image" alt="Pochette de {{ $cd->titre }}"> {{-- Modifié --}}
                            @else
                                <div class="item-image-placeholder d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-compact-disc fa-3x text-secondary"></i> {{-- Icône CD --}}
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-primary item-title">{{ $cd->titre }}</h5> {{-- Modifié --}}
                                <p class="card-text item-author text-muted small mb-2">{{ $cd->artiste }}</p> {{-- Modifié --}}
                                {{-- Vous pouvez ajouter le genre ou autre info ici si désiré --}}
                                {{-- <span class="badge bg-secondary align-self-start">{{ $cd->genre }}</span> --}}
                                <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                                    <span class="badge {{ $cd->nb_exemplaires > 0 ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }} ">
                                        {{ $cd->nb_exemplaires > 0 ? $cd->nb_exemplaires.' dispo.' : 'Indisponible' }} {{-- Modifié --}}
                                    </span>
                                    <i class="fas fa-chevron-right text-primary"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination Links --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $cds->appends(request()->query())->links() }} {{-- Modifié --}}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Styles repris de livres/index.blade.php - peuvent être factorisés dans app.css */
    .item-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 0.5rem; /* Bords arrondis */
        overflow: hidden; /* Pour que l'image respecte les bords arrondis */
    }
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .item-image {
        height: 250px; /* Hauteur fixe pour l'image */
        object-fit: cover; /* Assure que l'image couvre la zone sans se déformer */
    }
     .item-image-placeholder {
        height: 250px; /* Hauteur fixe pour le placeholder */
        width: 100%;
    }
    .item-title {
        font-size: 1rem; /* Taille de titre légèrement réduite */
        /* Limiter le titre à 2 lignes avec ellipsis */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 2.4em; /* Hauteur minimale pour 2 lignes */
    }
    .item-author {
        font-size: 0.85rem;
    }
    .card-body {
        padding: 0.8rem; /* Padding réduit */
    }
</style>
@endpush