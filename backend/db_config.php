<?php
$dbhost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'telecontrol';

$conexao = new mysqli($dbhost, $dbUsername, $dbPassword, $dbName);

if ($conexao->connect_errno) {
    echo "Erro ao conectar ao banco de dados: " . $conexao->connect_error;
} else {
    echo "";
}
?>

