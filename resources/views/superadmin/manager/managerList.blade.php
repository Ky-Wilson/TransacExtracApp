@extends('layouts.superadminv2.master')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">

            <!-- Header + Breadcrumbs -->
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Liste des Gestionnaires</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('superadmin.dashboard') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="fa-solid fa-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Gestionnaires</div>
                    </li>
                </ul>
            </div>

            <!-- Message de succès -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Boîte principale -->
            <div class="wg-box">
                <!-- Barre de recherche + bouton (optionnel : ajouter un bouton "Nouveau gestionnaire") -->
                {{-- <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('superadmin.gestionnaire.index') }}">
                        <fieldset class="name">
                            <input type="text"
                                   name="search"
                                   placeholder="Rechercher par nom ou email..."
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

                <!-- Exemple bouton nouveau (à décommenter si besoin) -->
                <!--
                <a href="{{ route('superadmin.managers.create') }}" class="tf-button style-1">
                    <i class="fa-solid fa-plus me-2"></i>Nouveau Gestionnaire
                </a>
                -->
            </div> --}}

                <!-- Table -->
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%; min-width: 120px;">Avatar</th>
                                    <th class="text-center" style="width: 20%; min-width: 160px;">Nom</th>
                                    <th class="text-center" style="width: 25%; min-width: 220px;">Email</th>
                                    <th class="text-center" style="width: 15%; min-width: 140px;">Date Création</th>
                                    <th class="text-center" style="width: 10%; min-width: 100px;">Statut</th>
                                    <th class="text-center" style="width: 20%; min-width: 180px;">Admin Associé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($managers as $manager)
                                    <tr class="align-middle">
                                        <td class="text-center">
                                            <img src="{{ $manager->avatar ? asset($manager->avatar) : asset('assets/avatars/default.png') }}"
                                                alt="Avatar de {{ $manager->name }}" class="rounded-circle"
                                                style="width: 48px; height: 48px; object-fit: cover;">
                                        </td>
                                        <td class="text-center fw-medium">{{ $manager->name }}</td>
                                        <td class="text-center">{{ Str::limit($manager->email, 28, '...') }}</td>
                                        <td class="text-center">{{ $manager->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $manager->status ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                                {{ $manager->status ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($manager->admin)
                                                {{ $manager->admin->name }}
                                            @else
                                                <span class="text-muted">Aucun admin</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-users-slash fa-2x mb-3 opacity-50"></i><br>
                                            Aucun gestionnaire trouvé.
                                            @if (request('search'))
                                                <small class="d-block mt-2">Essayez un autre terme de recherche.</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="divider my-4"></div>

                <!-- Pagination -->
                <div class="flex items-center justify-center flex-wrap gap10 wgp-pagination">
                    {{ $managers->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

    <style>
        .wg-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
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

        .tf-button.style-1 {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
        }

        .table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .badge {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table th,
            .table td {
                min-width: 130px;
            }
        }
    </style>
@endsection
