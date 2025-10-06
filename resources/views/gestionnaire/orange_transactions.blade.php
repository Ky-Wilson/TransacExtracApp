@extends('layouts.gestionnaire.master')

@section('content')
<main>
    <div class="table-data">
        <div class="order">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class='bx bx-check-circle me-2'></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class='bx bx-error-circle me-2'></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="head d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0">
                    <i class='bx bxl-stripe' style='color: #ff6600; font-size: 1.5rem;'></i>
                    Transactions Orange
                </h3>
                <div>
                    <i class='bx bx-search me-2' style='cursor: pointer; font-size: 1.3rem;'></i>
                    <i class='bx bx-filter' style='cursor: pointer; font-size: 1.3rem;'></i>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover shadow-sm rounded" style="white-space: nowrap;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 15%; min-width: 120px;">
                                <i class='bx bx-category me-1'></i>Type
                            </th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">
                                <i class='bx bx-money me-1'></i>Montant
                            </th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">
                                <i class='bx bx-user me-1'></i>Expéditeur
                            </th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">
                                <i class='bx bx-file me-1'></i>Référence
                            </th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">
                                <i class='bx bx-wallet me-1'></i>Solde
                            </th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">
                                <i class='bx bx-receipt me-1'></i>Frais
                            </th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">
                                <i class='bx bx-calendar me-1'></i>Date
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                        <tr class="align-middle">
                            <td class="text-center">
                                @if($transaction->type == 'transfere')
                                    <span class="badge bg-primary">
                                        <i class='bx bx-transfer'></i> {{ ucfirst($transaction->type) }}
                                    </span>
                                @elseif($transaction->type == 'depot')
                                    <span class="badge bg-success">
                                        <i class='bx bx-download'></i> {{ ucfirst($transaction->type) }}
                                    </span>
                                @elseif($transaction->type == 'retrait')
                                    <span class="badge bg-danger">
                                        <i class='bx bx-upload'></i> {{ ucfirst($transaction->type) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-success">
                                {{ is_numeric($transaction->montant) ? number_format((float)$transaction->montant, 0, ',', ' ') : $transaction->montant }} FCFA
                            </td>
                            <td class="text-center">
                                @if($transaction->expediteur)
                                    <i class='bx bx-user-circle me-1' style='color: #6c757d;'></i>
                                    {{ $transaction->expediteur }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($transaction->reference)
                                    <code class="bg-light text-dark px-2 py-1 rounded">
                                        {{ $transaction->reference }}
                                    </code>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold" style="color: #0d6efd;">
                                {{ $transaction->solde ? number_format((float)$transaction->solde, 0, ',', ' ') . ' FCFA' : 'N/A' }}
                            </td>
                            <td class="text-center text-warning fw-semibold">
                                {{ $transaction->frais ? number_format((float)$transaction->frais, 0, ',', ' ') . ' FCFA' : 'N/A' }}
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    <i class='bx bx-time-five me-1'></i>
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y H:i') }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class='bx bx-info-circle' style='font-size: 3rem; color: #6c757d;'></i>
                                <p class="text-muted mt-3">Aucune transaction trouvée.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination mt-4">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
            
            <div class="mt-4">
                <a href="{{ route('manager.orange.form') }}" class="btn btn-primary">
                    <i class='bx bx-plus-circle me-2'></i>Ajouter une nouvelle transaction
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Styles personnalisés -->
<style>
    .table {
        white-space: nowrap !important;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .alert {
        border-radius: 10px;
        border-left: 4px solid;
    }
    
    .alert-success {
        border-left-color: #10b981;
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .alert-danger {
        border-left-color: #ef4444;
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    code {
        font-size: 0.875rem;
        font-family: 'Courier New', monospace;
    }
    
    @media (max-width: 768px) {
        .table {
            display: table !important;
            width: 100% !important;
        }
        .table th,
        .table td {
            min-width: 120px !important;
            padding: 0.5rem !important;
        }
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection