<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Emprunt;
use Illuminate\Http\Request; // Ajout du use pour Request
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LivreController extends Controller
{
    public function index(Request $request)
    {
        $query = Livre::query()->where('disponible', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        $livres = $query->paginate(6);

        return view('livres.index', compact('livres'));
    }

    public function show(Livre $livre)
    {
        $prochainRetour = null;
        $user = auth()->user();
        $aAtteintLimite = false;

        if (auth()->check()) {
            $aAtteintLimite = $user->emprunts()
                ->whereNull('date_retour_effective')
                ->count() >= 3;
        }

        if (!$livre->disponible) {
            $prochainRetour = Emprunt::where('livre_id', $livre->id)
                ->whereNull('date_retour_effective')
                ->orderBy('date_retour_prevue', 'asc')
                ->first();

        }

        return view('livres.show', compact('livre', 'prochainRetour', 'aAtteintLimite'));
    }

    public function reserve(Request $request, Livre $livre)
    {
        $user = auth()->user();
        $now = Carbon::now(); // Obtenir la date actuelle

        // --- AJOUT : Vérification des retards ---
        $empruntsEnRetard = $user->emprunts()
                                ->whereNull('date_retour_effective')
                                ->where('date_retour_prevue', '<', Carbon::today()) // Vérifie si la date prévue est passée
                                ->exists(); // exists() est plus performant si on a juste besoin de savoir s'il y en a au moins un

        if ($empruntsEnRetard) {
            return back()->with('error', 'Vous ne pouvez pas emprunter de nouveaux articles car vous avez des retours en retard.');
        }
        // --- FIN AJOUT ---

        // --- NOUVELLE VÉRIFICATION ---
        // Vérifier si l'abonnement de l'utilisateur est actif
        if (!$user->date_expiration || Carbon::parse($user->date_expiration)->isPast()) {
            // Rediriger avec un message d'erreur si l'abonnement est expiré ou non défini
            return back()->with('error', 'Votre abonnement est expiré ou invalide. Vous ne pouvez pas réserver de livre.');
        }

        // Vérifie stock
        if ($livre->nb_exemplaires <= 0) {
            return back()->with('error', 'Ce livre n\'est plus disponible.');
        }

        // Vérifie emprunt identique
        $dejaEmprunte = $user->emprunts()
            ->where('livre_id', $livre->id)
            ->whereNull('date_retour_effective')
            ->exists();

        if ($dejaEmprunte) {
            return back()->with('error', 'Vous avez déjà emprunté ce livre.');
        }

        // Vérifie limite max de 3 emprunts actifs
        if ($user->emprunts()->whereNull('date_retour_effective')->count() >= 3) {
            return back()->with('error', 'Vous avez déjà atteint la limite de 3 emprunts actifs.');
        }

        // Réservation
        $duree = min((int)$request->input('duree', 7), 21); // max 21 jours

        // Utilisation de create sur la relation pour lier automatiquement l'utilisateur
        $user->emprunts()->create([
            'livre_id' => $livre->id,
            'cd_id' => null, // Assurez-vous que cd_id est null pour un livre
            'date_emprunt' => $now, // Utiliser la date actuelle stockée
            'date_retour_prevue' => $now->copy()->addDays($duree), // Utiliser copy() pour ne pas modifier $now
        ]);

        // Décrémenter le nombre d'exemplaires du livre
        $livre->decrement('nb_exemplaires'); // <<< LIGNE NON COMMENTÉE >>>

        return redirect()->route('profile')->with('success', 'Livre réservé avec succès.');
    }



}