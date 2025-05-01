<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
// use App\Models\Emprunt; // Import non nécessaire si on passe par la relation user->emprunts()

class ProfileController extends Controller
{
    /**
     * Affiche les infos du profil + historique des emprunts
     */
    public function show(): View
    {
        $user = auth()->user(); // Ou Auth::user();

        // Emprunts non rendus (en cours)
        // Renommer la variable, charger les relations et ajuster le tri
        $empruntsActifs = $user->emprunts()
            ->whereNull('date_retour_effective')
            ->with(['livre', 'cd']) // <<< Charger les relations livre et cd
            ->orderBy('date_retour_prevue', 'asc') // Trier par date de retour prévue
            ->get();

        // Emprunts déjà rendus (historiques)
        // Renommer la variable, charger les relations, ajuster le tri et paginer
        $historiqueEmprunts = $user->emprunts()
            ->whereNotNull('date_retour_effective')
            ->with(['livre', 'cd']) // <<< Charger les relations livre et cd
            ->orderBy('date_retour_effective', 'desc') // Trier par date de retour effective (plus récent en premier)
            ->paginate(10); // <<< Paginer les résultats (ex: 10 par page)

        // Passer les variables renommées à la vue
        return view('profile.show', compact('user', 'empruntsActifs', 'historiqueEmprunts'));
    }

    /**
     * Formulaire d'édition du profil (Breeze)
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour les infos du profil
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Supprime le compte utilisateur
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}