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
                    <h3 class="m-0">Liste des Admins</h3>
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
                                <th class="text-center" style="width: 20%; min-width: 160px;">Nombre de Gestionnaires</th>
                                <th class="text-center" style="width: 15%; min-width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                            <tr class="align-middle">
                                <td class="text-center">
                                    <img src="{{ $admin->avatar ? asset($admin->avatar) : asset('assets/avatars/default.png') }}" alt="Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                </td>
                                <td class="text-center">
                                    <p class="m-0">{{ $admin->name }}</p>
                                </td>
                                <td class="text-center">{{ Str::limit($admin->email, 20) }}</td>
                                <td class="text-center">{{ $admin->created_at->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $admin->status ? 'bg-success' : 'bg-warning' }} text-white">
                                        {{ $admin->status ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <p class="m-0">{{ $admin->managers_count }}</p>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm edit-status" data-id="{{ $admin->id }}" data-name="{{ $admin->name }}" data-status="{{ $admin->status }}">
                                        Modifier Statut
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">Aucun admin trouvé.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $admins->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Script pour SweetAlert2 -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-status').forEach(button => {
        button.addEventListener('click', function () {
            const adminId = this.getAttribute('data-id');
            const adminName = this.getAttribute('data-name');
            const currentStatus = this.getAttribute('data-status') === '1';

            Swal.fire({
                title: `Modifier le statut de ${adminName}`,
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

                    return fetch(`{{ url('/superadmin/manage-admins') }}/${adminId}/status`, {
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
                    Swal.fire('Succès!', 'Le statut a été mis à jour pour l\'admin et ses gestionnaires.', 'success').then(() => {
                        location.reload();
                    });
                }
            });
        });
    });
});
</script>
@endsection