@extends('layouts.auth.auth')

@section('content')
<div class="page-header min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-12">
                <div class="card z-index-0 fadeIn3 fadeInBottom shadow-xl border-0">
                    <!-- En-tête élégant -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="header-gradient shadow-lg border-radius-lg py-4">
                            <div class="text-center mb-2">
                                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle icon-box">
                                    <i class="fas fa-building icon-main"></i>
                                </div>
                            </div>
                            <h3 class="text-white font-weight-bold text-center mb-1">Inscription Entreprise</h3>
                            <p class="text-white text-sm text-center opacity-9 mb-0 px-3">Créez votre compte administrateur</p>
                        </div>
                    </div>

                    <div class="card-body px-lg-4 px-3 pt-4 pb-3">
                        <form role="form" class="text-start" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Messages d'erreur -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle me-3 mt-1" style="font-size: 18px;"></i>
                                        <div class="flex-grow-1">
                                            @foreach ($errors->all() as $error)
                                                <div class="mb-1">{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle me-3" style="font-size: 18px;"></i>
                                        <span>{{ session('success') }}</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Section Entreprise -->
                            <div class="section-divider mb-3">
                                <i class="fas fa-building me-2"></i>
                                <span>Informations de l'entreprise</span>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-briefcase me-2 text-primary"></i>Nom de l'entreprise
                                    </label>
                                    <div class="input-group input-group-outline">
                                        <input type="text"
                                               class="form-control"
                                               name="company_name"
                                               placeholder="Ex: Ma Société SARL"
                                               value="{{ old('company_name') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-envelope me-2 text-primary"></i>Email entreprise
                                    </label>
                                    <div class="input-group input-group-outline">
                                        <input type="email"
                                               class="form-control"
                                               name="company_email"
                                               placeholder="contact@entreprise.com"
                                               value="{{ old('company_email') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-phone me-2 text-primary"></i>Téléphone
                                    </label>
                                    <div class="input-group input-group-outline">
                                        <input type="text"
                                               class="form-control"
                                               name="company_phone"
                                               placeholder="+225 XX XX XX XX XX"
                                               value="{{ old('company_phone') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Section Administrateur -->
                            <div class="section-divider mb-3 mt-3">
                                <i class="fas fa-user-shield me-2"></i>
                                <span>Compte administrateur</span>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-user me-2 text-primary"></i>Nom complet
                                    </label>
                                    <div class="input-group input-group-outline">
                                        <input type="text"
                                               class="form-control"
                                               name="name"
                                               placeholder="Jean Dupont"
                                               value="{{ old('name') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-at me-2 text-primary"></i>Email administrateur
                                    </label>
                                    <div class="input-group input-group-outline">
                                        <input type="email"
                                               class="form-control"
                                               name="email"
                                               placeholder="admin@entreprise.com"
                                               value="{{ old('email') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-lock me-2 text-primary"></i>Mot de passe
                                    </label>
                                    <div class="input-group input-group-outline">
                                        <input type="password"
                                               class="form-control"
                                               name="password"
                                               placeholder="Minimum 8 caractères"
                                               id="passwordInput"
                                               required>
                                        <span class="input-group-text border-0 bg-transparent pe-3" style="cursor: pointer;" onclick="togglePassword()">
                                            <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-check-circle me-2 text-primary"></i>Confirmation
                                    </label>
                                    <div class="input-group input-group-outline">
                                        <input type="password"
                                               class="form-control"
                                               name="password_confirmation"
                                               placeholder="Confirmez le mot de passe"
                                               id="passwordConfirmInput"
                                               required>
                                        <span class="input-group-text border-0 bg-transparent pe-3" style="cursor: pointer;" onclick="togglePasswordConfirm()">
                                            <i class="fas fa-eye text-muted" id="toggleIconConfirm"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Avatar Upload -->
                                <div class="col-12 mb-3">
                                    <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                        <i class="fas fa-camera me-2 text-primary"></i>Photo de profil (optionnel)
                                    </label>
                                    <div class="upload-zone" id="uploadZone">
                                        <input type="file"
                                               class="d-none"
                                               name="avatar"
                                               id="avatarInput"
                                               accept="image/*"
                                               onchange="previewAvatar(event)">
                                        <div class="upload-content" id="uploadContent">
                                            <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 2rem; color: #6c757d;"></i>
                                            <p class="mb-1 text-sm font-weight-bold">Cliquez ou glissez une image</p>
                                            <p class="text-xs text-muted mb-0">PNG, JPG jusqu'à 5MB</p>
                                        </div>
                                        <div class="preview-content d-none" id="previewContent">
                                            <img id="avatarPreview" src="" alt="Aperçu" class="preview-image">
                                            <button type="button" class="btn-remove" onclick="removeAvatar()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton d'inscription -->
                            <div class="text-center mt-4">
                                <button type="submit"
                                        class="btn btn-primary w-100 shadow-lg btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Créer le compte
                                </button>
                            </div>

                            <!-- Lien de connexion -->
                            <div class="text-center mt-3 mb-2">
                                <p class="text-sm mb-0 text-muted">
                                    Vous avez déjà un compte ?
                                    <a href="{{ route('login') }}" class="text-primary font-weight-bold text-gradient">
                                        Se connecter <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Footer sécurité -->
                    <div class="card-footer pt-0 px-4 pb-3 bg-transparent border-top-0">
                        <div class="security-badge">
                            <i class="fas fa-shield-halved me-2"></i>
                            <span class="d-none d-sm-inline">Vos données sont protégées avec un chiffrement SSL 256 bits</span>
                            <span class="d-inline d-sm-none">Données sécurisées SSL</span>
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="text-center mt-3">
                    <small class="text-white opacity-8">
                        <i class="fas fa-info-circle me-1"></i>
                        L'inscription est gratuite et ne prend que quelques minutes
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('passwordInput');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function togglePasswordConfirm() {
    const passwordInput = document.getElementById('passwordConfirmInput');
    const toggleIcon = document.getElementById('toggleIconConfirm');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('uploadContent').classList.add('d-none');
            document.getElementById('previewContent').classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
}

function removeAvatar() {
    document.getElementById('avatarInput').value = '';
    document.getElementById('uploadContent').classList.remove('d-none');
    document.getElementById('previewContent').classList.add('d-none');
}

// Drag and drop functionality
const uploadZone = document.getElementById('uploadZone');
const avatarInput = document.getElementById('avatarInput');

uploadZone.addEventListener('click', () => {
    if (!document.getElementById('previewContent').classList.contains('d-none')) return;
    avatarInput.click();
});

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('drag-over');
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.classList.remove('drag-over');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        avatarInput.files = e.dataTransfer.files;
        previewAvatar({ target: { files: [file] } });
    }
});
</script>

@endsection
