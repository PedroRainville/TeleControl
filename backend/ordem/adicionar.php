<?php
include '../db_config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_ordem = $_POST["numero_ordem"];
    $data_abertura = $_POST["data_abertura"];
    $nome_consumidor = $_POST["nome_consumidor"];
    $cpf_consumidor = $_POST["cpf_consumidor"];
    $produto_id = $_POST["produto_id"];

    $query = "SELECT id FROM clientes WHERE cpf = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $cpf_consumidor);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $query = "INSERT INTO clientes (nome, cpf) VALUES (?, ?)";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("ss", $nome_consumidor, $cpf_consumidor);
        $stmt->execute();

        $cliente_id = $stmt->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $cliente_id = $row['id'];
    }

    $query = "INSERT INTO ordem_servico (numero_ordem, data_abertura, cliente_id, cpf_consumidor, produto_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("ssiii", $numero_ordem, $data_abertura, $cliente_id, $cpf_consumidor, $produto_id);

    if ($stmt->execute()) {
        header("Location: principal.php");
        exit();
    } else {
        echo "Erro ao cadastrar ordem de serviço: " . $conexao->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Ordem de Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
      body {            
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1c213f, #036fec);
            color: #fff;
        }
</style>
<body>
    <div class="container">
        <h2 class="mt-5 mb-4">Cadastro de Ordem de Serviço</h2>
        <form action="adicionar.php" method="POST">
            <div class="mb-3">
                <label for="numero_ordem" class="form-label">Número da Ordem:</label>
                <input type="text" class="form-control" id="numero_ordem" name="numero_ordem" required>
            </div>
            <div class="mb-3">
                <label for="data_abertura" class="form-label">Data de Abertura:</label>
                <input type="date" class="form-control" id="data_abertura" name="data_abertura" required>
            </div>
            <div class="mb-3">
                <label for="nome_consumidor" class="form-label">Nome do Consumidor:</label>
                <input type="text" class="form-control" id="nome_consumidor" name="nome_consumidor" required>
            </div>
            <div class="mb-3">
                <label for="cpf_consumidor" class="form-label">CPF do Consumidor:</label>
                <input type="text" class="form-control" id="cpf_consumidor" name="cpf_consumidor" required>
            </div>
            <div class="mb-3">
                <label for="produto_id" class="form-label">Produto:</label>
                <select class="form-select" id="produto_id" name="produto_id" required>
                    <option value="">Selecione</option>
                    <?php
                    $query = "SELECT id, descricao FROM produtos";
                    $result = $conexao->query($query);

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['descricao'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="principal.php" class="btn btn-danger">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
