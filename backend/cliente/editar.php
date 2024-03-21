<?php
include '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_atualizar"], $_POST["nome_atualizar"], $_POST["cpf_atualizar"], $_POST["endereco_atualizar"])) {
        $id = $_POST["id_atualizar"];
        $nome = $_POST["nome_atualizar"];
        $cpf = $_POST["cpf_atualizar"];
        $endereco = $_POST["endereco_atualizar"];

        $sql = "UPDATE clientes SET nome=?, cpf=?, endereco=? WHERE id=?";

        $stmt = $conexao->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssi", $nome, $cpf, $endereco, $id);
            if ($stmt->execute()) {
                header("Location: principal.php");
                exit();
            } else {
                echo "Erro ao atualizar cliente: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Erro ao preparar a declaração SQL: " . $conexao->error;
        }
    } else {
        echo "Todos os campos do formulário são obrigatórios.";
    }
} else {
    if (isset($_GET["id"])) {
        $id_cliente = $_GET["id"];
        
        $sql_select = "SELECT * FROM clientes WHERE id = ?";
        $stmt_select = $conexao->prepare($sql_select);
        if ($stmt_select) {
            $stmt_select->bind_param("i", $id_cliente);
            $stmt_select->execute();
            $result = $stmt_select->get_result();
            if ($result->num_rows > 0) {
                $cliente = $result->fetch_assoc();
                ?>
                <!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Editar Cliente</title>
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
                    <h2 class="mt-5 mb-4">Editar Cliente</h2>
                        <form method="post" action="editar.php">
                            <input type="hidden" name="id_atualizar" value="<?php echo $cliente['id']; ?>">
                            <div class="mb-3">
                                <label for="nome_atualizar" class="form-label">Novo Nome:</label>
                                <input type="text" class="form-control" id="nome_atualizar" name="nome_atualizar" value="<?php echo $cliente['nome']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="cpf_atualizar" class="form-label">Novo CPF:</label>
                                <input type="text" class="form-control" id="cpf_atualizar" name="cpf_atualizar" value="<?php echo $cliente['cpf']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="endereco_atualizar" class="form-label">Novo Endereço:</label>
                                <input type="text" class="form-control" id="endereco_atualizar" name="endereco_atualizar" value="<?php echo $cliente['endereco']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                            <a href="javascript:history.back()" class="btn btn-danger">Voltar</a>
                        </form>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                </body>
                </html>
                <?php
            } else {
                echo "Cliente não encontrado.";
            }
            $stmt_select->close();
        } else {
            echo "Erro ao preparar a declaração SQL: " . $conexao->error;
        }
    } else {
        echo "ID do cliente não identificado";
    }
}
?>
