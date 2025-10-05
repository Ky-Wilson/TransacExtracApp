@extends('layouts.superadmin.master')

@section('content')
<main>
    <div class="table-data">
        <div class="order">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-5">
                <div class="head d-flex justify-content-between align-items-center mb-4">
                    <h3 class="m-0">Liste des Gestionnaires</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover shadow-sm rounded" style="white-space: nowrap;">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 15%; min-width: 120px;">Avatar</th>
                                <th class="text-center" style="width: 15%; min-width: 120px;">Nom</th>
                                <th class="text-center" style="width: 20%; min-width: 160px;">Email</th>
                                <th class="text-center" style="width: 15%; min-width: 120px;">Date de Création</th>
                                <th class="text-center" style="width: 15%; min-width: 120px;">Statut</th>
                                <th class="text-center" style="width: 20%; min-width: 160px;">Admin Associé</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($managers as $manager)
                            <tr class="align-middle">
                                <td class="text-center">
                                    <img src="{{ $manager->avatar ? asset($manager->avatar) : asset('assets/avatars/default.png') }}" alt="Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                </td>
                                <td class="text-center">
                                    <p class="m-0">{{ $manager->name }}</p>
                                </td>
                                <td class="text-center">{{ Str::limit($manager->email, 20) }}</td>
                                <td class="text-center">{{ $manager->created_at->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $manager->status ? 'bg-success' : 'bg-warning' }} text-white">
                                        {{ $manager->status ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <p class="m-0">{{ $manager->admin ? $manager->admin->name : 'Aucun admin' }}</p>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">Aucun gestionnaire trouvé.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $managers->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection