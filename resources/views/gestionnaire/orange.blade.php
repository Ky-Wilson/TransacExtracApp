@extends('layouts.gestionnaire.master')

@section('content')
<main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card modern-card shadow-lg border-0">
                    <div class="card-header-gradient">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 text-white fw-bold">
                                    <i class='bx bx-money'></i> Analyse Transaction
                                </h4>
                                <p class="mb-0 text-white-50 small">Orange Money</p>
                            </div>
                            <div class="icon-container">
                                <i class='bx bx-mobile-alt'></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('manager.orange') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="mb-4">
                                <label for="image" class="form-label fw-bold text-dark mb-3">
                                    <i class='bx bx-image-add me-2'></i>Upload Image Transaction
                                </label>
                                
                                <div class="upload-container">
                                    <input type="file" 
                                           class="form-control form-control-lg file-input @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*" 
                                           required>
                                    
                                    <div class="upload-placeholder" id="uploadPlaceholder">
                                        <i class='bx bx-cloud-upload'></i>
                                        <p class="mb-0">Cliquez ou glissez une image ici</p>
                                        <small class="text-muted">JPEG, PNG, JPG (max 2MB)</small>
                                    </div>
                                    
                                    <div class="image-preview" id="imagePreview" style="display: none;">
                                        <img src="" alt="Preview" id="previewImg">
                                        <button type="button" class="btn-remove" id="removeBtn">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </div>
                                    
                                    @error('image')
                                        <div class="invalid-feedback d-block">
                                            <i class='bx bx-error-circle'></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-text text-info mt-2">
                                    <i class='bx bx-info-circle'></i> Formats acceptés : JPEG, PNG, JPG (max 2MB)
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-analyze btn-lg w-100">
                                <i class='bx bx-search-alt-2 me-2'></i>
                                <span>Analyser la Transaction</span>
                                <i class='bx bx-right-arrow-alt ms-2'></i>
                            </button>
                        </form>

                        @if (session('success'))
                            <div class="alert alert-success-custom mt-4 fade-in">
                                <i class='bx bx-check-circle me-2'></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger-custom mt-4 fade-in">
                                <i class='bx bx-error-circle me-2'></i>
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="info-cards mt-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-card">
                                <i class='bx bx-shield-quarter'></i>
                                <p>Sécurisé</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-card">
                                <i class='bx bx-timer'></i>
                                <p>Rapide</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Styles personnalisés -->
<style>
/* Carte moderne */
.modern-card {
    border-radius: 20px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
}

/* En-tête avec dégradé Orange Money */
.card-header-gradient {
    background: linear-gradient(135deg, #FF6B00 0%, #FF8C00 50%, #FFA500 100%);
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.card-header-gradient::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 4s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

.icon-container {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.icon-container i {
    font-size: 2rem;
    color: white;
}

/* Container d'upload */
.upload-container {
    position: relative;
    border: 3px dashed #FF8C00;
    border-radius: 15px;
    padding: 2rem;
    background: linear-gradient(135deg, #FFF8F0 0%, #FFEFE0 100%);
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-container:hover {
    border-color: #FF6B00;
    background: linear-gradient(135deg, #FFEFE0 0%, #FFE5CC 100%);
    transform: scale(1.02);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.upload-placeholder {
    text-align: center;
    color: #FF6B00;
}

.upload-placeholder i {
    font-size: 4rem;
    display: block;
    margin-bottom: 1rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.upload-placeholder p {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

/* Prévisualisation d'image */
.image-preview {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
}

.image-preview img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 10px;
}

.btn-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-remove:hover {
    background: rgba(255, 0, 0, 1);
    transform: rotate(90deg) scale(1.1);
}

/* Bouton d'analyse */
.btn-analyze {
    background: linear-gradient(135deg, #FF6B00 0%, #FF8C00 50%, #FFA500 100%);
    border: none;
    color: white;
    font-weight: 600;
    padding: 1rem 2rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-analyze:hover {
    background: linear-gradient(135deg, #FFA500 0%, #FF8C00 50%, #FF6B00 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 107, 0, 0.5);
    color: white;
}

.btn-analyze:active {
    transform: translateY(0);
}

/* Alertes personnalisées */
.alert-success-custom {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.alert-danger-custom {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Cartes d'information */
.info-cards {
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.info-card {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.info-card i {
    font-size: 2.5rem;
    color: #FF8C00;
    display: block;
    margin-bottom: 0.5rem;
}

.info-card p {
    margin: 0;
    font-weight: 600;
    color: #333;
}

/* Responsive */
@media (max-width: 768px) {
    .card-header-gradient {
        padding: 1.5rem;
    }
    
    .icon-container {
        width: 50px;
        height: 50px;
    }
    
    .icon-container i {
        font-size: 1.5rem;
    }
    
    .upload-placeholder i {
        font-size: 3rem;
    }
    
    .btn-analyze {
        font-size: 0.95rem;
        padding: 0.875rem 1.5rem;
    }
}
</style>

<!-- Script pour la prévisualisation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeBtn = document.getElementById('removeBtn');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                uploadPlaceholder.style.display = 'none';
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.value = '';
        uploadPlaceholder.style.display = 'block';
        imagePreview.style.display = 'none';
        previewImg.src = '';
    });
});
</script>

@if (session('transaction_data'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const transactionData = @json(session('transaction_data'));
        Swal.fire({
            title: '<strong>Transaction Analysée</strong>',
            html: `
                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="table table-hover" style="text-align: left;">
                        <tbody>
                            <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <th style="padding: 15px; border-radius: 8px 0 0 0;"><i class='bx bx-category me-2' style="color: #FF8C00;"></i>Type:</th>
                                <td style="padding: 15px; font-weight: 600;">${transactionData.type || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th style="padding: 15px;"><i class='bx bx-money me-2' style="color: #28a745;"></i>Montant:</th>
                                <td style="padding: 15px; font-weight: 600; color: #28a745;">${transactionData.montant || 'N/A'}</td>
                            </tr>
                            <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <th style="padding: 15px;"><i class='bx bx-user me-2' style="color: #FF8C00;"></i>Expéditeur:</th>
                                <td style="padding: 15px; font-weight: 600;">${transactionData.expediteur || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th style="padding: 15px;"><i class='bx bx-receipt me-2' style="color: #FF8C00;"></i>Référence:</th>
                                <td style="padding: 15px; font-weight: 600;">${transactionData.reference || 'N/A'}</td>
                            </tr>
                            <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                <th style="padding: 15px;"><i class='bx bx-wallet me-2' style="color: #17a2b8;"></i>Solde:</th>
                                <td style="padding: 15px; font-weight: 600; color: #17a2b8;">${transactionData.solde || 'N/A'}</td>
                            </tr>
                            ${transactionData.frais ? `<tr><th style="padding: 15px;"><i class='bx bx-credit-card me-2' style="color: #dc3545;"></i>Frais:</th><td style="padding: 15px; font-weight: 600;">${transactionData.frais}</td></tr>` : ''}
                            ${transactionData.date ? `<tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);"><th style="padding: 15px; border-radius: 0 0 0 8px;"><i class='bx bx-calendar me-2' style="color: #FF8C00;"></i>Date:</th><td style="padding: 15px; font-weight: 600; border-radius: 0 0 8px 0;">${transactionData.date}</td></tr>` : ''}
                        </tbody>
                    </table>
                </div>
            `,
            icon: 'success',
            iconColor: '#FF8C00',
            confirmButtonText: '<i class="bx bx-check me-2"></i>Parfait !',
            confirmButtonColor: '#FF8C00',
            width: '600px',
            customClass: {
                popup: 'animated bounceIn',
                title: 'fw-bold',
                confirmButton: 'btn-custom-swal'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    });
</script>

<style>
.btn-custom-swal {
    border-radius: 10px !important;
    padding: 12px 30px !important;
    font-weight: 600 !important;
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3) !important;
}

.btn-custom-swal:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 140, 0, 0.5) !important;
}
</style>
@endif
@endsection