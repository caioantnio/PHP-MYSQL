<?php
include "conecta.php";

// Verificar conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Verificar se foi solicitado a exclusão de um produto
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $sql_delete = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conexao->prepare($sql_delete);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<p>Produto excluído com sucesso.</p>";
    } else {
        echo "<p>Erro ao excluir produto: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Buscar todos os produtos
$sql = "SELECT * FROM produtos";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produtos</title>
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

    <div class="container-exc">
        <h2>Excluir Produto por ID</h2>
        <form class="form-exc" method="post" action="">
            <label for="delete_id">ID do Produto:</label>
            <input type="number" id="delete_id" name="delete_id" required>
            <button type="submit">Excluir</button>
        </form>

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

</body>

</html>

<?php
// Fechar a conexão
$conexao->close();
?>