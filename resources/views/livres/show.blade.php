
@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow rounded overflow-hidden">
                <div class="row g-0">
                    <!-- Image à gauche -->
                    <div class="col-md-4 bg-light d-flex align-items-center justify-content-center">
                        @if ($livre->image)
                            <img src="{{ asset('storage/' . $livre->image) }}"
                                 alt="Couverture du livre '{{ $livre->titre }}'"
                                 class="img-fluid p-3"
                                 style="max-height: 450px; object-fit: contain;">
                        @else
                            <i class="fas fa-book-open fa-4x text-secondary"></i> {{-- Placeholder icon --}}
                        @endif
                    </div>

                    <!-- Contenu à droite -->
                    <div class="col-md-8 p-4">
                        <h1 class="fw-bold text-primary">{{ $livre->titre }}</h1>
                        <p class="text-muted mb-1">{{ $livre->auteur }}</p>
                        <span class="badge bg-secondary mb-3">{{ ucfirst($livre->genre) }}</span>

                        <p class="mb-4">{{ $livre->description }}</p>

                        <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <strong>Exemplaires disponibles :</strong>
                                    <span>
                                        {{ $livre->nb_exemplaires }}
                                        @if($livre->disponible) {{-- Utilise le champ booléen 'disponible' pour l'indicateur --}}
                                            <span class="availability-indicator bg-success ms-2" title="Disponible"></span>
                                        @else
                                            <span class="availability-indicator bg-danger ms-2" title="Indisponible"></span>
                                        @endif
                                    </span>
                                </li>
                                @if ($livre->isbn)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <strong>ISBN :</strong> <span>{{ $livre->isbn }}</span>
                                </li>
                                @endif
                                @if ($livre->nombre_pages) {{-- Ajout d'un @if pour les pages --}}
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <strong>Pages :</strong> <span>{{ $livre->nombre_pages }}</span>
                                </li>
                                @endif
                                {{-- La ligne 'Format' est supprimée ou commentée car la colonne n'existe pas --}}
                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <strong>Format :</strong> <span>Broché</span>
                                </li> --}}
                                @if ($livre->edition) {{-- Vérifie et utilise la colonne 'edition' --}}
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <strong>Éditeur :</strong> <span>{{ $livre->edition }}</span> {{-- Utilise la colonne 'edition' --}}
                                </li>
                            @endif
                        </ul>
                        @auth
                            @if (isset($aAtteintLimite) && $aAtteintLimite)
                                <div class="alert alert-warning bg-warning-subtle text-dark border border-warning mb-4">
                                    ⚠️ Vous avez déjà atteint la limite de <strong>3 emprunts actifs</strong>. Veuillez retourner un livre avant d’en réserver un autre.
                                </div>
                            @elseif ($livre->nb_exemplaires > 0)
                                {{-- Vérifier si déjà emprunté (à adapter si la variable $dejaEmprunte est passée depuis le contrôleur) --}}
                                @php
                                    // On recalcule ici si l'utilisateur a déjà ce livre spécifique
                                    $dejaEmprunteCeLivre = auth()->user()->emprunts()
                                        ->where('livre_id', $livre->id)
                                        ->whereNull('date_retour_effective')
                                        ->exists();
                                @endphp
                                @if ($dejaEmprunteCeLivre)
                                    <div class="alert alert-info bg-info-subtle text-dark border border-info mb-4">
                                        ℹ️ Vous avez déjà emprunté ce livre. Vous pouvez le retrouver dans votre <a href="{{ route('profile') }}">profil</a>.
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('livres.reserve', $livre->id) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="duree" class="form-label">Durée d’emprunt (en jours)</label>
                                            <input type="number" name="duree" id="duree" class="form-control" min="1" max="21" value="7" required>
                                            <small class="form-text text-muted">Maximum : 21 jours</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 mb-3 btn-hover-scale" onclick="this.disabled=true; this.innerText='Réservation...'; this.form.submit();">
                                            <i class="fas fa-bookmark me-2"></i> Réserver
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="alert alert-danger bg-danger-subtle text-dark border border-danger mb-4">
                                    <i class="fas fa-exclamation-circle me-2"></i> Ce livre n’est pas disponible actuellement.
                                    @if($prochainRetour)
                                        <br><small>Prochain retour prévu le : {{ \Carbon\Carbon::parse($prochainRetour->date_retour_prevue)->format('d/m/Y') }}</small>
                                    @endif
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning w-100 mb-3 btn-hover-scale">
                                <i class="fas fa-sign-in-alt me-2"></i> Connectez-vous pour réserver
                            </a>
                        @endauth

                        <a href="{{ route('livres.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Styles existants */
.detail-card {
    max-width: 1200px; /* Ajusté pour cohérence */
    border-radius: 15px; /* Ajusté pour cohérence */
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05); /* Ombre plus douce */
}

.book-cover {
    width: 100%;
    height: 100%; /* Prend toute la hauteur de la colonne */
    max-height: 450px; /* Limite la hauteur max */
    object-fit: contain; /* Assure que l'image entière est visible */
    padding: 1rem; /* Ajoute un peu d'espace autour */
}

.placeholder-cover {
    height: 450px; /* Hauteur cohérente */
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef; /* Fond clair pour placeholder */
}

.author-genre { /* Cette classe n'est pas utilisée dans le HTML actuel, mais conservée */
    margin: 1rem 0; /* Ajusté */
}

.genre-badge { /* Cette classe n'est pas utilisée dans le HTML actuel, mais conservée */
    font-size: 0.85rem; /* Ajusté */
    background-color: #e9ecef; /* Fond clair */
    color: #495057; /* Texte plus foncé */
    padding: 0.4rem 0.8rem; /* Ajusté */
    border-radius: 15px; /* Ajusté */
    border: 1px solid #dee2e6; /* Bordure légère */
}

.detail-item { /* Cette classe n'est pas utilisée dans le HTML actuel, mais conservée */
    background: #ffffff;
    border-radius: 10px; /* Ajusté */
    padding: 1.5rem; /* Ajusté */
    border: 1px solid #f1f3f5; /* Bordure très légère */
    transition: transform 0.2s ease, box-shadow 0.2s ease; /* Transition plus rapide */
}

.detail-item:hover { /* Cette classe n'est pas utilisée dans le HTML actuel, mais conservée */
    transform: translateY(-3px); /* Effet de survol subtil */
    box-shadow: 0 6px 15px rgba(0,0,0,0.06); /* Ombre subtile */
}

.detail-item h3 { /* Cette classe n'est pas utilisée dans le HTML actuel, mais conservée */
    color: #212529; /* Texte noir standard */
    border-bottom: 2px solid #0d6efd; /* Bordure bleue Bootstrap */
    padding-bottom: 0.5rem; /* Ajusté */
    margin-bottom: 1rem; /* Ajusté */
    font-weight: 600;
    font-size: 1.1rem; /* Ajusté */
}

/* Styles pour l'indicateur de disponibilité */
.availability-indicator {
    display: inline-block; /* Pour être sur la même ligne que le texte */
    vertical-align: middle; /* Alignement vertical */
    width: 12px;  /* Taille réduite */
    height: 12px; /* Taille réduite */
    border-radius: 50%;
    animation: pulse 1.5s infinite ease-in-out; /* Animation plus douce */
    box-shadow: 0 0 5px rgba(0,0,0,0.2); /* Légère ombre pour la visibilité */
}

/* Animation Pulse */
@keyframes pulse {
    0% {
        transform: scale(0.95);
        opacity: 0.7;
    }
    50% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(0.95);
        opacity: 0.7;
    }
}

/* Styles pour les boutons d'action */
.action-buttons .btn-lg { /* Cette classe n'est pas utilisée dans le HTML actuel, mais conservée */
    padding: 0.8rem 1.5rem; /* Padding standard pour btn-lg */
    border-radius: 8px; /* Rayon standard */
    font-size: 1rem; /* Taille standard */
    letter-spacing: normal; /* Espacement normal */
}

.btn-hover-scale {
    transition: transform 0.2s ease;
}

.btn-hover-scale:hover {
    transform: scale(1.03); /* Effet de zoom léger */
}

/* Responsive adjustments */
@media (max-width: 767.98px) { /* Changé pour md breakpoint */
    .book-cover, .placeholder-cover {
        max-height: 300px; /* Hauteur réduite sur mobile */
    }
    .card .row > div {
        padding: 1rem !important; /* Réduire le padding sur mobile */
    }
    h1 {
        font-size: 1.75rem; /* Taille de titre réduite */
    }
}

</style>
@endpush

@push('scripts')
{{-- Si vous utilisez Bootstrap 5 pour les alertes dismissible --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
