@extends('layouts.gestionnairev2.master')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- Header + Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Analyser des Transactions MTN MoMo</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('gestionnaire.dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                </li>
                <li>
                    <div class="text-tiny">Upload Multiple MTN</div>
                </li>
            </ul>
        </div>

        <!-- Contenu principal -->
        <div class="wg-box p-0 overflow-hidden">
            <div class="card modern-card shadow-lg border-0 m-0 rounded-0">
                <div class="card-header-gradient-mtn">
                    <div class="d-flex align-items-center justify-content-between px-4 py-4">
                        <div>
                            <h4 class="mb-1 text-white fw-bold">
                                <i class='bx bx-mobile me-2'></i> Analyse Multi-Transactions
                            </h4>
                            <p class="mb-0 text-white-50 small">MTN MoMo – Jusqu'à 3 reçus à la fois</p>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-mobile-alt'></i>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('manager.mtn') }}"
                          enctype="multipart/form-data" class="needs-validation" novalidate id="uploadForm">
                        @csrf

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark mb-3">
                                <i class='bx bx-images me-2' style="color: #FFCC00;"></i> Chargez jusqu'à 3 reçus MTN
                            </label>

                            <div class="upload-container position-relative" id="uploadArea">
                                <input type="file"
                                       class="file-input position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer z-2"
                                       id="images"
                                       name="images[]"
                                       accept="image/*"
                                       multiple
                                       required>

                                <div class="upload-placeholder text-center py-5" id="uploadPlaceholder">
                                    <i class='bx bx-cloud-upload fa-4x mb-3' style="color: #FFCC00;"></i>
                                    <p class="fw-bold mb-2" style="color: #FFCC00;">Glissez 1 à 3 images ici ou cliquez pour sélectionner</p>
                                    <small class="text-muted">JPEG, PNG, JPG — max 2 Mo par image</small>
                                </div>

                                <div class="image-preview-grid row g-3 mt-3" id="imagePreview" style="display: none;"></div>

                                @error('images.*')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class='bx bx-error-circle'></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-text text-info mt-3">
                                <i class='bx bx-info-circle' style="color: #00A859;"></i>
                                Vous pouvez uploader 1, 2 ou 3 images à la fois. Chaque reçu MTN sera analysé indépendamment.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-analyze-mtn btn-lg w-100" id="submitBtn" disabled>
                                <i class='bx bx-search-alt-2 me-2'></i>
                                <span>Analyser les transactions MTN</span>
                                <i class='bx bx-right-arrow-alt ms-2'></i>
                            </button>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success-custom mt-4 fade-in" style="background: linear-gradient(135deg, #00A859 0%, #28a745 100%);">
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

            <!-- Infos supplémentaires -->
            <div class="info-cards mt-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="info-card">
                            <i class='bx bx-shield-quarter' style="color: #FFCC00;"></i>
                            <p>Sécurisé</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-card">
                            <i class='bx bx-timer' style="color: #00A859;"></i>
                            <p>Rapide</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

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

    /* En-tête avec dégradé MTN MoMo */
    .card-header-gradient-mtn {
        background: linear-gradient(135deg, #FFCC00 0%, #FFB300 50%, #FFD700 100%);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient-mtn::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .icon-container {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.3);
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

    /* Container d'upload MTN */
    .upload-container {
        position: relative;
        border: 3px dashed #FFCC00;
        border-radius: 15px;
        padding: 2rem;
        background: linear-gradient(135deg, #FFF8E0 0%, #FFF3C7 100%);
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 260px;
    }

    .upload-container:hover {
        border-color: #FFB300;
        background: linear-gradient(135deg, #FFF3C7 0%, #FFEFA0 100%);
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
        color: #FFCC00;
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

    /* Grille de prévisualisation */
    .image-preview-grid .preview-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: white;
    }

    .image-preview-grid img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .image-preview-grid .remove-single {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(220,53,69,0.95);
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .image-preview-grid .remove-single:hover {
        background: #dc3545;
        transform: scale(1.15) rotate(90deg);
    }

    /* Bouton d'analyse MTN */
    .btn-analyze-mtn {
        background: linear-gradient(135deg, #FFCC00 0%, #FFB300 50%, #FFD700 100%);
        border: none;
        color: #000;
        font-weight: 600;
        padding: 1rem 2rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-analyze-mtn:hover:not(:disabled) {
        background: linear-gradient(135deg, #FFD700 0%, #FFB300 50%, #FFCC00 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 204, 0, 0.5);
        color: #000;
    }

    .btn-analyze-mtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Alertes personnalisées MTN */
    .alert-success-custom {
        background: linear-gradient(135deg, #00A859 0%, #28a745 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 168, 89, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-header-gradient-mtn {
            padding: 1.5rem;
        }

        .image-preview-grid img {
            height: 150px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput       = document.getElementById('images');
    const uploadArea      = document.getElementById('uploadArea');
    const placeholder     = document.getElementById('uploadPlaceholder');
    const previewGrid     = document.getElementById('imagePreview');
    const submitBtn       = document.getElementById('submitBtn');

    const MAX_FILES = 3;

    function updatePreview() {
        const files = Array.from(fileInput.files || []);
        previewGrid.innerHTML = '';
        previewGrid.style.display = files.length > 0 ? 'flex' : 'none';
        placeholder.style.display = files.length > 0 ? 'none' : 'block';
        submitBtn.disabled = files.length === 0;

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const col = document.createElement('div');
                col.className = 'col-12 col-sm-6 col-md-4 preview-item';
                col.innerHTML = `
                    <img src="${ev.target.result}" alt="Reçu MTN ${index + 1}">
                    <button type="button" class="remove-single" data-index="${index}">
                        <i class='bx bx-x'></i>
                    </button>
                `;
                previewGrid.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }

    fileInput.addEventListener('change', updatePreview);

    // Suppression individuelle
    previewGrid.addEventListener('click', function(e) {
        if (e.target.closest('.remove-single')) {
            const btn = e.target.closest('.remove-single');
            const index = parseInt(btn.dataset.index);
            const dt = new DataTransfer();
            Array.from(fileInput.files).forEach((file, i) => {
                if (i !== index) dt.items.add(file);
            });
            fileInput.files = dt.files;
            updatePreview();
        }
    });

    // Drag & Drop
    ['dragover', 'dragenter'].forEach(event => {
        uploadArea.addEventListener(event, e => {
            e.preventDefault();
            uploadArea.style.borderColor = '#FFCC00';
            uploadArea.style.background = 'rgba(255, 204, 0, 0.08)';
        });
    });

    ['dragleave', 'drop'].forEach(event => {
        uploadArea.addEventListener(event, e => {
            e.preventDefault();
            uploadArea.style.borderColor = '#FFB300';
            uploadArea.style.background = 'linear-gradient(135deg, #FFF8E0 0%, #FFF3C7 100%)';
        });
    });

    uploadArea.addEventListener('drop', e => {
        const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
        if (files.length > MAX_FILES) {
            alert(`Maximum ${MAX_FILES} images autorisées.`);
            return;
        }
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
        updatePreview();
    });

    // Loader pendant soumission
    document.getElementById('uploadForm').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Analyse en cours...
        `;
    });
});
</script>

<!-- SweetAlert multi-résultats MTN -->
@if (session('transaction_results_mtn'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const results = @json(session('transaction_results_mtn'));

    let html = '<div style="max-height: 60vh; overflow-y: auto; padding: 10px;">';

    results.forEach((data, index) => {
        html += `
            <div class="result-card" style="animation: slideInUp ${0.3 + (index * 0.1)}s ease-out; border-left: 5px solid #FFCC00;">
                <div class="result-card-header">
                    <div class="result-card-number" style="background: linear-gradient(135deg, #FFCC00, #FFB300);">${index + 1}</div>
                    <div class="result-card-type">
                        <i class='bx bx-receipt'></i>
                        ${data.type || 'Transaction MTN'}
                    </div>
                </div>

                <div class="result-card-body">
                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-money'></i> Montant
                        </div>
                        <div class="result-item-value amount">
                            ${data.montant || 'N/A'}
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-user'></i> Expéditeur
                        </div>
                        <div class="result-item-value">
                            ${data.expediteur || 'N/A'}
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-barcode'></i> Référence
                        </div>
                        <div class="result-item-value">
                            ${data.reference || 'N/A'}
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-wallet'></i> Solde
                        </div>
                        <div class="result-item-value balance">
                            ${data.solde || 'N/A'}
                        </div>
                    </div>

                    ${data.frais ? `
                        <div class="result-item">
                            <div class="result-item-label">
                                <i class='bx bx-credit-card'></i> Frais
                            </div>
                            <div class="result-item-value fees">
                                ${data.frais}
                            </div>
                        </div>
                    ` : ''}

                    ${data.date ? `
                        <div class="result-item">
                            <div class="result-item-label">
                                <i class='bx bx-calendar'></i> Date
                            </div>
                            <div class="result-item-value">
                                ${data.date}
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    });

    html += `
        <div class="results-summary" style="background: linear-gradient(135deg, #FFCC00, #FFB300);">
            <i class='bx bx-check-circle' style="font-size: 20px; margin-right: 8px;"></i>
            ${results.length} transaction${results.length > 1 ? 's' : ''} MTN analysée${results.length > 1 ? 's' : ''} avec succès
        </div>
    `;

    html += '</div>';

    Swal.fire({
        title: '<strong style="color: #FFCC00;"><i class="bx bx-trophy me-2"></i>Analyse MTN Terminée</strong>',
        html: html,
        icon: 'success',
        iconColor: '#FFCC00',
        confirmButtonText: '<i class="bx bx-check me-2"></i>Super !',
        confirmButtonColor: '#FFCC00',
        width: '900px',
        padding: '2rem',
        customClass: {
            popup: 'animated-popup',
            confirmButton: 'btn-custom-swal'
        }
    });
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
.btn-custom-swal {
    border-radius: 10px !important;
    padding: 12px 30px !important;
    font-weight: 600 !important;
    background: #FFCC00 !important;
    color: #000 !important;
    box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3) !important;
    transition: all 0.3s ease !important;
}

.btn-custom-swal:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(255, 204, 0, 0.5) !important;
}

.swal2-popup {
    border-radius: 20px !important;
}
</style>
@endif
@endsection
