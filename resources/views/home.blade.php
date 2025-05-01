@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="text-center py-5 bg-light">
    <h1 class="display-4 mb-3">Médiathèque de Montpellier 📚</h1>
    <p class="lead mb-4">23 087 prêts annuels • 7 527 documents • 1 772 inscrits</p>

    <!-- Boutons d'accès rapide -->
    <div class="d-flex justify-content-center gap-4 flex-wrap mb-5">
        <a href="{{ route('livres.index') }}" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-book-open me-2"></i> Livres (5 500)
        </a>

        <a href="{{ route('cds.index') }}" class="btn btn-success btn-lg px-5">
            <i class="fas fa-compact-disc me-2"></i> CD (712)
        </a>

        <a href="{{ route('dvds.index') }}" class="btn btn-warning btn-lg px-5">
            <i class="fas fa-film me-2"></i> DVD/Blu-Ray (1 266)
        </a>
    </div>

    <!-- Informations utiles -->
    <div class="row g-4 justify-content-center">
        <div class="col-md-5">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-clock me-2"></i>Horaires</h5>
                    <ul class="list-unstyled">
                        <li>Mardi–Jeudi : 10h–18h</li>
                        <li>Vendredi : 14h–18h</li>
                        <li>Samedi : 10h–18h</li>
                        <li class="text-muted">Fermé lundi et dimanche</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-euro-sign me-2"></i>Abonnements</h5>
                    <ul class="list-unstyled">
                        <li>Gratuit pour les résidents &lt; 18 ans</li>
                        <li>Annuel : 22€ • Mensuel : 10,50€</li>
                    </ul>
                    @guest
                        @auth
                            <!-- L’utilisateur est connecté, rien à afficher ici -->
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary mt-2">
                                    S'inscrire maintenant
                                </a>
                            @endif
                        @endauth
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services -->
<div class="row text-center mt-5 g-4">
    <div class="col-md-4">
        <div class="p-4 h-100 shadow-sm bg-white rounded">
            <i class="fas fa-bell fa-2x text-danger mb-3"></i>
            <h4>Gestion des rappels</h4>
            <p>Recevez des notifications en cas de retard</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="p-4 h-100 shadow-sm bg-white rounded">
            <i class="fas fa-sync-alt fa-2x text-success mb-3"></i>
            <h4>Renouvellement en ligne</h4>
            <p>Prolongez vos prêts sans vous déplacer</p>
        </div>
    </div>

    <!-- Espace personnel -->
    <div class="col-md-4">
        <div class="p-4 h-100 shadow-sm bg-white rounded">
            <i class="fas fa-user fa-2x text-info mb-3"></i>
            <h4>Espace personnel</h4>
            @auth
            <a href="{{ route('profile') }}" class="btn btn-primary btn-sm mt-2"> 
            Accéder à mon compte
                </a>
            @else
                <p>Connectez-vous pour gérer vos emprunts</p>
            @endauth
        </div>
    </div>
</div>

<!-- Accès employé -->
@auth
@if(auth()->user()->isAdmin())
    <div class="alert alert-info mt-5 text-center">
        <h5>Accès employé</h5>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            {{-- 1. Lien vers la gestion des prêts --}}
            <a href="{{ \App\Filament\Resources\EmpruntResource::getUrl('index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-exchange-alt me-1"></i> Gérer les prêts
            </a>
            {{-- 2. Lien vers la gestion des utilisateurs/abonnements --}}
            <a href="{{ \App\Filament\Resources\UserResource::getUrl('index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-users me-1"></i> Gérer les Adhérents
            </a>
            {{-- 3. Lien vers la gestion des livres (comme point d'entrée pour le contenu) --}}
            <a href="{{ \App\Filament\Resources\LivreResource::getUrl('index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-book me-1"></i> Gérer les Livres
            </a>
        </div>
    </div>
@endif
@endauth
@endsection
