<?php
include "conecta.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $descricao = $_POST['descricao'] ?? '';
    $fabricante = $_POST['fabricante'] ?? '';
    $qtd = $_POST['qtd'] ?? 0;
    $preco_custo = $_POST['preco_custo'] ?? 0.00;
    $preco_venda = $_POST['preco_venda'] ?? 0.00;

    // Processa o upload da imagem
    $imagem = '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $tmp_name = $_FILES['imagem']['tmp_name'];
        $name = basename($_FILES['imagem']['name']);
        $upload_file = $upload_dir . $name;

        // Verifica se o diretório de upload existe, caso contrário, cria
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move o arquivo para o diretório de upload
        if (move_uploaded_file($tmp_name, $upload_file)) {
            $imagem = $upload_file;
        } else {
            echo "<p>Erro ao enviar a imagem.</p>";
        }
    } else {
        echo "<p>Erro no upload da imagem ou arquivo não enviado.</p>";
    }

    if ($descricao && $fabricante && $qtd >= 0 && $preco_custo >= 0 && $preco_venda >= 0) {
        if ($conexao) {
            $query = "INSERT INTO produtos (descricao, fabricante, qtd, preco_custo, preco_venda, imagem) 
                      VALUES (?, ?, ?, ?, ?, ?)";

            if ($dec = $conexao->prepare($query)) {
                $dec->bind_param('ssidds', $descricao, $fabricante, $qtd, $preco_custo, $preco_venda, $imagem);

                if ($dec->execute()) {
                    echo "<p>Produto cadastrado com sucesso!</p>";
                } else {
                    echo "<p>Erro ao cadastrar o produto: " . $dec->error . "</p>";
                }

                $dec->close();
            } else {
                echo "<p>Erro ao preparar a query: " . $conexao->error . "</p>";
            }

            $conexao->close();
        } else {
            echo "<p>Erro na conexão com o banco de dados.</p>";
        }
    } else {
        echo "<p>Por favor, preencha todos os campos corretamente.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Mundo Kitty</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cadastrar.php">Cadastrar</a></li>
                    <li><a href="excluir.php">Excluir</a></li>
                    <li><a href="atualizar.php">Atualizar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Formulário de Cadastro -->
    <div class="container">
        <h2>Insira os dados do produto</h2>
        <form class="form-cadastro" method="post" action="cadastrar.php" enctype="multipart/form-data">
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" id="descricao" required>

            <label for="fabricante">Fabricante:</label>
            <input type="text" name="fabricante" id="fabricante" required>

            <label for="qtd">Quantidade em Estoque:</label>
            <input type="number" name="qtd" id="qtd" required>

            <label for="preco_custo">Preço de Custo:</label>
            <input type="number" step="0.01" name="preco_custo" id="preco_custo" required>

            <label for="preco_venda">Preço de Venda:</label>
            <input type="number" step="0.01" name="preco_venda" id="preco_venda" required>

            <label for="imagem">Imagem:</label>
            <input type="file" name="imagem" id="imagem" accept="image/*" required>

            <button type="submit">Cadastrar Produto</button>
        </form>
    </div>
</body>

</html>