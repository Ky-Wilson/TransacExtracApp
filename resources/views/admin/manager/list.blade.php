@extends('layouts.adminv2.master')

@section('content')
<main class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header + Breadcrumbs + Titre -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Gestionnaires de l'Entreprise</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Gestionnaires</div>
                </li>
            </ul>
        </div>

        <!-- Messages de succès -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Boîte principale -->
        <div class="wg-box">
            <!-- Recherche + Bouton Ajouter -->
            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('admin.manage.managers') }}">
                        <fieldset class="name">
                            <input type="text"
                                   placeholder="Rechercher un gestionnaire..."
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   tabindex="2">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>

                <a href="{{ route('admin.create.manager.form') }}"
                   class="tf-button style-1 w208">
                    <i class="icon-plus"></i>Ajouter un gestionnaire
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover shadow-sm rounded" style="white-space: nowrap;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 15%; min-width: 120px;">Avatar</th>
                            <th class="text-center" style="width: 20%; min-width: 150px;">Nom</th>
                            <th class="text-center" style="width: 25%; min-width: 220px;">Email</th>
                            <th class="text-center" style="width: 15%; min-width: 140px;">Date Création</th>
                            <th class="text-center" style="width: 10%; min-width: 100px;">Statut</th>
                            <th class="text-center" style="width: 15%; min-width: 140px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($managers as $manager)
                            <tr class="align-middle">
                                <td class="text-center">
                                    <img src="{{ $manager->avatar ? asset($manager->avatar) : asset('assets/avatars/default.png') }}"
                                         alt="Avatar de {{ $manager->name }}"
                                         class="rounded-circle"
                                         style="width: 48px; height: 48px; object-fit: cover;">
                                </td>
                                <td class="text-center">
                                    <p class="m-0 fw-medium">{{ $manager->name }}</p>
                                </td>
                                <td class="text-center">
                                    {{ Str::limit($manager->email, 28, '...') }}
                                </td>
                                <td class="text-center">
                                    {{ $manager->created_at->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $manager->status ? 'bg-success' : 'bg-warning' }} text-white px-3 py-2">
                                        {{ $manager->status ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="text-center action-column">
                                    <button type="button"
                                            class="edit-status btn btn-sm text-white"
                                            data-id="{{ $manager->id }}"
                                            data-name="{{ $manager->name }}"
                                            data-status="{{ $manager->status }}"
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; box-shadow: 0 2px 6px rgba(102,126,234,0.3); transition: all 0.25s;">
                                        <i class='bx bx-edit-alt me-1'></i> Modifier
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Aucun gestionnaire trouvé.
                                    @if(request('search'))
                                        <br><small>Essayez un autre terme de recherche.</small>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divider my-4"></div>

            <!-- Pagination -->
            <div class="flex items-center justify-center flex-wrap gap10 wgp-pagination">
                {{ $managers->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</main>

<!-- Script SweetAlert pour modifier le statut -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-status').forEach(button => {
        button.addEventListener('click', function () {
            const managerId   = this.dataset.id;
            const managerName = this.dataset.name;
            const isActive    = this.dataset.status === '1';

            Swal.fire({
                title: `Statut de ${managerName}`,
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

                    fetch(`/admin/manage-managers/${managerId}/status`, {
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
    .table { white-space: nowrap !important; }
    .wg-box { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
    .tf-button.style-1 {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 0.6rem 1.4rem;
        border-radius: 0.5rem;
    }
    .tf-button.style-1:hover { opacity: 0.92; transform: translateY(-1px); }
    @media (max-width: 768px) {
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table th, .table td { min-width: 130px; padding: 0.65rem !important; }
    }
</style>
@endsection
