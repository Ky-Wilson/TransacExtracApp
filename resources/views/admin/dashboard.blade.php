@extends('layouts.adminv2.master')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- En-tête -->
        <div class="dashboard-header">
            <div class="header-content">
                <div>
                    <h3 class="page-title">Tableau de bord Admin</h3>
                    <ul class="breadcrumbs">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><i class="fa-solid fa-chevron-right"></i></li>
                        <li>Vue d'ensemble</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- KPIs Gestionnaires -->
        <div class="stats-section">
            <h4 class="section-title">
                <i class="fa-solid fa-users-gear"></i>
                Gestionnaires
            </h4>
            <div class="stats-grid stats-grid-3">

                <div class="stat-card card-primary">
                    <div class="stat-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Gestionnaires</div>
                        <div class="stat-value">{{ $totalManagers ?? 0 }}</div>
                    </div>
                </div>

                <div class="stat-card card-success">
                    <div class="stat-icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Actifs</div>
                        <div class="stat-value">{{ $activeManagers ?? 0 }}</div>
                    </div>
                </div>

                <div class="stat-card card-warning">
                    <div class="stat-icon">
                        <i class="fa-solid fa-user-xmark"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Inactifs</div>
                        <div class="stat-value">{{ $inactiveManagers ?? 0 }}</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section ORANGE MONEY -->
        <div class="stats-section">
            <h4 class="section-title orange-title">
                <i class="fa-solid fa-circle-dot"></i>
                Orange Money
            </h4>
            <div class="stats-grid stats-grid-4">

                <div class="stat-card card-orange">
                    <div class="stat-icon">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Transactions aujourd'hui</div>
                        <div class="stat-value">{{ $todayTransactionsOrange ?? 0 }}</div>
                    </div>
                </div>

                <div class="stat-card card-orange">
                    <div class="stat-icon">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Montant total extrait</div>
                        <div class="stat-value stat-amount">
                            {{ number_format((float)($totalAmountOrange ?? 0), 0, ',', ' ') }}
                            <span class="currency">FCFA</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card card-orange">
                    <div class="stat-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Dernière transaction</div>
                        <div class="stat-value stat-datetime">
                            @if($lastOrange && $lastOrange->date)
                                {{ \Carbon\Carbon::parse($lastOrange->date)->format('d/m/Y H:i') }}
                                <span class="stat-sublabel">
                                    {{ number_format((float)($lastOrange->montant ?? 0), 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span class="stat-empty">Aucune transaction</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="stat-card card-purple">
                    <div class="stat-icon">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Taux de réussite OCR</div>
                        <div class="stat-value">
                            {{ $ocrSuccessRate ?? 0 }}
                            <span class="currency">%</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section MTN MOBILE MONEY -->
        <div class="stats-section">
            <h4 class="section-title mtn-title">
                <i class="fa-solid fa-circle-dot"></i>
                MTN Mobile Money
            </h4>
            <div class="stats-grid stats-grid-3">

                <div class="stat-card card-mtn">
                    <div class="stat-icon">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Transactions aujourd'hui</div>
                        <div class="stat-value">{{ $todayTransactionsMtn ?? 0 }}</div>
                    </div>
                </div>

                <div class="stat-card card-mtn">
                    <div class="stat-icon">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Montant total extrait</div>
                        <div class="stat-value stat-amount">
                            {{ number_format((float)($totalAmountMtn ?? 0), 0, ',', ' ') }}
                            <span class="currency">FCFA</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card card-mtn">
                    <div class="stat-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Dernière transaction</div>
                        <div class="stat-value stat-datetime">
                            @if($lastMtn && $lastMtn->date)
                                {{ \Carbon\Carbon::parse($lastMtn->date)->format('d/m/Y H:i') }}
                                <span class="stat-sublabel">
                                    {{ number_format((float)($lastMtn->montant ?? 0), 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span class="stat-empty">Aucune transaction</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tableau des gestionnaires -->
        <div class="table-section">
            <div class="table-header">
                <h4 class="table-title">
                    <i class="fa-solid fa-users"></i>
                    Derniers gestionnaires ajoutés
                </h4>
                <a href="{{ route('admin.manage.managers') }}" class="btn-view-all">
                    Voir tous
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="table-wrapper">
                <table class="managers-table">
                    <thead>
                        <tr>
                            <th class="col-name">Nom</th>
                            <th class="col-email">Email</th>
                            <th class="col-date">Date création</th>
                            <th class="col-status">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentManagers as $manager)
                        <tr>
                            <td class="col-name">
                                <div class="manager-info">
                                    <img src="{{ $manager->avatar ? asset($manager->avatar) : asset('assets/avatars/default.png') }}"
                                         class="manager-avatar" alt="{{ $manager->name }}">
                                    <span class="manager-name">{{ $manager->name }}</span>
                                </div>
                            </td>
                            <td class="col-email">{{ $manager->email }}</td>
                            <td class="col-date">{{ $manager->created_at->format('d/m/Y') }}</td>
                            <td class="col-status">
                                <span class="status-badge {{ $manager->status ? 'status-active' : 'status-inactive' }}">
                                    {{ $manager->status ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <div class="empty-content">
                                    <i class="fa-solid fa-users-slash"></i>
                                    <p>Aucun gestionnaire pour le moment</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Indicateur de scroll mobile -->
            <div class="scroll-hint">
                <i class="fa-solid fa-arrows-left-right"></i>
                Faites défiler pour voir plus
            </div>
        </div>

    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    /* ============================================
       VARIABLES & BASE
    ============================================ */

    :root {
        --color-primary: #4F46E5;
        --color-primary-light: #818CF8;
        --color-primary-dark: #3730A3;

        --color-success: #10B981;
        --color-success-light: #34D399;

        --color-warning: #F59E0B;
        --color-warning-light: #FBBF24;

        --color-orange: #FF6B00;
        --color-orange-light: #FF8C3A;
        --color-orange-bg: #FFF4ED;

        --color-mtn: #FFCC00;
        --color-mtn-dark: #E6B800;
        --color-mtn-bg: #FFFBEB;

        --color-purple: #8B5CF6;
        --color-purple-light: #A78BFA;

        --color-text: #111827;
        --color-text-secondary: #6B7280;
        --color-text-light: #9CA3AF;

        --color-bg: #F9FAFB;
        --color-white: #FFFFFF;
        --color-border: #E5E7EB;

        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 20px;

        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);

        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .main-content-inner {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--color-bg);
    }

    /* ============================================
       HEADER
    ============================================ */

    .dashboard-header {
        margin-bottom: 32px;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--color-text);
        margin: 0 0 8px 0;
        letter-spacing: -0.02em;
    }

    .breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: 14px;
    }

    .breadcrumbs li {
        color: var(--color-text-secondary);
        display: flex;
        align-items: center;
    }

    .breadcrumbs a {
        color: var(--color-primary);
        text-decoration: none;
        transition: var(--transition);
    }

    .breadcrumbs a:hover {
        color: var(--color-primary-dark);
    }

    .breadcrumbs i {
        font-size: 10px;
        color: var(--color-text-light);
    }

    /* ============================================
       STATS SECTIONS
    ============================================ */

    .stats-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-text);
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        font-size: 20px;
        color: var(--color-primary);
    }

    .orange-title i {
        color: var(--color-orange);
    }

    .mtn-title i {
        color: var(--color-mtn-dark);
    }

    .stats-grid {
        display: grid;
        gap: 24px;
    }

    .stats-grid-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .stats-grid-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    /* ============================================
       STAT CARDS
    ============================================ */

    .stat-card {
        background: var(--color-white);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        align-items: flex-start;
        gap: 20px;
        border: 2px solid transparent;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, currentColor);
        opacity: 0;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: currentColor;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--color-text);
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .stat-amount {
        font-size: 24px;
    }

    .stat-datetime {
        font-size: 18px;
        font-weight: 700;
    }

    .currency {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-secondary);
        margin-left: 4px;
    }

    .stat-sublabel {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: var(--color-text-secondary);
        margin-top: 4px;
    }

    .stat-empty {
        font-size: 14px;
        font-weight: 500;
        color: var(--color-text-light);
    }

    /* Card variants */
    .card-primary {
        color: var(--color-primary);
    }

    .card-primary .stat-icon {
        background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary));
        color: white;
    }

    .card-success {
        color: var(--color-success);
    }

    .card-success .stat-icon {
        background: linear-gradient(135deg, var(--color-success-light), var(--color-success));
        color: white;
    }

    .card-warning {
        color: var(--color-warning);
    }

    .card-warning .stat-icon {
        background: linear-gradient(135deg, var(--color-warning-light), var(--color-warning));
        color: white;
    }

    .card-orange {
        color: var(--color-orange);
    }

    .card-orange .stat-icon {
        background: linear-gradient(135deg, var(--color-orange-light), var(--color-orange));
        color: white;
    }

    .card-mtn {
        color: var(--color-mtn-dark);
    }

    .card-mtn .stat-icon {
        background: linear-gradient(135deg, var(--color-mtn), var(--color-mtn-dark));
        color: #1a1a1a;
    }

    .card-purple {
        color: var(--color-purple);
    }

    .card-purple .stat-icon {
        background: linear-gradient(135deg, var(--color-purple-light), var(--color-purple));
        color: white;
    }

    /* ============================================
       TABLE SECTION
    ============================================ */

    .table-section {
        background: var(--color-white);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .table-header {
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid var(--color-border);
        flex-wrap: wrap;
        gap: 16px;
    }

    .table-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--color-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .table-title i {
        font-size: 22px;
        color: var(--color-primary);
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
        color: white;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
    }

    .btn-view-all:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
    }

    .btn-view-all i {
        font-size: 12px;
        transition: var(--transition);
    }

    .btn-view-all:hover i {
        transform: translateX(4px);
    }

    /* ============================================
       TABLE
    ============================================ */

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .managers-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .managers-table thead {
        background: linear-gradient(135deg, #F9FAFB, #F3F4F6);
    }

    .managers-table thead th {
        padding: 20px 32px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-bottom: 2px solid var(--color-border);
    }

    .managers-table tbody tr {
        border-bottom: 1px solid var(--color-border);
        transition: var(--transition);
    }

    .managers-table tbody tr:last-child {
        border-bottom: none;
    }

    .managers-table tbody tr:hover {
        background: var(--color-bg);
    }

    .managers-table tbody td {
        padding: 24px 32px;
        color: var(--color-text);
        font-size: 14px;
    }

    /* Colonnes */
    .col-name {
        min-width: 280px;
    }

    .col-email {
        min-width: 280px;
        color: var(--color-text-secondary);
    }

    .col-date {
        min-width: 150px;
        font-weight: 600;
    }

    .col-status {
        min-width: 140px;
    }

    /* Manager info */
    .manager-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .manager-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--color-border);
        transition: var(--transition);
    }

    .managers-table tbody tr:hover .manager-avatar {
        border-color: var(--color-primary);
        transform: scale(1.05);
    }

    .manager-name {
        font-weight: 600;
        color: var(--color-text);
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        min-width: 90px;
    }

    .status-active {
        background: linear-gradient(135deg, var(--color-success-light), var(--color-success));
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .status-inactive {
        background: linear-gradient(135deg, var(--color-warning-light), var(--color-warning));
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    /* Empty state */
    .empty-state {
        padding: 80px 32px !important;
        text-align: center;
    }

    .empty-content {
        color: var(--color-text-light);
    }

    .empty-content i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .empty-content p {
        font-size: 16px;
        font-weight: 500;
        margin: 0;
    }

    /* Scroll hint */
    .scroll-hint {
        display: none;
        padding: 16px;
        text-align: center;
        background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary));
        color: white;
        font-size: 13px;
        font-weight: 600;
        gap: 8px;
        align-items: center;
        justify-content: center;
    }

    /* Scrollbar */
    .table-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: var(--color-bg);
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: var(--color-border);
        border-radius: 10px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: var(--color-text-light);
    }

    /* ============================================
       RESPONSIVE
    ============================================ */

    @media (max-width: 1400px) {
        .stats-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 1024px) {
        .stats-grid-3,
        .stats-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-value {
            font-size: 28px;
        }

        .stat-amount {
            font-size: 20px;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 24px;
        }

        .stats-grid-3,
        .stats-grid-4 {
            grid-template-columns: 1fr;
        }

        .stats-section {
            margin-bottom: 32px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }

        .table-header {
            padding: 20px;
        }

        .table-title {
            font-size: 18px;
        }

        .managers-table thead th {
            padding: 16px 20px;
        }

        .managers-table tbody td {
            padding: 20px;
        }

        .scroll-hint {
            display: flex;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    }

    @media (max-width: 480px) {
        .dashboard-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 20px;
        }

        .breadcrumbs {
            font-size: 12px;
        }

        .section-title {
            font-size: 16px;
        }

        .stat-card {
            padding: 16px;
            gap: 12px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .stat-value {
            font-size: 24px;
        }

        .stat-amount {
            font-size: 18px;
        }

        .stat-datetime {
            font-size: 16px;
        }

        .table-wrapper {
            margin: 0 -20px;
        }

        .managers-table thead th,
        .managers-table tbody td {
            padding: 16px;
        }

        .manager-avatar {
            width: 40px;
            height: 40px;
        }
    }
</style>
@endsection
