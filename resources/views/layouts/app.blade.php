<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OBRACONTROL - Gestão de Estoque')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #0a0a0a;
            --sidebar: #111111;
            --content: #1a1a1a;
            --yellow: #ffc107;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #20284a;
            background: linear-gradient(319deg, rgba(32, 40, 74, 1) 0%, rgba(15, 15, 61, 1) 100%);
            color: #ffffff;
            margin: 0;

            overflow-y: auto;
            /* O scroll agora pertence à página inteira */
            overflow-x: hidden;
        }

        /* ==================== SIDEBAR & NAV ==================== */


        .logo {
            color: var(--yellow);
            font-family: 'Oswald', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
        }

        .modal {
            z-index: 1060 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }


        .top-nav {
            height: 70px;
            background-color: transparent;
            position: fixed;
            left: 260px;
            right: 0;
            top: 0;
            display: flex;
            align-items: center;
            padding: 0 40px;
            z-index: 999;
        }

        /* ==================== MAIN CONTENT & SCROLLBAR ==================== */
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            /* Remova o height fixo e o overflow daqui */
            min-height: calc(100vh - 70px);
            padding: 3vh;
            background-color: transparent;
            overflow: visible;
            /* Deixe o conteúdo "vazar" para o body scrollar */
        }

        /* Customização da Barra (Para ela não parecer um corte no meio) */
        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .main-content::-webkit-scrollbar-track {
            background: transparent;
            /* Remove a calha cinza que marca o início da div */
        }

        .main-content::-webkit-scrollbar-thumb {
            background: rgba(255, 193, 7, 0.2);
            /* Amarelo discreto */
            border-radius: 10px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background: var(--yellow);
            /* Brilha ao passar o mouse */
        }

        .content-box {
            background: #0D1031;
            background: linear-gradient(135deg, rgb(21, 24, 71) 0%, rgba(21, 24, 59, 1) 100%);
            /* Mantenha o alpha (0.7) para transparência */
            border-radius: 44px;

            min-height: 100%;
            padding: 20px;


        }

        /* ==================== FORM FIXES ==================== */
        .form-control.bg-dark,
        .form-select.bg-dark {
            background-color: #24242448 !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: none !important;
        }

        .icon-seta-detalhe {
            transition: transform 0.4s ease;
            opacity: 0.3;
        }

        .product-card.active .icon-seta-detalhe {
            transform: rotate(180deg);
            opacity: 1;
            color: #ffc107;
        }

        /* Melhorar aparência do select escuro */
        /* Fix Definitivo para Categoria Dark */
        .form-select.tom-select,
        select.tom-select {
            /* Cor de fundo: o seu "Marine Blue" transparente */
            background-color: #24242448 !important;

            /* Borda e Arredondamento */
            border: none !important;
            border-radius: 10px !important;

            /* Texto em Branco */
            color: #ffffff !important;

            /* Alinhamento e Altura */
            height: 50px !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 15px !important;

            /* Seta Branca (FontAwesome) */
            background-image: none !important;
            /* Mata a seta preta nativa */
            position: relative;
        }

        /* Criando a Seta Branca Elegante */
        .form-select.tom-select::after,
        select.tom-select::after {
            content: "\f107" !important;
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
            position: absolute !important;
            right: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .ts-wrapper.tom-select {
            width: 100% !important;
            display: block !important;
        }

        .ts-control {
            width: 100% !important;
        }




        /* dropdown padronizado */
        /* 1. O CAMPO FECHADO (Respeita o col-md-X do Bootstrap) */
        .ts-wrapper.tom-select {
            width: 100% !important;
            /* Faz o dropdown ocupar toda a coluna do Bootstrap */
            background: transparent !important;
        }

        .ts-wrapper.tom-select .ts-control {
            background-color: #24242448 !important;
            /* Seu Marine Blue transparente */

            border-radius: 12px !important;
            padding: 12px 15px !important;
            color: #ffffff !important;
            min-height: 50px !important;
            display: flex !important;
            align-items: center !important;
            transition: 0.3s ease !important;
        }

        .form-control:focus {
            color: #fff !important;
        }

        /* 2. A LISTA DE OPÇÕES (Dropdown aberto) */
        .ts-wrapper.tom-select .ts-dropdown {
            background-color: #1a1c3d !important;
            /* Azul escuro sólido para não vazar fundo */
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            margin-top: 8px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        }

        .ts-wrapper.tom-select .ts-dropdown .option {
            padding: 12px 15px !important;
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* 3. HOVER / SELEÇÃO (Seu Amarelo Construção) */
        .ts-wrapper.tom-select .ts-dropdown .active {
            background-color: var(--yellow) !important;
            color: #000000 !important;
            font-weight: 700 !important;
        }

        /* Garante que o dropdown tenha altura suficiente para todas as opções */
        .ts-dropdown.tom-select .ts-dropdown-content {
            max-height: 300px !important;
            overflow-y: auto !important;
            padding: 5px 0 !important;
        }

        /* Garante que a opção "TODAS" (vazia) não seja escondida pelo CSS */
        .ts-dropdown .option[data-value=""],
        .ts-dropdown .option:first-child {
            display: block !important;
            min-height: 40px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            /* Divisor sutil */
        }






        /* Sidebar com o design da imagem mas as suas cores */
        .sidebar-modern {
            width: 260px;
            background: rgba(32, 40, 74, 0.2);
            /* Seu tom de Marine Blue com transparência */
            backdrop-filter: blur(15px);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 15px;
            z-index: 1100;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-label {
            color: rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin: 20px 0 10px 15px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            text-decoration: none !important;
            color: rgba(255, 255, 255, 0.5);
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 6px;
        }

        /* Hover e Ativo usando seu Amarelo Construção */
        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 193, 7, 0.1);
            /* Fundo amarelado sutil */
            color: var(--yellow);
            transform: scale(1.02);
            /* Sua animação favorita */
        }

        /* Quadrado do ícone estilo premium */
        .icon-box {
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            transition: 0.3s;
        }

        .nav-item.active .icon-box {
            background: var(--yellow);
            color: #15183b;
            /* Cor do fundo para contraste */
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }

        /* Card de Usuário no rodapé */
        /* Botão de Usuário 100% Clicável Estilo Original */
        /* Card de Usuário Original (Agora como Botão Clicável) */
        .user-card-premium {
            background: rgba(255, 255, 255, 0.03);
            padding: 12px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
            outline: none !important;
        }

        /* Remove a setinha nativa do Bootstrap */
        .user-card-premium::after {
            display: none;
        }

        .user-card-premium:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: scale(1.02);
            /* Sua animação favorita */
        }

        .avatar-yellow {
            width: 35px;
            height: 35px;
            background: var(--yellow);
            /* #ffc107 */
            color: #15183b;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-name {
            font-size: 0.8rem;
            color: #fff;
            font-weight: 600;
        }

        .user-role {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.4);
        }

        /* O Ícone de "Desligar" com efeito de cor */
        .logout-icon-visual {
            color: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
            font-size: 0.9rem;
        }

        .user-card-premium:hover .logout-icon-visual {
            color: #ff416c;
            /* Fica vermelho no hover do card inteiro */
            text-shadow: 0 0 8px rgba(255, 65, 108, 0.4);
        }



















        /* ==================== TOM SELECT DARK FIX ==================== */
        /* ==================== FIX FINAL TOM SELECT (SEM SETA DUPLA) ==================== */


        .ts-wrapper.tom-select,
        .ts-wrapper.form-select,
        .ts-wrapper.single .ts-control {
            background-image: none !important;
            /* Remove a seta do Bootstrap */
            padding: 0 !important;
        }

        .ts-wrapper.single .ts-control input {
            margin-left: 8px !important;
            /* Dá o espaço para o cursor não colar no texto */
            color: #fff !important;
            display: inline-block !important;
            opacity: 1 !important;
        }

        .ts-control {
            background-color: #24242448 !important;
            /* Seu fundo dark com transparência */
            border: none;
            border-radius: 10px !important;
            height: 50px !important;
            display: flex !important;
            align-items: center !important;
            color: #fff !important;

            box-shadow: none !important;
            position: relative !important;



        }

        /* Garante que o input de busca não fique com fundo branco ao focar */
        .ts-wrapper.single.focus .ts-control {
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2) !important;
            /* Brilho amarelo ao focar */
        }

        /* Corrige o espaçamento do texto dentro do select para alinhar com "Nome do Produto" */
        .ts-wrapper.single .ts-control .item {
            padding-left: 5px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .ts-wrapper.single .ts-control .item {
            padding-left: 15px !important;
            /* Ajuste fino para alinhar com o placeholder/texto dos outros campos */
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
            font-size: 0.9rem !important;
            /* Mesma fonte dos outros inputs */
            color: #ffffff !important;
        }

        /* 2. A Seta Oficial (FontAwesome) - ÚNICA E EXCLUSIVA */
        .ts-wrapper.single .ts-control::after {
            content: "\f107" !important;
            /* Ícone da FontAwesome */
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;

            /* RESET TOTAL DOS TRIÂNGULOS PADRÃO */
            display: block !important;
            border: none !important;
            /* Mata o triângulo de bordas do TomSelect */
            width: auto !important;
            height: auto !important;
            margin-top: 0 !important;
            /* Remove desalinhamento padrão */

            /* POSICIONAMENTO PERFEITO */
            position: absolute !important;
            right: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #666 !important;
            transition: transform 0.3s ease !important;
        }

        /* Gira a seta quando o menu abre */
        .ts-wrapper.single.dropdown-active .ts-control::after {
            transform: translateY(-50%) rotate(180deg) !important;
            color: var(--yellow) !important;
        }

        /* 3. Dropdown (Fundo Preto sem vazamento) */
        .ts-dropdown {
            background-color: #111 !important;
            border: 1px solid #333 !important;
            border-radius: 10px !important;
            margin-top: 5px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        }

        .ts-dropdown .option {
            padding: 10px 15px !important;
            color: #eee !important;
            margin: 3px 6px !important;
            border-radius: 8px !important;
            transition: 0.2s !important;
        }

        .ts-dropdown .active {
            background-color: #ffc107 !important;
            /* Sua cor amarela */
            color: #000 !important;
        }

        /* 4. Limpa o input de busca (evita o quadrado branco) */
        .ts-control input {
            background: transparent !important;
            color: #fff !important;
            border: none !important;
            box-shadow: none !important;
        }
    </style>
</head>

<body>

    <aside class="sidebar-modern d-flex flex-column">
        <div class="logo">
            <i class="fas fa-hard-hat me-2"></i> OBRACONTROL
        </div>

        <nav class="flex-grow-1 nav-container">
            <p class="nav-label">Navegação</p>

            <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                <span class="nav-text">PAINEL</span>
            </a>

            <a class="nav-item {{ request()->routeIs('produtos.*') ? 'active' : '' }}" href="{{ route('produtos.index') }}">
                <div class="icon-box"><i class="fas fa-boxes"></i></div>
                <span class="nav-text">MEUS PRODUTOS</span>
            </a>

            <p class="nav-label mt-4">Registros</p>

            <a class="nav-item {{ request()->routeIs('movimentacoes.*') ? 'active' : '' }}" href="{{ route('movimentacoes.index') }}">
                <div class="icon-box"><i class="fas fa-history"></i></div>
                <span class="nav-text">HISTÓRICO</span>
            </a>

            <a class="nav-item {{ request()->routeIs('empresas.*') ? 'active' : '' }}" href="{{ route('empresas.index') }}">
                <div class="icon-box"><i class="fas fa-building"></i></div>
                <span class="nav-text">FORNECEDORES</span>
            </a>
        </nav>

        <div class="sidebar-footer mt-auto">
            @auth
            <div class="dropdown mt-auto">
                <button class="user-card-premium w-100 dropdown-toggle border-0" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="avatar-yellow">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="ms-2 text-start">
                                <p class="user-name mb-0">{{ Auth::user()->name }}</p>
                                <p class="user-role mb-0">Administrador</p>
                            </div>
                        </div>
                        <i class="fas fa-power-off logout-icon-visual"></i>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-dark shadow-lg w-100" style="background-color: #1a1a1a; border-radius: 15px; margin-bottom: 10px;">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger small py-2">
                                <i class="fas fa-sign-out-alt me-2"></i>Sair do Sistema
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </aside>

    <div class="top-nav">
        <h2 style="margin: 0; font-weight: 600; font-size: 1.45rem; text-transform: uppercase;">@yield('page_title', 'Painel')</h2>
    </div>

    <div class="main-content">
        <div class="content-box">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    {{-- Isso é vital para que scripts de páginas específicas funcionem --}}
    @stack('scripts')
    {{-- 1. ADICIONE ESSA LINHA AQUI EMBAIXO --}}
    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Seu código do TomSelect (mantenha ele aqui)
            document.querySelectorAll('.tom-select').forEach((el) => {
                // ... config do tomselect ...
            });

            // 2. O NOVO CÓDIGO DOS MODAIS (Adicione aqui)
            const modais = document.querySelectorAll('.modal');
            modais.forEach(modal => {
                document.body.appendChild(modal);
            });
        });
    </script>
</body>