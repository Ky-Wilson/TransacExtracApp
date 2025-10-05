@extends('layouts.auth.auth')

@section('content')
<div class="container my-auto">
    <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                        <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Inscription Admin Entreprise</h4>
                        <div class="row mt-3">
                            <div class="col-2 text-center ms-auto">
                                <a class="btn btn-link px-3" href="javascript:;">
                                    <i class="fa fa-facebook text-white text-lg"></i>
                                </a>
                            </div>
                            <div class="col-2 text-center px-1">
                                <a class="btn btn-link px-3" href="javascript:;">
                                    <i class="fa fa-github text-white text-lg"></i>
                                </a>
                            </div>
                            <div class="col-2 text-center me-auto">
                                <a class="btn btn-link px-3" href="javascript:;">
                                    <i class="fa fa-google text-white text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form role="form" class="text-start" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Champs de l'entreprise -->
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Nom de l'entreprise</label>
                            <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required>
                        </div>
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Email de l'entreprise</label>
                            <input type="email" class="form-control" name="company_email" value="{{ old('company_email') }}" required>
                        </div>
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" class="form-control" name="company_phone" value="{{ old('company_phone') }}">
                        </div>

                        <!-- Champs de l'admin -->
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Nom de l'admin</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Email de l'admin</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <!-- Champ pour uploader l'avatar -->
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label"></label>
                            <input type="file" class="form-control" name="avatar" accept="image/*">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">S'inscrire</button>
                        </div>
                        <p class="mt-4 text-sm text-center">
                            Déjà un compte ?
                            <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Se connecter</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection