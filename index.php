<?php
include 'conexao.php';

$mensagem = "";
$mensagem_tipo = "";

// Função para obter todas as categorias
function getCategorias($conexao) {
    $sql = "
        -- Insira aqui a consulta SQL para selecionar todas as categorias (id e nome)
    ";
    $resultado = mysqli_query($conexao, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}


// 1. REGISTRAR NOVO PRODUTO (CREATE)
if (isset($_POST['acao']) && $_POST['acao'] == 'adicionar_produto') {
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $preco_custo = (float)$_POST['preco_custo'];
    $preco_venda = (float)$_POST['preco_venda'];
    $estoque_inicial = (int)$_POST['estoque_inicial'];
    $categoria_id = (int)$_POST['categoria_id'];

    // O estoque_atual deve ser o estoque_inicial
    $sql_insert = "
        -- Insira aqui a consulta SQL para adicionar um novo produto.
    ";

    if (mysqli_query($conexao, $sql_insert)) {
        $novo_produto_id = mysqli_insert_id($conexao);
        
        // Se o estoque inicial > 0, registra a movimentação
        if ($estoque_inicial > 0) {
            $sql_movimentacao = "
                -- Insira aqui a consulta SQL para registrar a ENTRADA inicial no histórico.
            ";
            mysqli_query($conexao, $sql_movimentacao);
        }

        $mensagem = "Produto **$nome** adicionado com sucesso!";
        $mensagem_tipo = "success";
    } else {
        $mensagem = "Erro ao adicionar produto: " . mysqli_error($conexao);
        $mensagem_tipo = "error";
    }
}

// 2. ALTERAR PREÇO DE VENDA (UPDATE)
if (isset($_POST['acao']) && $_POST['acao'] == 'alterar_preco') {
    $id = (int)$_POST['id'];
    $novo_preco_venda = (float)$_POST['preco_venda'];

    $sql_update_preco = "
        -- Insira aqui a consulta SQL para atualizar APENAS o preco_venda do produto pelo ID.
    ";

    if (mysqli_query($conexao, $sql_update_preco)) {
        $mensagem = "Preço de venda do produto ID **$id** atualizado!";
        $mensagem_tipo = "success";
    } else {
        $mensagem = "Erro ao alterar preço: " . mysqli_error($conexao);
        $mensagem_tipo = "error";
    }
}

// 3. MOVIMENTAR ESTOQUE (UPDATE + INSERT MOVIMENTACAO)
if (isset($_POST['acao']) && $_POST['acao'] == 'movimentar_estoque') {
    $id = (int)$_POST['id'];
    $quantidade = (int)$_POST['quantidade'];
    $tipo_movimento = $_POST['tipo_movimento']; // 'entrada' ou 'saida'
    $valor_ajuste = ($tipo_movimento == 'entrada') ? $quantidade : -$quantidade;

    // 3.1. Atualiza o estoque na tabela 'produtos'
    // Deve usar $valor_ajuste para aumentar ou diminuir o estoque_atual
    $sql_update_estoque = "
        -- Insira aqui a consulta SQL para atualizar o estoque_atual do produto (somando ou subtraindo $quantidade)
    ";
    
    // 3.2. Registra a movimentação na tabela 'movimentacoes_estoque'
    $sql_insert_mov = "
        -- Insira aqui a consulta SQL para registrar a movimentação no histórico.
    ";

    if (mysqli_query($conexao, $sql_update_estoque) && mysqli_query($conexao, $sql_insert_mov)) {
        $mensagem = "$quantidade unidades registradas como **" . strtoupper($tipo_movimento) . "** para o produto ID **$id**.";
        $mensagem_tipo = "success";
    } else {
        $mensagem = "Erro ao movimentar estoque: " . mysqli_error($conexao);
        $mensagem_tipo = "error";
    }
}

// 4. DELETAR PRODUTO (DELETE)
if (isset($_GET['acao']) && $_GET['acao'] == 'deletar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 4.1. Deleta as movimentações (para satisfazer a FK)
    $sql_delete_mov = "
        -- Insira aqui a consulta SQL para deletar as movimentações associadas ao produto.
    ";
    mysqli_query($conexao, $sql_delete_mov); // Tenta deletar movimentações primeiro

    // 4.2. Deleta o produto
    $sql_delete_prod = "
        -- Insira aqui a consulta SQL para deletar o produto.
    ";

    if (mysqli_query($conexao, $sql_delete_prod)) {
        $mensagem = "Produto ID **$id** e suas movimentações deletados com sucesso!";
        $mensagem_tipo = "success";
        header("Location: index.php"); // Redireciona para limpar URL
        exit();
    } else {
        $mensagem = "Erro ao deletar produto: " . mysqli_error($conexao);
        $mensagem_tipo = "error";
    }
}

// 5. VISUALIZAR LISTA DE PRODUTOS (READ)
$sql_select_produtos = "
    -- Insira aqui a consulta SQL para selecionar todos os produtos.
    -- Deve incluir o nome da categoria usando JOIN na tabela 'categorias'.
";

$resultado_produtos = mysqli_query($conexao, $sql_select_produtos);
$produtos = mysqli_fetch_all($resultado_produtos, MYSQLI_ASSOC);
$categorias = getCategorias($conexao);

require_once 'index_view.php';

mysqli_close($conexao);