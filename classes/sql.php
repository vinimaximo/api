<?php 
class Sql extends PDO{
    private $cn;
    public function __construct(){
        $this-> cn = new PDO("mysql:host=127.0.0.1;dbname=controledb","root","");
    }
    // Métodos que atribuem parametros para um query sql
    public function setParams($comando, $parametros = array()){
            foreach($parametros as $key => $value){
                $this->setParam($comando,$key,$value);
            }
    }

    //Método para tratar o parâmetro
    public function setParam($cmd, $key, $value){
                $cmd->bindParam($key, $value); 
    }

    public function querySql($comandosql, $params = array()){
        $cmd = $this->cn->prepare($comandosql);
        $this->setParams($cmd, $params);
        $cmd->execute();
        return $cmd;
    }
    public function select($comandosql, $params = array()){
        $cmd = $this->query($comandosql, $params);
        return $cmd->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>