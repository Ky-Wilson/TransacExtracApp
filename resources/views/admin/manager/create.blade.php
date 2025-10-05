@extends('layouts.admin.master')
@section('content')

<style>
    .form-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-container {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .form-container h1 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .form-container .subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 30px;
        font-size: 14px;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
        font-size: 14px;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        margin-top: 10px;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-submit:active {
        transform: translateY(0);
    }
    
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 14px;
    }
    
    .alert-danger {
        background-color: #fee;
        border-left: 4px solid #dc3545;
        color: #721c24;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-left: 4px solid #28a745;
        color: #155724;
    }
    
    .alert ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .alert li {
        margin: 5px 0;
    }
    
    .back-link {
        text-align: center;
        margin-top: 20px;
    }
    
    .back-link a {
        color: #667eea;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease;
    }
    
    .back-link a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-container {
            padding: 30px 25px;
        }
        
        .form-container h1 {
            font-size: 24px;
        }
    }
    
    @media (max-width: 480px) {
        .form-wrapper {
            padding: 15px;
        }
        
        .form-container {
            padding: 25px 20px;
        }
        
        .form-container h1 {
            font-size: 22px;
        }
        
        .form-group input {
            padding: 10px 12px;
        }
        
        .btn-submit {
            padding: 12px;
        }
    }
</style>

<div class="form-wrapper">
    <div class="form-container">
        <h1>Créer un Gestionnaire</h1>
        <p class="subtitle">Remplissez les informations ci-dessous</p>
        
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

        <form method="POST" action="{{ route('admin.create.manager') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Nom du gestionnaire</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ex: Jean Dupont" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email du gestionnaire</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="exemple@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Minimum 8 caractères" required>
            </div>
            
            <button type="submit" class="btn-submit">Créer le gestionnaire</button>
        </form>
        
        <div class="back-link">
            <a href="{{ route('admin.dashboard') ?? '#' }}">← Retour au tableau de bord</a>
        </div>
    </div>
</div>

@endsection