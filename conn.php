<?php //Conexão com banco de dados do app mobile ionic 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type,Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

// Dados do servidor local
$banco = 'controledb';
$host = 'localhost';
$usuario = 'root';
$senha = '';

/*
// Dados do servidor remoto/hospedado
$banco = 'wellington_91';
$host = 'softkleen.com.br';
$usuario = 'apiti9111';
$senha = '';
*/

try {
   $pdo = new PDO("mysql:dbname=$banco;host=$host","$usuario","$senha");
} catch (Exception $e ) {
    echo'Erro ao Conectar com banco!';
}
?>