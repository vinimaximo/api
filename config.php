<?php 
// Inicializa a sessão de ususario
if (isset($_SESSION)) {
    session_start();
}
//Definindo padrão de zona GMT (TimeZone) -3,00
date_default_timezone_set('America/Sao_paulo');

// Inicia o carregamento de classes do projeto
spl_autoload_register(function($nome_classe){
    $nome_arquivo = "classes".DIRECTORY_SEPARATOR.$nome_classe.".php";
    if(file_exists($nome_arquivo)){
        require_once($nome_arquivo);
    }
});
?>