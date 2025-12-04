<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Barzinho - Controle de Estoque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>🍺 Controle de Estoque - Barzinho</h1>

    <?php if ($mensagem): ?>
        <div class="mensagem <?php echo $mensagem_tipo; ?>"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <hr>

    <h2>➕ Registrar Novo Produto</h2>
    <form action="index.php" method="POST">
        <input type="hidden" name="acao" value="adicionar_produto">
        
        <div class="form-group-inline">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div>
                <label for="categoria_id">Categoria:</label>
                <select id="categoria_id" name="categoria_id" required>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group-inline">
            <div>
                <label for="preco_custo">Preço Custo:</label>
                <input type="number" id="preco_custo" name="preco_custo" step="0.01" required>
            </div>
            <div>
                <label for="preco_venda">Preço Venda:</label>
                <input type="number" id="preco_venda" name="preco_venda" step="0.01" required>
            </div>
            <div>
                <label for="estoque_inicial">Estoque Inicial:</label>
                <input type="number" id="estoque_inicial" name="estoque_inicial" value="0" required>
            </div>
        </div>
        
        <input type="submit" value="Salvar Produto" class="btn-primary">
    </form>

    <hr>

    <h2>📋 Estoque Atual</h2>
    
    <?php if (empty($produtos)): ?>
        <p>Não há produtos cadastrados.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Custo</th>
                    <th>Estoque Atual</th>
                    <th>Preço Venda (Alterar)</th>
                    <th>Movimentar Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?php echo $produto['id']; ?></td>
                        <td>**<?php echo htmlspecialchars($produto['nome']); ?>**</td>
                        <td><?php echo htmlspecialchars($produto['categoria_nome']); ?></td>
                        <td>R$ <?php echo number_format($produto['preco_custo'], 2, ',', '.'); ?></td>
                        <td>**<?php echo $produto['estoque_atual']; ?>**</td>
                        
                        <td>
                            <form action="index.php" method="POST" class="action-form">
                                <input type="hidden" name="acao" value="alterar_preco">
                                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                <input type="number" name="preco_venda" value="<?php echo $produto['preco_venda']; ?>" step="0.01" style="width: 80px;" required>
                                <input type="submit" value="💰 Alterar" class="btn-action">
                            </form>
                        </td>

                        <td>
                            <form action="index.php" method="POST" class="action-form">
                                <input type="hidden" name="acao" value="movimentar_estoque">
                                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                <input type="number" name="quantidade" placeholder="Qtd" style="width: 50px;" required>
                                <select name="tipo_movimento" required>
                                    <option value="entrada">Entrada (+)</option>
                                    <option value="saida">Saída (-)</option>
                                </select>
                                <input type="submit" value="📦 Mover" class="btn-action">
                            </form>
                        </td>

                        <td>
                            <a href="index.php?acao=deletar&id=<?php echo $produto['id']; ?>" class="link-btn btn-delete" 
                               onclick="return confirm('ATENÇÃO: Deletar produto ID <?php echo $produto['id']; ?> apagará todo seu histórico de movimentação. Tem certeza?');">Deletar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>