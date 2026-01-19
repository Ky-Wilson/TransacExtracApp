@extends('layouts.adminv2.master')

@section('content')
<main class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header + Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Créer un Gestionnaire</h3>
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
                    <a href="{{ route('admin.manage.managers') }}">
                        <div class="text-tiny">Gestionnaires</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Nouveau Gestionnaire</div>
                </li>
            </ul>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong>Erreur{{ $errors->count() > 1 ? 's' : '' }} :</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Formulaire dans wg-box -->
        <div class="wg-box">
            <form class="form-new-product form-style-1"
                  method="POST"
                  action="{{ route('admin.create.manager') }}"
                  enctype="multipart/form-data">

                @csrf

                <!-- Nom -->
                <fieldset class="name">
                    <div class="body-title">Nom du gestionnaire <span class="tf-color-1">*</span></div>
                    <input class="flex-grow"
                           type="text"
                           name="name"
                           placeholder="Ex: Jean Dupont"
                           value="{{ old('name') }}"
                           tabindex="0"
                           required>
                </fieldset>

                <!-- Email -->
                <fieldset class="name">
                    <div class="body-title">Adresse email <span class="tf-color-1">*</span></div>
                    <input class="flex-grow"
                           type="email"
                           name="email"
                           placeholder="exemple@email.com"
                           value="{{ old('email') }}"
                           tabindex="0"
                           required>
                </fieldset>

                <!-- Mot de passe -->
                <fieldset class="name">
                    <div class="body-title">Mot de passe <span class="tf-color-1">*</span></div>
                    <input class="flex-grow"
                           type="password"
                           name="password"
                           placeholder="Minimum 8 caractères"
                           tabindex="0"
                           required>
                </fieldset>

                <!-- Confirmation mot de passe (recommandé) -->
                <fieldset class="name">
                    <div class="body-title">Confirmer le mot de passe <span class="tf-color-1">*</span></div>
                    <input class="flex-grow"
                           type="password"
                           name="password_confirmation"
                           placeholder="Confirmez le mot de passe"
                           tabindex="0"
                           required>
                </fieldset>

                <!-- Optionnel : Avatar (si tu veux le permettre dès la création) -->
                <!--
                <fieldset>
                    <div class="body-title">Photo de profil (optionnel)</div>
                    <div class="upload-image flex-grow">
                        <div class="item up-load">
                            <label class="uploadfile" for="avatar">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Sélectionner une image <span class="tf-color">cliquer ici</span></span>
                                <input type="file" id="avatar" name="avatar" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                -->

                <!-- Boutons -->
                <div class="bot d-flex justify-content-end gap10 mt-4">
                    <a href="{{ route('admin.manage.managers') }}"
                       class="tf-button style-2 w208">Annuler</a>
                    <button type="submit" class="tf-button style-1 w208">
                        Créer le gestionnaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
    .form-style-1 .body-title {
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }
    .form-style-1 input {
        height: 48px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0 16px;
        font-size: 15px;
    }
    .form-style-1 input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
    }
    .tf-button.style-1 {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }
    .tf-button.style-2 {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .wg-box {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
</style>
@endsection
