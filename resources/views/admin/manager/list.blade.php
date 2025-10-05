@extends('layouts.admin.master')
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
            <div class="head d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0">Gestionnaires de l'Entreprise</h3>
                <div>
                    <i class='bx bx-search me-2'></i>
                    <i class='bx bx-filter'></i>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover shadow-sm rounded" style="white-space: nowrap;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 15%; min-width: 120px;">Avatar</th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">Nom</th>
                            <th class="text-center" style="width: 25%; min-width: 200px;">Email</th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">Date de Création</th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">Statut</th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">Action</th>
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
                            <td class="text-center action-column">
                                <button type="button" class="edit-status" data-id="{{ $manager->id }}" data-name="{{ $manager->name }}" data-status="{{ $manager->status }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; font-weight: 500; padding: 0.375rem 0.75rem; border-radius: 0.25rem; box-shadow: 0 2px 5px rgba(102, 126, 234, 0.3); transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.background='linear-gradient(135deg, #764ba2 0%, #667eea 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.5)'" onmouseout="this.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 5px rgba(102, 126, 234, 0.3)'" onmousedown="this.style.transform='translateY(0)'" onmouseup="this.style.transform='translateY(-2px)'">
                                    <i class='bx bx-edit-alt'></i> Modifier Statut
                                </button>
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
            <!-- Pagination avec Bootstrap -->
            <div class="d-flex justify-content-center mt-4">
                {{ $managers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</main>

<!-- Script pour SweetAlert2 -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-status').forEach(button => {
        button.addEventListener('click', function () {
            const managerId = this.getAttribute('data-id');
            const managerName = this.getAttribute('data-name');
            const currentStatus = this.getAttribute('data-status') === '1';

            Swal.fire({
                title: `Modifier le statut de ${managerName}`,
                html: `
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="status" value="1" ${currentStatus ? 'checked' : ''}>
                        <label class="form-check-label">Actif</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="status" value="0" ${!currentStatus ? 'checked' : ''}>
                        <label class="form-check-label">Inactif</label>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Enregistrer',
                cancelButtonText: 'Annuler',
                preConfirm: () => {
                    const statusInput = document.querySelector('input[name="status"]:checked');
                    if (!statusInput) {
                        Swal.showValidationMessage('Veuillez sélectionner un statut.');
                        return false;
                    }
                    const status = statusInput.value;
                    
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
                    
                    return fetch(`/admin/manage-managers/${managerId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ status: status })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la mise à jour');
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Erreur : ${error.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Succès!', 'Le statut a été mis à jour.', 'success').then(() => {
                        location.reload();
                    });
                }
            });
        });
    });
});
</script>

<!-- Styles personnalisés pour éviter les retours à la ligne -->
<style>
    .table {
        white-space: nowrap !important;
    }
    @media (max-width: 768px) {
        .table {
            display: table !important; /* Forcer la table à rester en ligne */
            width: 100% !important; /* S'assurer que la table prend toute la largeur */
        }
        .table th,
        .table td {
            min-width: 120px !important; /* Minimum pour éviter la compression */
            padding: 0.5rem !important;
        }
        .table-responsive {
            overflow-x: auto !important; /* Défilement horizontal uniquement */
            -webkit-overflow-scrolling: touch; /* Défilement fluide sur mobile */
        }
    }
</style>
@endsection