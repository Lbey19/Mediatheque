
@extends('layouts.app')

@section('content')

{{-- Blocs pour afficher les messages session 'success', 'error' et $errors (identiques à livres.show) --}}
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
                        @if ($cd->image_url) {{-- Modifié --}}
                            <img src="{{ $cd->image_url }}" {{-- Modifié --}}
                                 alt="Pochette du CD '{{ $cd->titre }}'" {{-- Modifié --}}
                                 class="img-fluid p-3"
                                 style="max-height: 450px; object-fit: contain;">
                        @else
                            <i class="fas fa-compact-disc fa-4x text-secondary"></i> {{-- Icône CD --}}
                        @endif
                    </div>

                    <!-- Contenu à droite -->
                    <div class="col-md-8 p-4">
                        <h1 class="fw-bold text-primary">{{ $cd->titre }}</h1> {{-- Modifié --}}
                        <p class="text-muted mb-1">{{ $cd->artiste }}</p> {{-- Modifié --}}
                        @if($cd->genre)
                        <span class="badge bg-secondary mb-3">{{ ucfirst($cd->genre) }}</span> {{-- Modifié --}}
                        @endif

                        {{-- Pas de description pour les CDs pour l'instant, vous pouvez en ajouter une si nécessaire --}}
                        {{-- <p class="mb-4">{{ $cd->description }}</p> --}}

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Exemplaires disponibles :</strong>
                                <span>
                                    {{ $cd->nb_exemplaires }} {{-- Modifié --}}
                                    @if($cd->nb_exemplaires > 0) {{-- Modifié --}}
                                        <span class="availability-indicator bg-success ms-2"></span>
                                    @else
                                        <span class="availability-indicator bg-danger ms-2"></span>
                                    @endif
                                </span>
                            </li>
                            @if ($cd->nb_pistes) {{-- Modifié --}}
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Nombre de pistes :</strong> {{ $cd->nb_pistes }} {{-- Modifié --}}
                            </li>
                            @endif
                            @if ($cd->duree) {{-- Modifié --}}
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Durée :</strong> {{ $cd->duree }} {{-- Modifié --}}
                            </li>
                            @endif
                            @if ($cd->date_sortie) {{-- Modifié --}}
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Date de sortie :</strong> {{ \Carbon\Carbon::parse($cd->date_sortie)->format('d/m/Y') }} {{-- Modifié --}}
                            </li>
                            @endif
                            {{-- Vous pouvez ajouter d'autres champs ici si nécessaire (label, etc.) --}}
                        </ul>

                        {{-- Logique d'affichage du bouton Réserver/Messages (similaire à livres.show) --}}
                        @auth
                            @if (isset($aAtteintLimite) && $aAtteintLimite)
                                <div class="alert alert-warning bg-warning-subtle text-dark border border-warning mb-4">
                                    ⚠️ Vous avez déjà atteint la limite de <strong>3 emprunts actifs</strong> (livres et CDs confondus).
                                </div>
                            @elseif ($cd->nb_exemplaires > 0) {{-- Modifié --}}
                                @php
                                    // Vérifier si l'utilisateur a déjà emprunté ce CD spécifique
                                    // Nécessite que la relation Emprunt fonctionne avec les CDs (Étape 6)
                                    $dejaEmprunteCeCd = auth()->user()->emprunts()
                                        ->where('cd_id', $cd->id) // Assurez-vous d'avoir cd_id dans Emprunt
                                        ->whereNull('date_retour_effective')
                                        ->exists();
                                @endphp
                                @if ($dejaEmprunteCeCd)
                                    <div class="alert alert-info bg-info-subtle text-dark border border-info mb-4">
                                        ℹ️ Vous avez déjà emprunté ce CD. Vous pouvez le retrouver dans votre <a href="{{ route('profile.show') }}">profil</a>.
                                    </div>
                                @else
                                    {{-- Le formulaire pointe vers une route 'cds.reserve' qui sera créée plus tard --}}
                                    <form method="POST" action="{{ route('cds.reserve', $cd->id) }}"> {{-- Modifié --}}
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
                                    <i class="fas fa-exclamation-circle me-2"></i> Ce CD n’est pas disponible actuellement. {{-- Modifié --}}
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

                        <a href="{{ route('cds.index') }}" class="btn btn-outline-secondary w-100"> {{-- Modifié --}}
                            <i class="fas fa-arrow-left me-2"></i> Retour à la liste des CDs {{-- Modifié --}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Styles et Scripts (identiques à livres.show) --}}
@push('styles')
<style>
/* Styles repris de livres/show.blade.php - peuvent être factorisés */
.availability-indicator {
    display: inline-block; vertical-align: middle; width: 12px; height: 12px; border-radius: 50%;
    animation: pulse 1.5s infinite ease-in-out; box-shadow: 0 0 5px rgba(0,0,0,0.2);
}
@keyframes pulse { 0% { transform: scale(0.95); opacity: 0.7; } 50% { transform: scale(1); opacity: 1; } 100% { transform: scale(0.95); opacity: 0.7; } }
.btn-hover-scale { transition: transform 0.2s ease; }
.btn-hover-scale:hover { transform: scale(1.03); }
@media (max-width: 767.98px) { img.img-fluid { max-height: 300px; } .card .row > div { padding: 1rem !important; } h1 { font-size: 1.75rem; } }
</style>
@endpush

@push('scripts')
{{-- Si vous utilisez Bootstrap 5 pour les alertes dismissible --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush