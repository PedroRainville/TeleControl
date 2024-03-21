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

    $sql_update = "UPDATE ordem_servico SET data_abertura = ?, nome_consumidor = ?, cpf_consumidor = ?, produto_id = ? WHERE numero_ordem = ?";
    $stmt_update = $conexao->prepare($sql_update);
    $stmt_update->bind_param("ssisi", $data_abertura, $nome_consumidor, $cpf_consumidor, $produto_id, $numero_ordem);
    

    if ($stmt_update->execute()) {
        header("Location: principal.php");
        exit();
    } else {
        echo "Erro ao atualizar ordem de serviço: " . $stmt_update->error;
    }

    $stmt_update->close();
}

if (isset($_GET["numero_ordem"])) {
    $numero_ordem = $_GET["numero_ordem"];

    $sql_select = "SELECT os.numero_ordem, os.data_abertura, c.nome AS nome_consumidor, os.cpf_consumidor, os.produto_id, p.descricao AS produto
                    FROM ordem_servico AS os
                    INNER JOIN clientes AS c ON os.cliente_id = c.id
                    INNER JOIN produtos AS p ON os.produto_id = p.id
                    WHERE os.numero_ordem = ?";
    $stmt_select = $conexao->prepare($sql_select);
    $stmt_select->bind_param("i", $numero_ordem);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $data_abertura = $row["data_abertura"];
        $nome_consumidor = $row["nome_consumidor"];
        $cpf_consumidor = $row["cpf_consumidor"];
        $produto_id = $row["produto_id"];
        $produto = $row["produto"];
    } else {
        echo "Ordem de serviço não encontrada.";
        exit();
    }

    $stmt_select->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Ordem de Serviço</title>
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
        <h2 class="mt-5 mb-4">Atualizar Ordem de Serviço</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="numero_ordem" class="form-label">Número da Ordem</label>
                <input type="text" class="form-control" id="numero_ordem" name="numero_ordem" value="<?php echo $numero_ordem; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="data_abertura" class="form-label">Data de Abertura</label>
                <input type="date" class="form-control" id="data_abertura" name="data_abertura" value="<?php echo $data_abertura; ?>">
            </div>
            <div class="mb-3" hidden>
                <label for="nome_consumidor" class="form-label">Nome do Consumidor</label>
                <input type="text" class="form-control" id="nome_consumidor" name="nome_consumidor" value="<?php echo $nome_consumidor; ?>">
            </div>
            <div class="mb-3">
                <label for="cpf_consumidor" class="form-label">CPF do Consumidor</label>
                <input type="text" class="form-control" id="cpf_consumidor" name="cpf_consumidor" value="<?php echo $cpf_consumidor; ?>">
            </div>
            <div class="mb-3">
                <label for="produto_id" class="form-label">Produto</label>
                <select class="form-select" id="produto_id" name="produto_id">
                    <?php
                    $sql_produtos = "SELECT id, descricao FROM produtos";
                    $result_produtos = $conexao->query($sql_produtos);

                    if ($result_produtos->num_rows > 0) {
                        while ($row_produto = $result_produtos->fetch_assoc()) {
                            $selected = ($row_produto['id'] == $produto_id) ? "selected" : "";
                            echo "<option value='" . $row_produto['id'] . "' $selected>" . $row_produto['descricao'] . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>Nenhum produto cadastrado</option>";
                    }
                    ?>
                </select>
            </div>
            </br>
            <button type="submit" class="btn btn-primary">Atualizar Ordem</button>
            <a href="javascript:history.back()" class="btn btn-danger">Voltar</a>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
