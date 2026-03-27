<!DOCTYPE html>
<html>
<head>
    <title>Orçamento de Compra</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #000; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEDIDO DE ORÇAMENTO DE MATERIAL</h1>
        <p>Data: {{ date('d/m/Y') }} | Empresa: Sua Construtora LTDA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Qtd. Atual</th>
                <th>Qtd. Solicitada</th>
                <th>Preço Unit. (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $p)
            <tr>
                <td>{{ $p->nome }}</td>
                <td>{{ $p->categoria->nome ?? 'Geral' }}</td>
                <td>{{ $p->quantidade }}</td>
                <td>__________</td>
                <td>__________</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este documento é apenas uma solicitação de cotação de preços.</p>
    </div>
</body>
</html>