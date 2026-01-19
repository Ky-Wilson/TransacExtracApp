@extends('layouts.superadminv2.master')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- Header + Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Liste des Admins</h3>
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
                    <div class="text-tiny">Admins</div>
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
            <!-- Recherche -->
            {{-- <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('superadmin.manage-admins.index') }}">
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
                                <th class="text-center" style="width: 15%; min-width: 140px;">Gestionnaires</th>
                                <th class="text-center" style="width: 15%; min-width: 140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                                <tr class="align-middle">
                                    <td class="text-center">
                                        <img src="{{ $admin->avatar ? asset($admin->avatar) : asset('assets/avatars/default.png') }}"
                                             alt="Avatar de {{ $admin->name }}"
                                             class="rounded-circle"
                                             style="width: 48px; height: 48px; object-fit: cover;">
                                    </td>
                                    <td class="text-center fw-medium">{{ $admin->name }}</td>
                                    <td class="text-center">{{ Str::limit($admin->email, 28, '...') }}</td>
                                    <td class="text-center">{{ $admin->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $admin->status ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                            {{ $admin->status ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ $admin->managers_count ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm text-white edit-status"
                                                data-id="{{ $admin->id }}"
                                                data-name="{{ $admin->name }}"
                                                data-status="{{ $admin->status }}"
                                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="fa-solid fa-edit me-1"></i> Modifier Statut
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-user-shield fa-2x mb-3 opacity-50"></i><br>
                                        Aucun administrateur trouvé.
                                        @if(request('search'))
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
                {{ $admins->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
</div>

<!-- Script SweetAlert pour modifier statut (inchangé, juste adapté au style) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-status').forEach(button => {
        button.addEventListener('click', function () {
            const adminId   = this.dataset.id;
            const adminName = this.dataset.name;
            const isActive  = this.dataset.status === '1';

            Swal.fire({
                title: `Statut de ${adminName}`,
                html: `
                    <div class="form-check mb-3">
                        <input type="radio" class="form-check-input" name="status" value="1" ${isActive ? 'checked' : ''} id="status-active">
                        <label class="form-check-label" for="status-active">Actif</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="status" value="0" ${!isActive ? 'checked' : ''} id="status-inactive">
                        <label class="form-check-label" for="status-inactive">Inactif</label>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Enregistrer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#667eea',
                preConfirm: () => {
                    const selected = document.querySelector('input[name="status"]:checked');
                    if (!selected) {
                        Swal.showValidationMessage('Sélectionnez un statut');
                        return false;
                    }
                    return selected.value;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const status = result.value;
                    const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

                    fetch(`/superadmin/manage-admins/${adminId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Erreur réseau');
                        return res.json();
                    })
                    .then(() => {
                        Swal.fire('Mis à jour !', '', 'success').then(() => location.reload());
                    })
                    .catch(err => {
                        Swal.fire('Erreur', err.message, 'error');
                    });
                }
            });
        });
    });
});
</script>

<style>
    .wg-box {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
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
    .table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1rem;
    }
    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .btn-sm {
        padding: 0.4rem 1rem;
        font-size: 0.875rem;
    }
    @media (max-width: 768px) {
        .table-responsive { overflow-x: auto; }
        .table th, .table td { min-width: 130px; }
    }
</style>
@endsection
