<?php
include "conecta.php";

// Variável para armazenar mensagem
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_id'])) {
        $id = $_POST['update_id'];
        $descricao = $_POST['descricao'] ?? '';
        $fabricante = $_POST['fabricante'] ?? '';
        $preco_venda = $_POST['preco_venda'] ?? '';
        
        if ($id) {
            // Atualiza o produto
            $stmt = $conexao->prepare("UPDATE produtos SET descricao = ?, fabricante = ?, preco_venda = ? WHERE id = ?");
            $stmt->bind_param('ssdi', $descricao, $fabricante, $preco_venda, $id);
            if ($stmt->execute()) {
                $message = "Produto atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar o produto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "ID do produto não fornecido!";
        }
    }
}

// Buscar o produto para atualização
$product = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Buscar todos os produtos
$query = "SELECT * FROM produtos";
$result = $conexao->query($query);

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Produto</title>
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

    <div class="container">
        <h2>Buscar Produto para Atualizar</h2>

        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Formulário de Busca de ID -->
        <form method="get" action="">
            <div class="form-exc">
                <label for="id">ID do Produto:</label>
                <input type="number" id="id" name="id" required>
                <button type="submit">Buscar</button>
            </div>
        </form>

        <!-- Lista de Produtos -->
        <h2>Lista de Produtos</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="products-exc">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Fabricante</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                                <td><?php echo htmlspecialchars($row['fabricante']); ?></td>
                                <td>R$ <?php echo number_format($row['preco_venda'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum produto encontrado</p>
        <?php endif; ?>
    </div>

        <!-- Formulário de Atualização -->
        <?php if ($product): ?>
            <h2>Atualizar Produto</h2>
            <form method="post" action="">
                <div class="form-cadastro">
                    <input type="hidden" name="update_id" value="<?= htmlspecialchars($product['id']) ?>">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($product['descricao']) ?>" required>
                    <label for="fabricante">Fabricante:</label>
                    <input type="text" id="fabricante" name="fabricante" value="<?= htmlspecialchars($product['fabricante']) ?>" required>
                    <label for="preco_venda">Preço de Venda:</label>
                    <input type="number" id="preco_venda" name="preco_venda" step="0.01" value="<?= htmlspecialchars($product['preco_venda']) ?>" required>
                    <button type="submit">Atualizar</button>
                </div>
            </form>
        <?php elseif (isset($_GET['id'])): ?>
            <p>Produto não encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
