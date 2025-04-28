<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Emprunt;
use Illuminate\Http\Request; // Ajout du use pour Request

class LivreController extends Controller
{
    public function index(Request $request)
    {
        $query = Livre::query()->where('disponible', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%");
            });
        }

        $livres = $query->paginate(6);

        return view('livres.index', compact('livres'));
    }

    public function show(Livre $livre)
    {
        $prochainRetour = null;

        if (!$livre->disponible) {
            $prochainRetour = Emprunt::where('livre_id', $livre->id)
                ->whereNull('date_retour_effective')
                ->orderBy('date_retour_prevue', 'asc')
                ->first();
        }

        return view('livres.show', compact('livre', 'prochainRetour'));
    }

    public function reserve(Livre $livre)
    {
        if ($livre->nb_exemplaires > 0) {
            // Simuler une réservation (on peut plus tard vraiment créer un emprunt ici)
            return redirect()->route('livres.index')->with('success', "Votre réservation du livre '{$livre->titre}' est prise en compte !");
        }

        return redirect()->back()->with('error', "Désolé, ce livre n'est plus disponible pour le moment.");
    }
}