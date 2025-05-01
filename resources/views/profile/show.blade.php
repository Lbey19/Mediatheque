
@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- ... En-tête du profil et Section Informations (inchangés) ... --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 bg-white rounded-xl shadow-lg p-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-800">Bonjour, {{ $user->prenom ?? $user->name }}</h1>
            <p class="text-gray-600">Membre depuis le {{ $user->created_at->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-all text-sm font-medium">
            <i class="fas fa-user-edit mr-2"></i>Modifier le profil
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i>Informations du compte
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center">
                <i class="fas fa-user text-gray-400 mr-3 fa-fw"></i>
                <div>
                    <p class="text-sm text-gray-500">Nom complet</p>
                    <p class="font-medium">{{ $user->prenom }} {{ $user->name }}</p>
                </div>
            </div>
            <div class="flex items-center">
                <i class="fas fa-envelope text-gray-400 mr-3 fa-fw"></i>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex items-center">
                <i class="fas fa-calendar-alt text-gray-400 mr-3 fa-fw"></i>
                <div>
                    <p class="text-sm text-gray-500">Date d'inscription</p>
                    <p class="font-medium">{{ $user->date_inscription ? \Carbon\Carbon::parse($user->date_inscription)->format('d/m/Y') : $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
             <div class="flex items-center">
                <i class="fas fa-calendar-times text-gray-400 mr-3 fa-fw"></i>
                <div>
                    <p class="text-sm text-gray-500">Expiration adhésion</p>
                    <p class="font-medium">{{ $user->date_expiration ? \Carbon\Carbon::parse($user->date_expiration)->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>
            {{-- Ajoutez d'autres champs si nécessaire --}}
        </div>
    </div>

    <!-- Section Emprunts en cours -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">
            <i class="fas fa-book-reader mr-2 text-green-500"></i>Emprunts en cours {{-- Icône modifiée --}}
            {{-- Assurez-vous que $empruntsActifs est passé par le contrôleur --}}
            <span class="text-sm font-normal text-gray-500">({{ $empruntsActifs->count() }})</span> {{-- Variable renommée pour clarté --}}
        </h2>

        {{-- Utiliser la variable correcte : $empruntsActifs --}}
        @if($empruntsActifs->count())
            <div class="space-y-4">
                 {{-- Utiliser la variable correcte : $empruntsActifs --}}
                @foreach($empruntsActifs as $emprunt)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <div class="mb-2 sm:mb-0 flex items-center"> {{-- Ajout de flex et items-center --}}
                                {{-- --- MODIFICATION ICI --- --}}
                                @if ($emprunt->livre_id && $emprunt->livre) {{-- Si c'est un livre --}}
                                    <i class="fas fa-book text-blue-500 mr-3 fa-fw text-xl"></i> {{-- Icône Livre --}}
                                    <div>
                                        <h3 class="font-medium text-lg">{{ $emprunt->livre->titre }}</h3>
                                        <p class="text-sm text-gray-500">de {{ $emprunt->livre->auteur }}</p>
                                    </div>
                                @elseif ($emprunt->cd_id && $emprunt->cd) {{-- Si c'est un CD --}}
                                    <i class="fas fa-compact-disc text-purple-500 mr-3 fa-fw text-xl"></i> {{-- Icône CD --}}
                                    <div>
                                        <h3 class="font-medium text-lg">{{ $emprunt->cd->titre }}</h3>
                                        <p class="text-sm text-gray-500">par {{ $emprunt->cd->artiste }}</p>
                                    </div>
                                @else
                                    <i class="fas fa-question-circle text-gray-400 mr-3 fa-fw text-xl"></i> {{-- Icône Inconnu --}}
                                    <div>
                                        <h3 class="font-medium text-lg">Article inconnu</h3>
                                        <p class="text-sm text-gray-500">ID Emprunt: {{ $emprunt->id }}</p>
                                    </div>
                                @endif
                                {{-- --- FIN MODIFICATION --- --}}
                            </div>
                            <div class="flex items-center space-x-4 text-sm">
                                {{-- Affichage du statut (suppose que $emprunt->status_color et $emprunt->status_label existent) --}}
                                {{-- Vous devrez peut-être ajouter ces accesseurs au modèle Emprunt --}}
                                {{-- Exemple simple : --}}
                                @php
                                    $isOverdue = !$emprunt->date_retour_effective && $emprunt->date_retour_prevue->isPast();
                                    $statusLabel = $isOverdue ? 'En retard' : 'En cours';
                                    $statusColor = $isOverdue ? 'red' : 'green';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                    {{ $statusLabel }}
                                </span>
                                <div class="text-right">
                                    <p><span class="text-gray-500">Emprunté le :</span> {{ $emprunt->date_emprunt->format('d/m/Y') }}</p>
                                    <p><span class="text-gray-500">À rendre le :</span> {{ $emprunt->date_retour_prevue->format('d/m/Y') }}</p>
                                    @if($isOverdue)
                                        <p class="text-red-600 font-semibold">Retard !</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6">
                <i class="fas fa-check-circle text-4xl text-green-400 mb-2"></i>
                <p class="text-gray-500">Aucun emprunt en cours actuellement.</p>
            </div>
        @endif
    </div>

    <!-- Section Historique des emprunts -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">
            <i class="fas fa-history mr-2 text-purple-500"></i>Historique des emprunts
             {{-- Assurez-vous que $historiqueEmprunts est passé par le contrôleur --}}
            <span class="text-sm font-normal text-gray-500">({{ $historiqueEmprunts->count() }})</span> {{-- Variable renommée pour clarté --}}
        </h2>

         {{-- Utiliser la variable correcte : $historiqueEmprunts --}}
        @if($historiqueEmprunts->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                     <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Article</th> {{-- Colonne renommée --}}
                            {{-- <th class="py-3 px-4">Statut</th> --}} {{-- Statut implicite (rendu) --}}
                            <th class="py-3 px-4">Dates</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                         {{-- Utiliser la variable correcte : $historiqueEmprunts --}}
                        @foreach($historiqueEmprunts as $emprunt)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">
                                {{-- --- MODIFICATION ICI --- --}}
                                @if ($emprunt->livre_id && $emprunt->livre) {{-- Si c'est un livre --}}
                                    <div class="flex items-center">
                                        <i class="fas fa-book text-blue-500 mr-2 fa-fw"></i>
                                        <div>
                                            <p class="font-medium">{{ $emprunt->livre->titre }}</p>
                                            <p class="text-sm text-gray-500">de {{ $emprunt->livre->auteur }}</p>
                                        </div>
                                    </div>
                                @elseif ($emprunt->cd_id && $emprunt->cd) {{-- Si c'est un CD --}}
                                     <div class="flex items-center">
                                        <i class="fas fa-compact-disc text-purple-500 mr-2 fa-fw"></i>
                                        <div>
                                            <p class="font-medium">{{ $emprunt->cd->titre }}</p>
                                            <p class="text-sm text-gray-500">par {{ $emprunt->cd->artiste }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <i class="fas fa-question-circle text-gray-400 mr-2 fa-fw"></i>
                                        <div>
                                            <p class="font-medium">Article inconnu</p>
                                            <p class="text-sm text-gray-500">ID Emprunt: {{ $emprunt->id }}</p>
                                        </div>
                                    </div>
                                @endif
                                {{-- --- FIN MODIFICATION --- --}}
                            </td>
                            {{-- Statut implicite "Rendu" pour l'historique --}}
                            {{-- <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    Rendu
                                </span>
                            </td> --}}
                            <td class="py-3 px-4 text-sm">
                                <p><span class="text-gray-500">Emprunt :</span> {{ $emprunt->date_emprunt->format('d/m/Y') }}</p>
                                <p><span class="text-gray-500">Retour :</span> {{ $emprunt->date_retour_effective?->format('d/m/Y') ?? '-' }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                 {{-- Ajouter la pagination si $historiqueEmprunts est paginé --}}
                 <div class="mt-4">
                    {{ $historiqueEmprunts->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-6">
                 <i class="fas fa-folder-open text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Aucun emprunt dans l'historique.</p>
            </div>
        @endif
    </div>
</div>
@endsection