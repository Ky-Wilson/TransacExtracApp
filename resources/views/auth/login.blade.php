@extends('layouts.auth.signin')

@section('content')
<div class="page-header align-items-start min-vh-100" style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);">
    <span class="mask opacity-2"></span>
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-4 col-md-7 col-11 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom shadow-xl border-0">
                    <!-- En-tête élégant -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="header-gradient shadow-lg border-radius-lg py-5">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle icon-box">
                                    <i class="fas fa-wallet icon-main"></i>
                                </div>
                            </div>
                            <h3 class="text-white font-weight-bold text-center mb-1">Connexion</h3>
                            <p class="text-white text-sm text-center opacity-9 mb-0 px-4">Accédez à votre tableau de bord financier</p>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-5 pb-4">
                        <form role="form" class="text-start" method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Messages d'erreur -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
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

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-3" style="font-size: 18px;"></i>
                                        <span>{{ session('error') }}</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Champ Email -->
                            <div class="mb-4">
                                <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Adresse email
                                </label>
                                <div class="input-group input-group-outline">
                                    <input type="email"
                                           class="form-control form-control-lg"
                                           name="email"
                                           placeholder="exemple@email.com"
                                           value="{{ old('email') }}"
                                           required
                                           autofocus>
                                </div>
                            </div>

                            <!-- Champ Mot de passe -->
                            <div class="mb-4">
                                <label class="form-label text-sm font-weight-bold text-dark mb-2">
                                    <i class="fas fa-lock me-2 text-primary"></i>Mot de passe
                                </label>
                                <div class="input-group input-group-outline">
                                    <input type="password"
                                           class="form-control form-control-lg"
                                           name="password"
                                           placeholder="Entrez votre mot de passe"
                                           id="passwordInput"
                                           required>
                                    <span class="input-group-text border-0 bg-transparent pe-3" style="cursor: pointer;" onclick="togglePassword()">
                                        <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Bouton de connexion -->
                            <div class="text-center mt-5">
                                <button type="submit"
                                        class="btn btn-primary w-100 shadow-lg btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Se connecter
                                </button>
                            </div>

                            <!-- Lien d'inscription -->
                            @if (Route::has('register'))
                                <div class="text-center mt-4">
                                    <p class="text-sm mb-0 text-muted">
                                        Vous n'avez pas de compte ?
                                        <a href="{{ route('register') }}" class="text-primary font-weight-bold text-gradient">
                                            Créer un compte <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </form>
                    </div>

                    <!-- Footer sécurité -->
                    <div class="card-footer pt-0 px-4 pb-4 bg-transparent border-top-0">
                        <div class="security-badge">
                            <i class="fas fa-shield-halved me-2"></i>
                            <span>Connexion sécurisée avec chiffrement SSL 256 bits</span>
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="text-center mt-4">
                    <small class="text-white opacity-8">
                        <i class="fas fa-info-circle me-1"></i>
                        Pour toute assistance, contactez notre support
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
</script>

@endsection
