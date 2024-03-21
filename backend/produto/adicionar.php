<?php
include '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST["codigo"];
    $descricao = $_POST["descricao"];
    $status = $_POST["status"];
    $tempo_garantia = $_POST["tempo_garantia"];
    
    $sql = "INSERT INTO produtos (codigo, descricao, status, tempo_garantia) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssi", $codigo, $descricao, $status, $tempo_garantia);
    
    if ($stmt->execute()) {
        header("Location: principal.php");
        exit();
    } else {
        echo "Erro ao cadastrar produto: " . $conexao->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1c213f, #036fec);
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="mt-5 mb-4">Cadastro de Produto</h2>
        <form action="adicionar.php" method="POST">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código:</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <input type="text" class="form-control" id="descricao" name="descricao" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="">Selecione</option>
                    <option value="Ativo">Ativo</option>
                    <option value="Inativo">Inativo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tempo_garantia" class="form-label">Tempo de Garantia (em meses):</label>
                <input type="number" class="form-control" id="tempo_garantia" name="tempo_garantia" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="javascript:history.back()" class="btn btn-danger">Voltar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
