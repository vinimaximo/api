<?php 
class Evento{
    //Atributos
    private $Id;
    private $Nome;
    private $data_evento;
    private $capacidade;
    private $usuarios_id;
    private $Ativo;
    // Declaração de metodos de acesso(Getters an Setters)
    public function getId(){return $this->Id;}
    public function getNome(){return $this->Nome;}
    public function getdata_evento(){return $this->data_evento;}
    public function getcapacidade(){return $this->capacidade;}
    public function getusuarios_id(){return $this->usuarios_id;}
    public function getAtivo(){return $this->Ativo;}

    public function setId($value){$this->Id = $value;}
    public function setNome($value){$this->Nome = $value;}
    public function setdata_evento($value){$this->data_evento = $value;}
    public function setcapacidade($value){$this->capacidade = $value;}
    public function setusuarios_id($value){$this->usuarios_id = $value;}
    public function setAtivo($value){$this->Ativo = $value;}

    public function loadById($_Id){
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM eventos WHERE id  = :id",array(':id'=>$_Id));
        if(count($results)>0){
            $this->setdata_evento($results[0]);
        }
    }
    public function setData($dados){
        $this->setId($dados['id']);
        $this->setNome($dados['nome']);
        $this->setdata_evento($dados['data_evento']);
        $this->setcapacidade($dados['capacidade']);
        $this->setusuarios_id($dados['usuarios_id']);
        $this->setAtivo($dados['ativo']);
    }
    public static function getList(){
        $sql = new Sql();
        return $sql->select("SELECT * FROM eventos ORDER BY nome");
    }
    public static function search($_nome){
        $sql = new Sql();
        return $sql->select("SELECT * FROM eventos WHERE nome LIKE :nome",
        array(":nome"=>"%".$_nome."%"));
    }
   
    public function insert(){
        $sql = new Sql ();
        $res = $sql->select("CALL sp_eve_insert(:nome, :data_evento, :capacidade, :usuarios_id",
        array(
            ":nome"=>$this->getNome(),
            ":data_evento"=>md5($this->getdata_evento()),
            ":capacidade"=>$this->getcapacidade(),
            ":usuarios_id"=>$this->getusuarios_id(),
           
           
            
        ));
        if(count($res)> 0){
            $this->setdata_evento($res[0]);
        }
    }
    public function update($_id, $_nome, $_data_evento, $_capacidade, $_usuarios_id){
        $sql = new Sql();
        $sql->query("UPDATE eventos SET nome = :nome, data_evento = :data_evento, capacidade = :capacidade, usuarios_id = :usuarios_id WHERE id = :id",
        array(
            ":nome"=>$_nome,
            ":senha"=>$_data_evento,
            ":nivel"=>$_capacidade,
            ":avatar"=>$_usuarios_id,
            ":id"=>$_id,
           
            
        ));
    }
    public function delete(){
        $sql = new Sql();
        $sql->query("DELETE FROM eventos WHERE id = :id",array(":id"=>$this->getId()));
    }
    public function __contruct($_nome="", $_data_evento="", $_capacidade="", $_usuarios_id="", $_ativo=""){
      $this->nome = $_nome;
      $this->senha = $_data_evento;
      $this->nivel = $_capacidade;
      $this->usuario = $_usuarios_id;
      $this->ativo = $_ativo;
      
      
    }
    
}
?>