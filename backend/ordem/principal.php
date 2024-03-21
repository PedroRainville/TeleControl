<?php
include '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["numero_ordem"])) {
    $numero_ordem = $_POST["numero_ordem"];

    $sql_delete = "DELETE FROM ordem_servico WHERE numero_ordem = ?";
    $stmt_delete = $conexao->prepare($sql_delete);
    $stmt_delete->bind_param("s", $numero_ordem);

    if ($stmt_delete->execute()) {
        echo "";
    } else {
        echo "Erro ao excluir ordem de serviço: " . $stmt_delete->error;
    }

    $stmt_delete->close();
}

$query = "SELECT os.numero_ordem, os.data_abertura, c.nome AS nome_consumidor, os.cpf_consumidor, p.descricao AS produto
          FROM ordem_servico AS os
          INNER JOIN clientes AS c ON os.cliente_id = c.id
          INNER JOIN produtos AS p ON os.produto_id = p.id";
$result = $conexao->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<style>
        body {            
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1c213f, #036fec);
            color: #fff;
        }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Ordens de Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5 mb-4" style="color:#fff">Listagem de Ordens de Serviço</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número da Ordem</th>
                    <th>Data de Abertura</th>
                    <th>CPF do Consumidor</th>
                    <th>Produto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["numero_ordem"] . "</td>";
                        echo "<td>" . $row["data_abertura"] . "</td>";
                        echo "<td>" . $row["cpf_consumidor"] . "</td>";
                        echo "<td>" . $row["produto"] . "</td>";
                        echo "<td>
                                <a href='editar.php?numero_ordem=" . $row["numero_ordem"] . "' class='btn btn-sm btn-primary'><i class='bi bi-pencil'></i></a>
                                <form method='POST' class='d-inline' onsubmit='return confirm(\"Tem certeza que deseja excluir esta ordem de serviço?\")'>
                                    <input type='hidden' name='numero_ordem' value='" . $row["numero_ordem"] . "'>
                                    <button type='submit' class='btn btn-sm btn-danger'><i class='bi bi-trash'></i></button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhuma ordem de serviço encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-center">
            <a href="adicionar.php" class="btn btn-primary">Cadastrar Ordem</a>
        </div>
        <div class="col-sm-12 d-flex justify-content-center" style="padding-top: 20px;">
            <a href="../../frontend/index.html" class="btn btn-danger">Retornar Menu</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
