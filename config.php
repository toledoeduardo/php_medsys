<?php
// Arquivo de configuração do banco de dados
$host = 'localhost';
$db = 'MedSys';
$user = 'root'; // Substitua pelo seu usuário
$password = 'Meg@deth1999'; // Substitua pela sua senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
