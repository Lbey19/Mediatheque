<?php


namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Emprunt;
use Carbon\Carbon;
use Livewire\WithPagination; // Pour la pagination si besoin

class LoanStatsWidget  extends Component
{
    use WithPagination;

    public string $activeTab = 'overdue'; // Onglet actif par défaut ('overdue' ou 'latest')
    public int $perPage = 5; // Nombre d'éléments par page

    // Méthode pour changer l'onglet actif
    public function setActiveTab(string $tabName)
    {
        $this->activeTab = $tabName;
        $this->resetPage(); // Réinitialise la pagination quand on change d'onglet
    }

    // Méthode pour récupérer les données en fonction de l'onglet actif
    protected function getLoans()
    {
        if ($this->activeTab === 'overdue') {
            return Emprunt::query()
                ->whereNull('date_retour_effective')
                ->where('date_retour_prevue', '<', Carbon::today())
                ->with(['user', 'livre', 'cd'])
                ->latest('date_retour_prevue') // Ou 'asc' si vous préférez les plus anciens retards en premier
                ->paginate($this->perPage);
        } else { // 'latest'
            return Emprunt::query()
                ->with(['user', 'livre', 'cd'])
                ->latest('created_at')
                ->paginate($this->perPage);
        }
    }

    // Méthode pour rendre la vue
    public function render()
    {
        return view('livewire.loan-stats-widget');
    }

    // Fonction utilitaire pour obtenir le titre du média
    public function getItemTitle(Emprunt $emprunt): string
    {
        if ($emprunt->livre) return $emprunt->livre->titre . ' (Livre)';
        if ($emprunt->cd) return $emprunt->cd->titre . ' (CD)';
        // Ajoutez DVD ici si nécessaire
        return 'Média inconnu';
    }

     // Fonction utilitaire pour obtenir le statut
     public function getStatus(Emprunt $emprunt): array
     {
         if ($emprunt->date_retour_effective) {
             return ['text' => 'Retourné', 'color' => 'success'];
         } elseif ($emprunt->date_retour_prevue < Carbon::today()) {
             return ['text' => 'En retard', 'color' => 'danger'];
         } else {
             return ['text' => 'En cours', 'color' => 'warning'];
         }
     }
}