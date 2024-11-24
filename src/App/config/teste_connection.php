<?php
// Dados de conexão
$host = '127.0.0.1';       // Host do banco (127.0.0.1 para MySQL local)
$user = 'root';            // Usuário (root, por padrão)
$password = '123456';      // Substitua pela sua senha configurada
$dbname = 'gestao_eventos'; // Nome do banco de dados

// Criando a conexão
$connect = new mysqli($host, $user, $password, $dbname, 3306);

// Testando a conexão
if ($connect->connect_error) {
    die("Falha na conexão: " . $connect->connect_error);
}
echo "Conexão bem-sucedida! #################";
?>
