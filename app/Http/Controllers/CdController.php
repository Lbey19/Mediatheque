<?php


namespace App\Http\Controllers;

use App\Models\Cd; // Importer le modèle Cd
use App\Models\Emprunt; // Importer Emprunt pour plus tard
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importer Auth pour la réservation
use Carbon\Carbon; // Importer Carbon pour la réservation

class CdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Logique similaire à LivreController->index
        $query = Cd::query()->where('disponible', true); // Ou afficher tous les CDs ? À décider.

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('artiste', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%"); // <<< AJOUTER CETTE LIGNE
            });
        }

        $cds = $query->orderBy('artiste')->orderBy('titre')->paginate(10); // Paginer les résultats

        return view('cds.index', compact('cds')); // Retourner la vue index des CDs
    }

    /**
     * Display the specified resource.
     */
    public function show(Cd $cd) // Utilisation du Route Model Binding
    {
        // Logique similaire à LivreController->show
        $prochainRetour = null; // Initialiser
        $user = auth()->user();
        $aAtteintLimite = false; // Initialiser

        if (auth()->check()) {
            // Vérifier la limite d'emprunts (sera ajustée plus tard pour inclure les CDs)
            $aAtteintLimite = $user->emprunts()
                ->whereNull('date_retour_effective')
                ->count() >= 3; // Limite globale (livres + cds) ? À définir.
        }

        // Si le CD n'a plus d'exemplaires, chercher le prochain retour prévu
        // Note: Cela nécessite que la relation Emprunt fonctionne avec les CDs (Étape 6)
        if ($cd->nb_exemplaires <= 0) {
             $prochainRetour = Emprunt::where('cd_id', $cd->id) // <<< Vérifier que c'est bien cd_id
                 ->whereNull('date_retour_effective')
                 ->orderBy('date_retour_prevue', 'asc')
                 ->first();
        }

        return view('cds.show', compact('cd', 'prochainRetour', 'aAtteintLimite')); // Retourner la vue show du CD
    }

    public function reserve(Request $request, Cd $cd) // Utilisation du Route Model Binding
    {
        $user = auth()->user();
        $now = Carbon::now();

        // --- AJOUT : Vérification des retards ---
        $empruntsEnRetard = $user->emprunts()
                                ->whereNull('date_retour_effective')
                                ->where('date_retour_prevue', '<', Carbon::today()) // Vérifie si la date prévue est passée
                                ->exists(); // exists() est plus performant si on a juste besoin de savoir s'il y en a au moins un

        if ($empruntsEnRetard) {
            return back()->with('error', 'Vous ne pouvez pas emprunter de nouveaux articles car vous avez des retours en retard.');
        }
        // --- FIN AJOUT ---

        // --- VÉRIFICATIONS (similaires à LivreController) ---

        // 1. Vérifier l'abonnement de l'utilisateur
        if (!$user->date_expiration || Carbon::parse($user->date_expiration)->isPast()) {
            return back()->with('error', 'Votre abonnement est expiré ou invalide. Vous ne pouvez pas réserver de CD.');
        }

        // 2. Vérifier le stock du CD
        if ($cd->nb_exemplaires <= 0) {
            return back()->with('error', 'Ce CD n\'est plus disponible.');
        }

        // 3. Vérifier si l'utilisateur a déjà emprunté ce CD
        //    NOTE : Ceci nécessite que la table 'emprunts' ait une colonne 'cd_id' (ou une relation polymorphique) - Voir Étape 6
        $dejaEmprunte = $user->emprunts()
            ->where('cd_id', $cd->id) // Assurez-vous que cd_id existe dans la table emprunts
            ->whereNull('date_retour_effective')
            ->exists();

        if ($dejaEmprunte) {
            return back()->with('error', 'Vous avez déjà emprunté ce CD.');
        }

        //    NOTE : Ceci compte les livres ET les CDs. Ajustez si nécessaire.
        if ($user->emprunts()->whereNull('date_retour_effective')->count() >= 3) {
            return back()->with('error', 'Vous avez déjà atteint la limite de 3 emprunts actifs (livres et CDs).');
        }

        // --- CRÉATION DE L'EMPRUNT ---

        // Récupérer et valider la durée (similaire à LivreController)
        $duree = min((int)$request->input('duree', 7), 21); // 7 jours par défaut, max 21

        // Créer l'enregistrement d'emprunt
        $user->emprunts()->create([
            'livre_id' => null, // Important si vous ajoutez cd_id
            'cd_id' => $cd->id,      // Assurez-vous que cd_id existe dans la table emprunts
            'date_emprunt' => $now,
            'date_retour_prevue' => $now->copy()->addDays($duree),
        ]);

        // Décrémenter le nombre d'exemplaires du CD
        $cd->decrement('nb_exemplaires'); // <<< LIGNE NON COMMENTÉE >>>

        return redirect()->route('profile')->with('success', 'CD réservé avec succès.');
    }
}