<div>
    {{-- Onglets --}}
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4">
        <button
            wire:click="setActiveTab('overdue')"
            class="px-4 py-2 -mb-px border-b-2 font-medium text-sm focus:outline-none {{ $activeTab === 'overdue' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
            Emprunts en Retard
        </button>
        <button
            wire:click="setActiveTab('latest')"
            class="px-4 py-2 -mb-px border-b-2 font-medium text-sm focus:outline-none {{ $activeTab === 'latest' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
            Derniers Emprunts
        </button>
    </div>

    {{-- Tableau --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">Adhérent</th>
                    <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">Média</th>
                    <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">Date Emprunt</th>
                    <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">Retour Prévu</th>
                    <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">Statut</th>
                    @if($activeTab === 'overdue')
                        <th scope="col" class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">Jours Retard</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($loans as $loan)
                    <tr class="dark:bg-gray-900">
                        {{-- Adhérent --}}
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200">
                            {{ $loan->user->name ?? 'N/A' }}
                        </td>

                        {{-- Média --}}
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200">
                            {{ $this->getItemTitle($loan) }}
                        </td>

                        {{-- Date Emprunt --}}
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200">
                            {{ optional($loan->created_at)->format('d/m/Y') }}
                        </td>

                        {{-- Retour Prévu --}}
                        <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200">
                            {{ optional($loan->date_retour_prevue)->format('d/m/Y') }}
                        </td>

                        {{-- Statut --}}
                        <td class="px-4 py-2 whitespace-nowrap">
                            @php $status = $this->getStatus($loan); @endphp
                            <span class="inline-flex items-center justify-center rounded-full bg-{{ $status['color'] ?? 'gray' }}-100 px-2.5 py-0.5 text-{{ $status['color'] ?? 'gray' }}-700 dark:bg-{{ $status['color'] ?? 'gray' }}-700 dark:text-{{ $status['color'] ?? 'gray' }}-100">
                                <p class="whitespace-nowrap text-xs">{{ $status['text'] ?? 'Inconnu' }}</p>
                            </span>
                        </td>

                        {{-- Jours Retard (si onglet "overdue") --}}
                        @if($activeTab === 'overdue')
                            <td class="px-4 py-2 whitespace-nowrap text-red-600 dark:text-red-400">
                                {{ \Carbon\Carbon::today()->diffInDays($loan->date_retour_prevue) }}
                            </td>
                        @endif
                    </tr>
                @empty
                    {{-- Message si aucun emprunt --}}
                    <tr>
                        <td colspan="{{ $activeTab === 'overdue' ? 6 : 5 }}" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                            Aucun emprunt à afficher pour cette catégorie.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($loans->hasPages())
        <div class="mt-4">
            {{ $loans->links() }}
        </div>
    @endif
</div>