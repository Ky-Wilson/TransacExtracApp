@extends('layouts.adminv2.master')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center justify-between mb-4">
                <h3>Transactions MTN</h3>
            </div>

            <!-- Filtre par gestionnaire -->
            <form method="GET" class="mb-6">
                <div class="flex items-center gap-4">
                    <select name="gestionnaire" class="form-control w-64">
                        <option value="">Tous les gestionnaires</option>
                        @foreach ($gestionnaires as $g)
                            <option value="{{ $g->id }}" {{ request('gestionnaire') == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="tf-button style-1">Filtrer</button>
                </div>
            </form>

            <div class="overflow-x-auto -mx-4 sm:-mx-0">
                <table class="table table-striped table-hover min-w-full min-w-[600px]">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant (FCFA)</th>
                            <th>Gestionnaire</th>
                            <th>Référence</th>
                            <!-- Ajoutez d'autres colonnes si votre modèle TransacMtn en a plus -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transac)
                            <tr>
                                <td class="whitespace-nowrap">{{ $transac->date->format('d/m/Y H:i') }}</td>
                                <td class="whitespace-nowrap">{{ number_format($transac->montant, 0, ',', ' ') }}</td>
                                <td class="whitespace-nowrap">{{ $transac->gestionnaire_name }}</td>
                                <td class="whitespace-nowrap">{{ $transac->reference ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-muted">Aucune transaction MTN</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
