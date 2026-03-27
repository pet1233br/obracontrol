<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OBRACONTROL - Gestão de Estoque')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    :root {
        --marine-blue: #24273a;
        --marine-dark: #1e2030;
        --marine-light: rgba(36, 39, 58, 0.8);
        --construction-yellow: #ffc107;
        --glass-bg: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.1);
        /* Cor específica para os headers que você pediu */
        --table-header-bg: rgba(0, 0, 0, 0.2); 
    }

    body {
        font-family: 'Oswald', sans-serif;
        background-color: var(--marine-dark);
        color: #ffffff;
        letter-spacing: 0.02em;
        min-height: 100vh;
        background: radial-gradient(circle at top right, #2d314d, var(--marine-dark));
    }

    /* --- Navbar --- */
    .navbar {
        background-color: var(--marine-blue) !important;
        border-bottom: 2px solid var(--construction-yellow);
        padding: 1rem 0;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--construction-yellow) !important;
        text-transform: uppercase;
    }
    .nav-link {
    color: rgba(255, 255, 255, 0.6) !important;
    font-weight: 400;
    display: inline-block; /* Necessário para o scale funcionar bem */
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), color 0.3s ease;
    border-bottom: 2px solid transparent;
}
.nav-link:hover, 
.nav-link.active {
    color: var(--construction-yellow) !important;
    /* Em vez de mudar o peso da fonte, aumentamos o tamanho visual */
    transform: scale(1.1); 
    text-shadow: 0 0 8px rgba(255, 193, 7, 0.4);
}

/* Ajuste no Brand para não perder o destaque */
.navbar-brand {
    color: var(--construction-yellow) !important;
    text-shadow: 0 0 15px rgba(255, 193, 7, 0.2);
}

    /* --- Cards e Headers (Ajuste solicitado) --- */
    .card {
        background: var(--glass-bg) !important;
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border) !important;
        border-radius: 12px;
        overflow: hidden; /* Garante que o header não escape das bordas arredondadas */
    }

    /* Altera a cor da barra de título dos cards (Produtos/Atividades) */
    .card-header {
        background-color: var(--table-header-bg) !important;
        border-bottom: 1px solid var(--glass-border) !important;
        color: var(--construction-yellow) !important;
        font-family: 'Oswald', sans-serif;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* --- Tabelas e Fontes (Ajuste solicitado) --- */
    .table {
        color: #ffffff;
        border-color: var(--glass-border);
        /* Troca a fonte dos itens para uma de melhor leitura */
        font-family: 'Inter', system-ui, -apple-system, sans-serif; 
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .table thead {
        background: var(--table-header-bg);
        color: var(--construction-yellow);
        font-family: 'Oswald', sans-serif; /* Cabeçalho mantém Oswald */
        text-transform: uppercase;
    }

    /* Garante que as linhas da tabela acompanhem o design */
    .table>:not(caption)>*>* {
        background-color: transparent !important;
        color: #ffffff !important;
        border-bottom-color: var(--glass-border) !important;
        padding: 12px 15px;
    }

    /* --- Formulários --- */
    .form-control, .form-select {
        background: rgba(0, 0, 0, 0.2) !important;
        border: 1px solid var(--glass-border) !important;
        color: #ffffff !important;
        border-radius: 8px;
        font-family: 'Inter', sans-serif; /* Fonte de leitura para inputs */
    }
    

    .form-control:focus {
        border-color: var(--construction-yellow);
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.2);
    }

    /* Para combinar com o seu tema escuro */
/* Estiliza o Select para combinar com o tema Marine */
select.form-control {
        background: rgb(0 0 0 / 20%) !important;
    color: #ffffff !important; /* Amarelo Construção */
    border-radius: 8px;
    padding: 10px;
    appearance: none; /* Remove a seta padrão chata */
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 12px;
}

/* Estiliza a barra de rolagem (Scrollbar) interna do dropdown */
select.form-control::-webkit-scrollbar {
    width: 8px;
}

select.form-control::-webkit-scrollbar-track {
    background: #1e2030;
}

select.form-control::-webkit-scrollbar-thumb {
    background: #ffc107;
    border-radius: 10px;
}

/* Estiliza as opções dentro do select */
select option {
    background-color: #24273a;
    color: white;
    padding: 10px;
}

    /* Labels e Divisores */
    label {
        color: var(--construction-yellow);
        text-transform: uppercase;
        font-family: 'Oswald', sans-serif;
    }

    .divider-yellow {
        height: 3px;
        width: 60px;
        background-color: var(--construction-yellow);
        margin-bottom: 20px;
    }

    /* Animação */
    main { animation: pageReveal 0.8s ease-out; }
    @keyframes pageReveal {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Botões */
    .btn-primary {
        background-color: var(--construction-yellow);
        border: none;
        color: var(--marine-blue);
        font-weight: 700;
        font-family: 'Oswald', sans-serif;
    }
    /* --- AJUSTE DA LISTA DE ATIVIDADES / PRODUTOS --- */

/* 1. Remove o fundo branco do corpo do card e da lista */
.card-body.p-0, 
.list-group {
    background-color: #2E3144 !important;
}

/* 2. Estiliza cada item da lista com a cor solicitada */
.list-group-item {
    background-color: #2E3144 !important; /* Cor que você pediu */
    color: #ffffff !important;            /* Texto branco para leitura */
    border-color: rgba(255, 255, 255, 0.05) !important; /* Borda bem sutil */
    padding: 12px 15px;
    font-family: 'Inter', sans-serif;    /* Fonte de leitura */
}

/* 3. Efeito opcional: Destaque ao passar o mouse na linha */
.list-group-item:hover {
    background-color: #363a50 !important; /* Um tom levemente mais claro */
}
/* --- FORÇAR TEXTO AUXILIAR (DATAS/DETALHES) PARA BRANCO --- */

.text-muted, 
.list-group-item small, 
.list-group-item .text-secondary {
    color: rgba(255, 255, 255, 0.7) !important; /* Branco com 70% de opacidade para não brigar com o título */
    --bs-text-opacity: 1;
}

/* Se quiser ele 100% branco brilhante, use este: */
.text-white-50 {
    color: #ffffff !important;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-hard-hat me-2"></i>OBRACONTROL
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Painel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('produtos.*') ? 'active' : '' }}" href="{{ route('produtos.index') }}">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('movimentacoes.*') ? 'active' : '' }}" href="{{ route('movimentacoes.index') }}">Histórico</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-warning" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="background: var(--marine-blue); border: 1px solid var(--construction-yellow);">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container my-5">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>