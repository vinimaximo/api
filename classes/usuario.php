<?php 
class Usuario{
    //Atributos
    private $Id;
    private $Nome;
    private $Usuario;
    private $Senha;
    private $Senha_original;
    private $Nivel;
    private $Avatar;
    private $Ativo;
    
    // Declaração de metodos de acesso(Getters an Setters)
    public function getId(){return $this->Id;}
    public function getNome(){return $this->Nome;}
    public function getUsuario(){return $this->Usuario;}
    public function getSenha(){return $this->Senha;}
    public function getSenha_original(){return $this->Senha_original;}
    public function getNivel(){return $this->Nivel;}
    public function getAvatar(){return $this->Avatar;}
    public function getAtivo(){return $this->Ativo;}

    public function setId($value){$this->Id = $value;}
    public function setNome($value){$this->Nome = $value;}
    public function setUsuario($value){$this->Usuario = $value;}
    public function setSenha($value){$this->Senha = $value;}
    public function setSenha_original($value){$this->Senha_original = $value;}
    public function setNivel($value){$this->Nivel = $value;}
    public function setAvatar($value){$this->Avatar = $value;}
    public function setAtivo($value){$this->Ativo = $value;}

    public function loadById($_Id){
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM usuarios WHERE id  = :id",array(':id'=>$_Id));
        if(count($results)>0){
            $this->setData($results[0]);
        }
    }
    public function setData($dados){
        $this->setId($dados['id']);
        $this->setNome($dados['nome']);
        $this->setUsuario($dados['usuario']);
        $this->setSenha($dados['senha']);
        $this->setSenha_original($dados['senha_original']);
        $this->setNivel($dados['nivel']);
        $this->setAvatar($dados['avatar']);
        $this->setAtivo($dados['ativo']);
    }
    public static function getList(){
        $sql = new Sql();
        return $sql->select("SELECT * FROM usuarios ORDER BY nome");
    }
    public static function search($_nome){
        $sql = new Sql();
        return $sql->select("SELECT * FROM usuarios WHERE nome LIKE :nome",
        array(":nome"=>"%".$_nome."%"));
    }
    public function efetuarLogin($_usuario,$_senha){
        $sql = new Sql();
        $senhaCrip = md5($_senha);
        $res = $sql->select("SELECT * FROM usuarios WHERE usuario = :usuario AND senha = :senha",
         array(":usuario"=>$_usuario,":senha"=>$senhaCrip));
         if(count($res)>0){
            $this->setData($res[0]);
         }
    }
    public function insert(){
        $sql = new Sql ();
        $res = $sql->select("CALL sp_user_insert(:nome, :usuario, :senha, :nivel, :avatar ",
        array(
            ":nome"=>$this->getNome(),
            ":usuario"=>md5($this->getUsuario()),
            ":senha"=>$this->getSenha(),
            ":nivel"=>$this->getNivel(),
            ":avatar"=>$this->getAvatar(),
           
            
        ));
        if(count($res)> 0){
            $this->setData($res[0]);
        }
    }
    public function update($_id, $_nome, $_senha, $_nivel, $_avatar){
        $sql = new Sql();
        $sql->query("UPDATE usuarios SET nome = :nome, senha = :senha, nivel = :nivel, avatar = :avatar WHERE id = :id",
        array(
            ":nome"=>$_nome,
            ":senha"=>md5($_senha),
            ":nivel"=>$_nivel,
            ":avatar"=>$_avatar,
            ":id"=>$_id,
           
            
        ));
    }
    public function delete(){
        $sql = new Sql();
        $sql->query("DELETE FROM usuarios WHERE id = :id",array(":id"=>$this->getId()));
    }
    public function __contruct($_nome="", $_usuario="", $_senha="", $_nivel="", $_ativo="", $_avatar=""){
      $this->nome = $_nome;
      $this->senha = $_senha;
      $this->nivel = $_nivel;
      $this->usuario = $_usuario;
      $this->ativo = $_ativo;
      $this->avatar = $_avatar;
      
    }
    
}
?>