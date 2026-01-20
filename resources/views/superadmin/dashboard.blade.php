@extends('layouts.superadminv2.master')

@section('content')
<style>
    /* Variables CSS */
    :root {
        --primary: #667eea;
        --success: #10b981;
        --warning: #f59e0b;
        --orange: #FF6B00;
        --mtn-yellow: #FFCC00;
        --purple: #8b5cf6;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --bg-light: #f9fafb;
        --white: #ffffff;
        --border: #e5e7eb;
        --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    .main-content-wrap {
        padding: 1.5rem;
    }

    /* En-tête */
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
        background: var(--white);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }

    .dashboard-header h3 {
        font-size: clamp(1.5rem, 4vw, 2rem);
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .breadcrumbs {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .breadcrumbs li {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .breadcrumbs i {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    /* Grid des cartes */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Cartes de statistiques */
    .stat-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: currentColor;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
        transition: transform 0.3s;
    }

    .stat-card:hover .stat-icon {
        transform: rotate(5deg) scale(1.1);
    }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: clamp(1.5rem, 3vw, 1.875rem);
        font-weight: 700;
        margin: 0;
        word-break: break-word;
    }

    /* Couleurs des cartes */
    .stat-card.primary { color: var(--primary); }
    .stat-card.success { color: var(--success); }
    .stat-card.warning { color: var(--warning); }
    .stat-card.orange { color: var(--orange); }
    .stat-card.mtn-yellow { color: var(--mtn-yellow); }
    .stat-card.purple { color: var(--purple); }

    .bg-primary-subtle { background: rgba(102, 126, 234, 0.1); }
    .bg-success-subtle { background: rgba(16, 185, 129, 0.1); }
    .bg-warning-subtle { background: rgba(245, 158, 11, 0.1); }
    .bg-orange-subtle { background: rgba(255, 107, 0, 0.1); }
    .bg-mtn-yellow { background: rgba(255, 204, 0, 0.1); }
    .bg-purple-subtle { background: rgba(139, 92, 246, 0.1); }

    /* Section tableau */
    .transactions-section {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .transactions-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-bottom: 1px solid var(--border);
    }

    .transactions-header h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--white);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .transactions-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 100%;
    }

    .transactions-table thead {
        background: #f8fafc;
    }

    .transactions-table th {
        padding: 1rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border);
        white-space: nowrap;
    }

    .transactions-table td {
        padding: 1rem;
        font-size: 0.9375rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .transactions-table tbody tr {
        transition: background-color 0.2s;
    }

    .transactions-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-secondary);
        font-style: italic;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
        opacity: 0.3;
    }

    /* Badge type */
    .badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-transfert { background: rgba(102, 126, 234, 0.1); color: var(--primary); }
    .badge-depot { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .badge-retrait { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card {
        animation: fadeInUp 0.6s ease-out backwards;
    }

    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.2s; }
    .stat-card:nth-child(5) { animation-delay: 0.25s; }
    .stat-card:nth-child(6) { animation-delay: 0.3s; }
    .stat-card:nth-child(7) { animation-delay: 0.35s; }
    .stat-card:nth-child(8) { animation-delay: 0.4s; }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .main-content-wrap {
            padding: 1rem;
        }

        .dashboard-header {
            padding: 1.25rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .transactions-header {
            padding: 1.25rem;
        }

        .transactions-table th,
        .transactions-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 576px) {
        .dashboard-header h3 {
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.375rem;
        }

        .transactions-table th,
        .transactions-table td {
            padding: 0.625rem 0.375rem;
            font-size: 0.75rem;
        }

        .badge {
            font-size: 0.625rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- En-tête -->
        <div class="dashboard-header">
            <h3>Dashboard Super Admin</h3>
            <ul class="breadcrumbs">
                <li>Super Admin</li>
                <li><i class="fa-solid fa-chevron-right"></i></li>
                <li>Vue d'ensemble</li>
            </ul>
        </div>

        <!-- Grille de statistiques -->
        <div class="stats-grid">
            <!-- Total Compagnies -->
            <div class="stat-card primary">
                <div class="stat-icon bg-primary-subtle">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Compagnies (Admins)</div>
                    <h4 class="stat-value">{{ $totalAdmins ?? 0 }}</h4>
                </div>
            </div>

            <!-- Total Gestionnaires -->
            <div class="stat-card success">
                <div class="stat-icon bg-success-subtle">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Gestionnaires</div>
                    <h4 class="stat-value">{{ $totalManagers ?? 0 }}</h4>
                </div>
            </div>

            <!-- Gestionnaires Actifs -->
            <div class="stat-card warning">
                <div class="stat-icon bg-warning-subtle">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Gestionnaires Actifs</div>
                    <h4 class="stat-value">{{ $activeManagers ?? 0 }}</h4>
                </div>
            </div>

            <!-- Transactions Orange -->
            <div class="stat-card orange">
                <div class="stat-icon bg-orange-subtle">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Transactions Orange (total)</div>
                    <h4 class="stat-value">{{ $totalTransactionsOrange ?? 0 }}</h4>
                </div>
            </div>

            <!-- Transactions MTN -->
            <div class="stat-card mtn-yellow">
                <div class="stat-icon bg-mtn-yellow">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Transactions MTN (total)</div>
                    <h4 class="stat-value">{{ $totalTransactionsMtn ?? 0 }}</h4>
                </div>
            </div>

            <!-- Montant Orange -->
            <div class="stat-card orange">
                <div class="stat-icon bg-orange-subtle">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Montant total Orange</div>
                    <h4 class="stat-value">
                        {{ number_format((float)($totalAmountOrange ?? 0), 0, ',', ' ') }} FCFA
                    </h4>
                </div>
            </div>

            <!-- Montant MTN -->
            <div class="stat-card mtn-yellow">
                <div class="stat-icon bg-mtn-yellow">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Montant total MTN</div>
                    <h4 class="stat-value">
                        {{ number_format((float)($totalAmountMtn ?? 0), 0, ',', ' ') }} FCFA
                    </h4>
                </div>
            </div>

            <!-- Taux OCR -->
            <div class="stat-card purple">
                <div class="stat-icon bg-purple-subtle">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Taux de réussite OCR Orange</div>
                    <h4 class="stat-value">{{ $ocrSuccessRate ?? 0 }} %</h4>
                </div>
            </div>
        </div>

        <!-- Dernières transactions -->
        <div class="transactions-section">
            <div class="transactions-header">
                <h5>
                    <i class="fa-solid fa-list-ul"></i>
                    5 Dernières transactions (toutes compagnies)
                </h5>
            </div>

            <div class="table-wrapper">
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Gestionnaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transac)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transac->date)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($transac->type == 'transfert')
                                    <span class="badge badge-transfert">{{ ucfirst($transac->type) }}</span>
                                @elseif($transac->type == 'depot')
                                    <span class="badge badge-depot">{{ ucfirst($transac->type) }}</span>
                                @else
                                    <span class="badge badge-retrait">{{ ucfirst($transac->type) }}</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format((float)$transac->montant, 0, ',', ' ') }} FCFA</strong></td>
                            <td>{{ $transac->user ? $transac->user->name : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox"></i>
                                    <div>Aucune transaction disponible</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
