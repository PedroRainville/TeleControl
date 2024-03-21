<?php
include '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $endereco = $_POST["endereco"];
    
    $sql = "INSERT INTO clientes (nome, cpf, endereco) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sss", $nome, $cpf, $endereco);
    
    if ($stmt->execute()) {
        header("Location: adicionar.php");
        exit();
    } else {
        echo "Erro ao adicionar cliente: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1c213f, #036fec);
            color: #fff;
            }
</style>
<body>
    <div class="container mt-5">
        <h2>Adicionar Cliente</h2>
        <form action="adicionar.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome">
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" class="form-control" id="cpf" name="cpf">
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco">
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Cliente</button>
            <a href="javascript:history.go(-1)" class="btn btn-danger">Voltar</a>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
