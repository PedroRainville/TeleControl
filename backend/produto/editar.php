<?php
include '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $codigo = $_POST["codigo"];
    $descricao = $_POST["descricao"];
    $status = $_POST["status"];
    $tempo_garantia = $_POST["tempo_garantia"];

    $sql = "UPDATE produtos SET codigo=?, descricao=?, status=?, tempo_garantia=? WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssii", $codigo, $descricao, $status, $tempo_garantia, $id);

    if ($stmt->execute()) {
        header("Location: principal.php");
        exit(); 
    } else {
        echo "Erro ao atualizar produto: " . $conexao->error;
    }
    $stmt->close();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM produtos WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $codigo = $row["codigo"];
        $descricao = $row["descricao"];
        $status = $row["status"];
        $tempo_garantia = $row["tempo_garantia"];
    } else {
        echo "Produto não encontrado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Produto</title>
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
        <h2 class="mt-5 mb-4">Atualizar Produto</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código:</label>
                <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $codigo; ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo $descricao; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="">Selecione</option>
                    <option value="Ativo" <?php if ($status == "Ativo") echo "selected"; ?>>Ativo</option>
                    <option value="Inativo" <?php if ($status == "Inativo") echo "selected"; ?>>Inativo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tempo_garantia" class="form-label">Tempo de Garantia (em meses):</label>
                <input type="number" class="form-control" id="tempo_garantia" name="tempo_garantia" value="<?php echo $tempo_garantia; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="javascript:history.back()" class="btn btn-danger">Voltar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
