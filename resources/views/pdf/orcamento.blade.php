<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido de Orçamento - OBRACONTROL</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #ffc107;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1a1a1a;
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 10px 5px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        td {
            padding: 10px 5px;
            border: 1px solid #dee2e6;
            font-size: 10px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .material-name {
            font-weight: bold;
            color: #000;
            display: block;
            margin-bottom: 2px;
        }
        .categoria {
            color: #777;
            font-size: 8px;
            text-transform: uppercase;
        }
        .empresa-tag {
            font-size: 9px;
            color: #555;
            display: block;
            margin-top: 3px;
            font-style: italic;
        }
        .blank-field {
            width: 100%;
            height: 25px;
            border-bottom: 1px solid #ccc;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
            font-size: 10px;
            color: #777;
        }
        .footer strong {
            color: #1a1a1a;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Solicitação de Cotação de Preços</h1>
        <p>Documento Gerado em: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Material / Referência</th>
                <th style="width: 15%;">Qtd. Pedida</th>
                <th style="width: 18%;">Preço Unit. (R$)</th>
                <th style="width: 18%;">Total Item (R$)</th>
                <th style="width: 19%;">Observações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $p)
            <tr>
                <td>
                    <span class="material-name">{{ $p->nome }}</span>
                    <span class="categoria">{{ $p->categoria->nome ?? 'Geral' }}</span>
                    @if($p->empresas->count() > 0)
                        <span class="empresa-tag">Ref: {{ $p->empresas->first()->nome }}</span>
                    @endif
                </td>
                <td class="text-center">
                    <div style="font-weight: bold; font-size: 12px; margin-bottom: 5px;">
                        {{-- Espaço para o usuário escrever a quantidade se quiser, ou mostrar a sugerida --}}
                        _______
                    </div>
                    <small style="color: #999;">unidades</small>
                </td>
                <td><div class="blank-field"></div></td>
                <td><div class="blank-field"></div></td>
                <td><div class="blank-field"></div></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 11px;">
        <p><strong>Condições de Fornecimento:</strong></p>
        <p>Prazo de Entrega: ____________________ | Forma de Pagamento: ____________________</p>
        <p>Validade da Proposta: ____________________</p>
    </div>

    <div class="footer">
        <p>Este documento é uma solicitação formal de cotação de preços.</p>
        <strong>OBRACONTROL - GESTÃO INTELIGENTE DE ESTOQUE</strong><br>
        <span style="font-size: 9px;">Sistema de Controle de Materiais e Insumos</span>
    </div>
</body>
</html>