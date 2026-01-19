@extends('layouts.adminv2.master')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- En-tête -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tableau de bord</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="fa-solid fa-chevron-right"></i></li>
                <li><div class="text-tiny">Vue d'ensemble</div></li>
            </ul>
        </div>

        <div class="tf-section-2 mb-30">
            <div class="flex gap20 flex-wrap-mobile">

                <!-- Colonne GAUCHE -->
                <div class="w-half">

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-primary-subtle">
                                    <i class="fa-solid fa-users text-primary"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Total Gestionnaires</div>
                                    <h4 class="text-primary">{{ $totalManagers ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-success-subtle">
                                    <i class="fa-solid fa-user-check text-success"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Gestionnaires Actifs</div>
                                    <h4 class="text-success">{{ $activeManagers ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-warning-subtle">
                                    <i class="fa-solid fa-user-xmark text-warning"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Gestionnaires Inactifs</div>
                                    <h4 class="text-warning">{{ $inactiveManagers ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-orange-subtle">
                                    <i class="fa-solid fa-mobile-screen-button text-orange"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Transactions Orange aujourd'hui</div>
                                    <h4 class="text-orange">{{ $todayTransactionsOrange ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Colonne DROITE -->
                <div class="w-half">

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-orange-subtle">
                                    <i class="fa-solid fa-money-bill-wave text-orange"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Montant total extrait Orange</div>
                                    <h4 class="text-orange">
                                        {{ number_format((float)($totalAmountOrange ?? 0), 0, ',', ' ') }} FCFA
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-info-subtle">
                                    <i class="fa-solid fa-clock-rotate-left text-info"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Dernière transaction Orange</div>
                                    <h4 class="text-info">
                                        @if($lastOrange && $lastOrange->date)
                                            {{ \Carbon\Carbon::parse($lastOrange->date)->format('d/m/Y H:i') }}
                                            <small class="d-block text-muted mt-1">
                                                {{ number_format((float)($lastOrange->montant ?? 0), 0, ',', ' ') }} FCFA
                                            </small>
                                        @else
                                            Aucune transaction enregistrée
                                        @endif
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-purple-subtle">
                                    <i class="fa-solid fa-brain text-purple"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Taux de réussite OCR</div>
                                    <h4 class="text-purple">{{ $ocrSuccessRate ?? 0 }} %</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default">
                        <div class="flex items-center justify-between opacity-60">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg bg-secondary-subtle">
                                    <i class="fa-solid fa-mobile-screen-button text-secondary"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Transactions MTN</div>
                                    <h4 class="text-secondary">Bientôt disponible</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- 5 derniers gestionnaires -->
        <div class="tf-section mb-30">
            <div class="wg-box">
                <div class="flex items-center justify-between mb-4">
                    <h5>5 Derniers gestionnaires ajoutés</h5>
                    <a href="{{ route('gestionnaire.dashboard') }}" class="tf-button style-1 btn-sm">
                        Voir tous <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Date création</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentManagers as $manager)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $manager->avatar ? asset($manager->avatar) : asset('assets/avatars/default.png') }}"
                                             class="rounded-circle me-3" width="40" height="40" alt="{{ $manager->name }}">
                                        {{ $manager->name }}
                                    </div>
                                </td>
                                <td>{{ $manager->email }}</td>
                                <td>{{ $manager->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge {{ $manager->status ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                        {{ $manager->status ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users-slash fa-2x mb-3 opacity-50"></i><br>
                                    Aucun gestionnaire pour le moment
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
