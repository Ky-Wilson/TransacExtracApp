@extends('layouts.gestionnairev2.master')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- Header + Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Transactions MTN MoMo</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('gestionnaire.dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="fa-solid fa-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Transactions MTN</div>
                </li>
            </ul>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Boîte principale -->
        <div class="wg-box">
            <!-- Recherche + Filtres + Bouton PDF -->
            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('manager.mtn.transactions') }}">
                        <fieldset class="name">
                            <input type="text"
                                   name="search"
                                   placeholder="Référence ou numéro expéditeur..."
                                   value="{{ request('search') }}"
                                   class="form-control">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('manager.dashboard.pdf') }}" class="tf-button style-1 mtn-pdf-btn">
                        <i class="fa-solid fa-file-pdf me-2"></i>Télécharger PDF
                    </a>
                    <button class="tf-button style-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fa-solid fa-filter me-2"></i>Filtres
                    </button>
                </div>
            </div>

            <!-- Filtres avancés (collapse) -->
            <div class="collapse mb-4" id="filterCollapse">
                <form method="GET" action="{{ route('manager.mtn.transactions') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Type de transaction</label>
                        <select name="type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="transfere" {{ request('type') == 'transfere' ? 'selected' : '' }}>Transfert</option>
                            <option value="depot" {{ request('type') == 'depot' ? 'selected' : '' }}>Dépôt / Reçu</option>
                            <option value="retrait" {{ request('type') == 'retrait' ? 'selected' : '' }}>Retrait</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date dès</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date jusqu'à</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="tf-button style-1">Appliquer</button>
                        <a href="{{ route('manager.mtn.transactions') }}" class="tf-button style-2">Réinitialiser</a>
                    </div>
                </form>
            </div>

            <!-- Table des transactions MTN -->
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Type</th>
                                <th class="text-center">Montant</th>
                                <th class="text-center">Expéditeur</th>
                                <th class="text-center">Référence</th>
                                <th class="text-center">Solde après</th>
                                <th class="text-center">Frais</th>
                                <th class="text-center">Date & Heure</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="text-center">
                                        @if($transaction->type == 'transfere')
                                            <span class="badge bg-warning" style="background-color: #FFCC00 !important; color: #000;">
                                                <i class="fa-solid fa-arrow-right-arrow-left me-1"></i>Transfert
                                            </span>
                                        @elseif($transaction->type == 'depot')
                                            <span class="badge bg-success" style="background-color: #00A859 !important;">
                                                <i class="fa-solid fa-arrow-down me-1"></i>Dépôt / Reçu
                                            </span>
                                        @elseif($transaction->type == 'retrait')
                                            <span class="badge bg-danger">
                                                <i class="fa-solid fa-arrow-up me-1"></i>Retrait
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($transaction->type) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        {{ number_format((float)$transaction->montant, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="text-center">
                                        {{ $transaction->expediteur ?? '<span class="text-muted">N/A</span>' }}
                                    </td>
                                    <td class="text-center">
                                        <code class="bg-light px-2 py-1 rounded">
                                            {{ $transaction->reference ?? 'N/A' }}
                                        </code>
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        {{ $transaction->solde ? number_format((float)$transaction->solde, 0, ',', ' ') . ' FCFA' : 'N/A' }}
                                    </td>
                                    <td class="text-center text-warning fw-semibold">
                                        {{ $transaction->frais ? number_format((float)$transaction->frais, 0, ',', ' ') . ' FCFA' : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">
                                            <i class="fa-solid fa-calendar-days me-1"></i>
                                            {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-receipt fa-3x mb-3 opacity-50"></i><br>
                                        Aucune transaction MTN trouvée pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="divider my-4"></div>

            <!-- Pagination + Bouton Ajouter -->
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                <div>
                    {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                <a href="{{ route('manager.mtn.form') }}" class="tf-button style-1" style="background: linear-gradient(135deg, #FFCC00, #FFB300); color: #000;">
                    <i class="fa-solid fa-plus me-2"></i>Ajouter transaction MTN
                </a>
            </div>
        </div>

    </div>
</div>
<style>
    .wg-box {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
    .tf-button.style-1 {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 0.6rem 1.4rem;
        border-radius: 8px;
    }
    .tf-button.style-1.mtn-pdf-btn {
        background: linear-gradient(135deg, #FFCC00, #FFB300);
        color: #000;
    }
    .tf-button.style-2 {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .badge.bg-warning {
        background-color: #FFCC00 !important;
        color: #000 !important;
    }
    .badge.bg-success {
        background-color: #00A859 !important;
    }
    .form-search input {
        height: 46px;
        border-radius: 8px 0 0 8px;
        border: 1px solid #e0e0e0;
    }
    .form-search .button-submit button {
        height: 46px;
        border-radius: 0 8px 8px 0;
        background: #667eea;
        border: none;
        color: white;
        padding: 0 1.2rem;
    }
    @media (max-width: 768px) {
        .table-responsive { overflow-x: auto; }
        .table th, .table td { min-width: 140px; }
    }
</style>
@endsection
