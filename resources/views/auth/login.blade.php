@extends('layouts.app')

@section('page_title', 'Acesso ao Sistema')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        
        {{-- Card com efeito Glassmorphism --}}
        <div class="card border-0 shadow-lg" style="background: rgba(46, 49, 68, 0.2); backdrop-filter: blur(15px); border-radius: 25px; border: 1px solid rgba(255,255,255,0.05) !important; overflow: hidden;">
            
            {{-- Abas de Alternância --}}
           <ul class="nav nav-pills d-flex justify-content-center gap-3 p-3" id="authTab" role="tablist" style="background: rgba(0,0,0,0.1);">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold text-uppercase px-4 py-2" 
                id="login-tab" data-bs-toggle="pill" data-bs-target="#pane-login" 
                type="button" role="tab" 
                style="border-radius: 10px; font-family: 'Oswald'; font-size: 0.85rem; letter-spacing: 1px;">
            Entrar
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-uppercase px-4 py-2" 
                id="register-tab" data-bs-toggle="pill" data-bs-target="#pane-register" 
                type="button" role="tab" 
                style="border-radius: 10px; font-family: 'Oswald'; font-size: 0.85rem; letter-spacing: 1px;">
            Cadastrar
        </button>
    </li>
</ul>

            <div class="card-body p-4 p-xl-5">
                <div class="tab-content">
                    
                    {{-- FORMULÁRIO LOGIN --}}
                    <div class="tab-pane fade show active" id="pane-login" role="tabpanel">
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">E-mail</label>
                                <div class="position-relative">
                                    <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                                    <input type="email" name="email" class="form-control login-input ps-5 shadow-none" placeholder="seu@email.com" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Senha</label>
                                <div class="position-relative">
                                    <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                                    <input type="password" name="password" class="form-control login-input ps-5 shadow-none" placeholder="••••••••" required>
                                </div>
                            </div>
                            {{-- Botão no Formulário de Login --}}
<button type="submit" class="btn btn-acesso w-100 m-0 py-3 text-uppercase" style="font-family: 'Oswald';">
    Acessar Painel
</button>
                        </form>
                    </div>

                    {{-- FORMULÁRIO CADASTRO --}}
                    <div class="tab-pane fade" id="pane-register" role="tabpanel">
                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.65rem;">Nome</label>
                                <div class="position-relative">
                                    <i class="fas fa-user position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                                    <input type="text" name="name" class="form-control login-input ps-5 shadow-none" placeholder="Nome completo" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.65rem;">E-mail</label>
                                <div class="position-relative">
                                    <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                                    <input type="email" name="email" class="form-control login-input ps-5 shadow-none" placeholder="seu@email.com" required>
                                </div>
                            </div>
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <input type="password" name="password" class="form-control login-input shadow-none" placeholder="Senha" required>
                                </div>
                                <div class="col-6">
                                    <input type="password" name="password_confirmation" class="form-control login-input shadow-none" placeholder="Confirmar" required>
                                </div>
                            </div>
                            {{-- Botão no Formulário de Cadastro --}}
<button type="submit" class="btn btn-acesso w-100 fw-bold py-3 text-uppercase" style="font-family: 'Oswald';">
    Criar Conta
</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. ESTILO DOS BOTÕES DE LOGIN E CADASTRO */
    .btn-acesso {
        background-color: #198754 !important; /* Verde Sucesso */
        color: #ffffff !important;
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        transition: 0.3s;
    }

    .btn-acesso:hover {
        background-color: #157347 !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.5);
    }

    /* 2. AJUSTE DOS BOTÕES DE ALTERNÂNCIA (ENTRAR/CADASTRAR) */
    .nav-pills .nav-link {
        color: #888 !important;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        transition: 0.3s;
    }

    .nav-pills .nav-link.active {
        background-color: #ffc107 !important; /* var(--yellow) */
        color: #000 !important;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
    }

    /* 3. INPUTS E CORREÇÃO DE FUNDO BRANCO (AUTOFILL) */
    .login-input {
        background-color: #24242448 !important;
        border: 1px solid rgba(255,255,255,0.05) !important;
        color: #ffffff !important;
        height: 50px;
        border-radius: 12px !important;
    }

    /* Mata o fundo azul/branco que o Chrome coloca automaticamente */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: #ffffff !important;
        -webkit-box-shadow: 0 0 0px 1000px #1e1e1e inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .login-input:focus {
        border: 1px solid #ffc107 !important;
        background-color: #1e1e1e !important;
        box-shadow: none !important;
    }
</style>
@endsection