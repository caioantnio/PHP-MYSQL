<?php
include "conecta.php";

$mysqli = new mysqli("localhost", "root", "", "loja");

$descricao = $_GET['descricao'] ?? '';
$fabricante = $_GET['fabricante'] ?? '';
$mostrarZerados = isset($_GET['zerados']) ? true : false;
$preco_min = $_GET['preco_min'] ?? '';
$preco_max = $_GET['preco_max'] ?? '';

$query = "SELECT * FROM produtos WHERE 1=1";

// Filtro por descrição
if ($descricao) {
    $query .= " AND descricao LIKE '%$descricao%'";
}

// Filtro por fabricante
if ($fabricante) {
    $query .= " AND fabricante = '$fabricante'";
}

// Filtro por produtos sem estoque
if ($mostrarZerados) {
    $query .= " AND qtd = 0";
}

// Filtro por faixa de preço
if ($preco_min != '' && $preco_max != '') {
    $query .= " AND preco_venda BETWEEN $preco_min AND $preco_max";
} elseif ($preco_min != '') {
    $query .= " AND preco_venda >= $preco_min";
} elseif ($preco_max != '') {
    $query .= " AND preco_venda <= $preco_max";
}

$result = $mysqli->query($query);
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
            <h1>Bem-vindo ao Mundo Kitty!</h1>
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

    <section class="banner">
        <div class="container">
            <img src="Assets/hello-kitty-2.jpg" alt="Banner de Promoção" class="banner-image">
            <h2>Encontre seus personagens favoritos!</h2>
        </div>
    </section>

    <div class="container">
        <h2>Buscar Produtos</h2>
        <form method="get" action="">
            <input type="text" name="descricao" placeholder="Descrição" value="<?= htmlspecialchars($descricao) ?>">
            <input type="text" name="fabricante" placeholder="Fabricante" value="<?= htmlspecialchars($fabricante) ?>">
            <input type="number" step="0.01" name="preco_min" placeholder="Preço Mínimo"
                value="<?= htmlspecialchars($preco_min) ?>">
            <input type="number" step="0.01" name="preco_max" placeholder="Preço Máximo"
                value="<?= htmlspecialchars($preco_max) ?>">
            <label>
                <input type="checkbox" name="zerados" <?= $mostrarZerados ? 'checked' : '' ?>> Sem estoque
            </label>
            <button type="submit">Buscar</button>
        </form>

        <!-- Lista de Produtos -->
        <div class="products">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product">
                        <img src="<?= $row['imagem'] ?>" alt="Imagem do produto">
                        <h3><?= $row['descricao'] ?></h3>
                        <p>Fabricante: <?= $row['fabricante'] ?></p>
                        <p class="price">R$ <?= number_format($row['preco_venda'], 2, ',', '.') ?></p>
                        <button>Comprar</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum produto encontrado</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>

<?php
$mysqli->close();
?>