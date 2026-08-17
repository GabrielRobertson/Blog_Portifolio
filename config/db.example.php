<?php
// ================================================
// CONFIGURAÇÃO DE BANCO DE DADOS (EXEMPLO DE TEMPLATE)
// ================================================
// Renomeie este arquivo para "db.php" e insira suas credenciais
// ================================================

$host    = 'localhost';
$db      = 'NOME_DO_SEU_BANCO';
$user    = 'SEU_USUARIO_MYSQL';
$pass    = 'SUA_SENHA_MYSQL';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log($e->getMessage());
    die("Erro de conexão com o banco de dados. Entre em contato com o administrador.");
}
?>
