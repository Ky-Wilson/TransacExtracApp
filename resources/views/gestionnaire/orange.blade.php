@extends('layouts.gestionnairev2.master')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <!-- Header + Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Analyser des Transactions Orange Money</h3>
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
                    <div class="text-tiny">Upload Multiple</div>
                </li>
            </ul>
        </div>

        <!-- Contenu principal -->
        <div class="wg-box p-0 overflow-hidden">
            <div class="card modern-card shadow-lg border-0 m-0 rounded-0">
                <div class="card-header-gradient">
                    <div class="d-flex align-items-center justify-content-between px-4 py-4">
                        <div>
                            <h4 class="mb-1 text-white fw-bold">
                                <i class='bx bx-money me-2'></i> Analyse Multi-Transactions
                            </h4>
                            <p class="mb-0 text-white-50 small">Orange Money – Jusqu'à 3 reçus à la fois</p>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-mobile-alt'></i>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('manager.orange') }}"
                          enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark mb-3">
                                <i class='bx bx-images me-2'></i> Chargez jusqu'à 3 reçus
                            </label>

                            <div class="upload-container" id="uploadArea">
                                <input type="file"
                                       class="file-input"
                                       id="images"
                                       name="images[]"
                                       accept="image/*"
                                       multiple
                                       required>

                                <div class="upload-placeholder text-center py-5" id="uploadPlaceholder">
                                    <i class='bx bx-cloud-upload mb-3'></i>
                                    <p class="fw-bold mb-2">Glissez 1 à 3 images ici ou cliquez pour sélectionner</p>
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
                                <i class='bx bx-info-circle'></i> Vous pouvez uploader 1, 2 ou 3 images à la fois. Chaque reçu sera analysé indépendamment.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-analyze btn-lg w-100" id="submitBtn" disabled>
                                <i class='bx bx-search-alt-2 me-2'></i>
                                <span>Analyser les transactions</span>
                                <i class='bx bx-right-arrow-alt ms-2'></i>
                            </button>
                        </div>
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

            <!-- Infos supplémentaires -->
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
    min-height: 260px;
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
    pointer-events: none;
}

.upload-placeholder i {
    font-size: 4rem;
    color: #FF8C00;
    display: block;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.upload-placeholder p {
    font-weight: 600;
    font-size: 1.1rem;
    color: #FF6B00;
}

/* Grille de prévisualisation */
.image-preview-grid {
    pointer-events: none;
}

.image-preview-grid .preview-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background: white;
    animation: fadeInScale 0.3s ease-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.image-preview-grid img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    display: block;
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
    pointer-events: all;
    z-index: 10;
    font-size: 18px;
    line-height: 1;
}

.image-preview-grid .remove-single:hover {
    background: #dc3545;
    transform: scale(1.15) rotate(90deg);
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

.btn-analyze:hover:not(:disabled) {
    background: linear-gradient(135deg, #FFA500 0%, #FF8C00 50%, #FF6B00 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 107, 0, 0.5);
    color: white;
}

.btn-analyze:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

/* Styles pour le popup des résultats */
.result-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-left: 5px solid #FF8C00;
    transition: all 0.3s ease;
}

.result-card:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(255, 140, 0, 0.2);
}

.result-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e9ecef;
}

.result-card-number {
    background: linear-gradient(135deg, #FF6B00, #FF8C00);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    box-shadow: 0 3px 10px rgba(255, 107, 0, 0.3);
}

.result-card-type {
    flex: 1;
    margin-left: 15px;
    font-weight: 700;
    font-size: 16px;
    color: #333;
}

.result-card-body {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
}

.result-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.result-item-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: #6c757d;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.result-item-label i {
    font-size: 14px;
    color: #FF8C00;
}

.result-item-value {
    font-size: 15px;
    font-weight: 700;
    color: #212529;
}

.result-item-value.amount {
    color: #28a745;
    font-size: 17px;
}

.result-item-value.balance {
    color: #17a2b8;
}

.result-item-value.fees {
    color: #dc3545;
}

.results-summary {
    background: linear-gradient(135deg, #FF6B00, #FF8C00);
    color: white;
    padding: 15px 20px;
    border-radius: 12px;
    margin-top: 20px;
    text-align: center;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
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

    .image-preview-grid img {
        height: 150px;
    }

    .result-card-body {
        grid-template-columns: 1fr;
    }

    .result-item-value {
        font-size: 14px;
    }

    .result-item-value.amount {
        font-size: 16px;
    }
}

/* Animations SweetAlert custom */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.swal2-container {
    backdrop-filter: blur(5px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('images');
    const uploadArea = document.getElementById('uploadArea');
    const placeholder = document.getElementById('uploadPlaceholder');
    const previewGrid = document.getElementById('imagePreview');
    const submitBtn = document.getElementById('submitBtn');

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
                    <img src="${ev.target.result}" alt="Reçu ${index + 1}">
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

    // Suppression d'une image
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
            uploadArea.style.borderColor = '#FF6B00';
            uploadArea.style.background = 'rgba(255, 107, 0, 0.08)';
        });
    });

    ['dragleave', 'drop'].forEach(event => {
        uploadArea.addEventListener(event, e => {
            e.preventDefault();
            uploadArea.style.borderColor = '#FF8C00';
            uploadArea.style.background = 'linear-gradient(135deg, #FFF8F0 0%, #FFEFE0 100%)';
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

    // Soumission du formulaire
    document.getElementById('uploadForm').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Analyse en cours...
        `;
    });
});
</script>

<!-- SweetAlert pour afficher les résultats multiples -->
@if (session('transaction_results'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const results = @json(session('transaction_results'));

    let html = '<div style="max-height: 60vh; overflow-y: auto; padding: 10px;">';

    results.forEach((data, index) => {
        html += `
            <div class="result-card" style="animation: slideInUp ${0.3 + (index * 0.1)}s ease-out;">
                <div class="result-card-header">
                    <div class="result-card-number">${index + 1}</div>
                    <div class="result-card-type">
                        <i class='bx bx-receipt'></i>
                        ${data.type || 'Transaction'}
                    </div>
                </div>

                <div class="result-card-body">
                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-money'></i>
                            Montant
                        </div>
                        <div class="result-item-value amount">
                            ${data.montant || 'N/A'}
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-user'></i>
                            Expéditeur
                        </div>
                        <div class="result-item-value">
                            ${data.expediteur || 'N/A'}
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-barcode'></i>
                            Référence
                        </div>
                        <div class="result-item-value">
                            ${data.reference || 'N/A'}
                        </div>
                    </div>

                    <div class="result-item">
                        <div class="result-item-label">
                            <i class='bx bx-wallet'></i>
                            Solde
                        </div>
                        <div class="result-item-value balance">
                            ${data.solde || 'N/A'}
                        </div>
                    </div>

                    ${data.frais ? `
                        <div class="result-item">
                            <div class="result-item-label">
                                <i class='bx bx-credit-card'></i>
                                Frais
                            </div>
                            <div class="result-item-value fees">
                                ${data.frais}
                            </div>
                        </div>
                    ` : ''}

                    ${data.date ? `
                        <div class="result-item">
                            <div class="result-item-label">
                                <i class='bx bx-calendar'></i>
                                Date
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
        <div class="results-summary">
            <i class='bx bx-check-circle' style="font-size: 20px; margin-right: 8px;"></i>
            ${results.length} transaction${results.length > 1 ? 's' : ''} analysée${results.length > 1 ? 's' : ''} avec succès
        </div>
    `;

    html += '</div>';

    Swal.fire({
        title: '<strong style="color: #FF8C00;"><i class="bx bx-trophy me-2"></i>Analyse Terminée</strong>',
        html: html,
        icon: 'success',
        iconColor: '#FF8C00',
        confirmButtonText: '<i class="bx bx-check me-2"></i>Super !',
        confirmButtonColor: '#FF8C00',
        width: '900px',
        padding: '2rem',
        customClass: {
            popup: 'animated-popup',
            confirmButton: 'btn-custom-swal'
        },
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
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
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3) !important;
    transition: all 0.3s ease !important;
}

.btn-custom-swal:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(255, 140, 0, 0.5) !important;
}

.swal2-popup {
    border-radius: 20px !important;
}

@media (max-width: 768px) {
    .swal2-popup {
        width: 95% !important;
        padding: 1.5rem !important;
    }
}
</style>
@endif
@endsection
