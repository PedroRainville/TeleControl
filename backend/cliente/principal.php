<?php
include '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_excluir"])) {
    $id_excluir = $_POST["id_excluir"];

    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_excluir);

    if ($stmt->execute()) {
        echo "Cliente excluído com sucesso.";
    } else {
        echo "Erro ao excluir cliente: " . $stmt->error;
    }

    $stmt->close();
}

$sql = "SELECT * FROM clientes";
$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
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
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <h2 class="mt-5 mb-4">Listagem de Clientes</h2>
                    </div>
                </div>
            </div>

            <div class="table-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nome do cliente</th>
                                    <th>CPF</th>
                                    <th>Endereço</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($resultado->num_rows > 0) {
                                    while ($row = $resultado->fetch_assoc()) {
                                        echo "<tr data-id='" . $row['id'] . "'>";
                                        echo "<td>" . $row['nome'] . "</td>";
                                        echo "<td>" . $row['cpf'] . "</td>";
                                        echo "<td>" . $row['endereco'] . "</td>";
                                        echo "<td>
                                                <a href='editar.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'><i class='bi bi-pencil'></i></a>
                                                <a href='#' class='btn btn-sm btn-danger' onclick='deleteClient(" . $row['id'] . ")'><i class='bi bi-trash'></i></a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>Nenhum cliente cadastrado.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 d-flex justify-content-center">
                    <a href="adicionar.php" class="btn btn-primary">Cadastrar Cliente</a>
                </div>
                <div class="col-sm-12 d-flex justify-content-center" style="padding-top: 20px;">
                    <a href="../../frontend/index.html" class="btn btn-danger">Retornar Menu</a>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        function deleteClient(id) {
            if (confirm("Tem certeza que deseja excluir este cliente?")) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var row = document.querySelector("tr[data-id='" + id + "']");
                        row.parentNode.removeChild(row);
                    }
                };
                xhttp.open("POST", "", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("id_excluir=" + id);
            }
        }
    </script>

</body>

</html>
