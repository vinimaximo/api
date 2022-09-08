<?php //Api - Aplicação para recusros de app mobile ionic
include_once('conn.php');

//Variavel que recebe o conteudo da requisição do app decodificando-a (Json)
$_POSTjson = json_decode(file_get_contents('php://input', true),true);

if($_POSTjson['requisicao']=='add'){
      $query = $pdo->prepare("insert into usuarios set nome = :nome, usuario=:usuario,senha=:senha, senha_original=:senha_original, nivel=:nivel, ativo=1");
      $query ->bindValue(":nome", $_POSTjson['nome']);
      $query ->bindValue(":usuario", $_POSTjson['usuario']);
      $query ->bindValue(":senha", md5($_POSTjson['senha']));
      $query ->bindValue(":senha_original", $_POSTjson['senha']);
      $query ->bindValue(":nivel", $_POSTjson['nivel']);
      $query->execute();
      $id = $pdo->lastInsertId();

      if ($query) {
        $result = json_encode(array('success'=>true,'id'=>$id));

      }else{
        $result = json_encode(array('false'=>true,'msg'=>'Falha ao Inserir o Usuario!!'));

      }
      echo $result;  
      // Final requisição add  
}else if($_POSTjson['requisicao'] == 'listar'){
  if($_POSTjson['nome'] == ''){
      $query = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC LIMIT $_POSTjson[start], $_POSTjson[limit]");
  }else{
        $busca = $_POSTjson['nome'].'%';
        $query = $pdo->query("SELECT * FROM usuarios WHERE nome LIKE '$busca' or usuario LIKE '$busca' order BY id desc limit $_POSTjson[start], $_POSTjson[limit]");
  }
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  for($i=0;$i < count($res);$i++){
      $dados[] = array(
        'id'=>$res[$i]['id'],
        'nome'=>$res[$i]['nome'],
        'usuario'=>$res[$i]['usuario'],
        'senha'=>$res[$i]['senha'],
        'senha_original'=>$res[$i]['senha'],
        'nivel'=>$res[$i]['nivel'],
        'ativo'=>$res[$i]['ativo']
      );
  }
  if(count($res)>0){
    $result = json_encode(array('success'=>true,'result'=>$dados));
  }else{
    $result = json_encode(array('success'=>false,'result'=>'Eita Cláudia...'));
  }
  echo $result;


}
else if($_POSTjson['requisicao']=='editar'){
    $query=$pdo-> prepare("UPDATE usuarios SET nome=:nome, usuario=:usuario, senha=:senha, senha_original=:senha_original, nivel=:nivel WHERE id = :id");
    $query->bindValue(":nome",$_POSTjson['nome']);
    $query->bindValue(":usuario",$_POSTjson['usuario']);
    $query->bindValue(":senha",$_POSTjson['senha']);
    $query->bindValue(":senha_original",$_POSTjson['senha']);
    $query->bindValue(":nivel",$_POSTjson['nivel']);
    $query->bindValue(":id",$_POSTjson['id']);
    $query->execute();
      if($query){
        $result = json_encode(array('success'=>true,'msg'=>"Deu tudo certo com a alteração!"));

      }else{
        $result = json_encode(array('success'=>false,'msg'=>"Dados Incorretos! Falha ao atualizar o Usuário!"));
      }
      echo $result;

}//Final da requisição Editar
else if($_POSTjson['requisicao']=='excluir'){
  //$query = $pdo->query("Delete from usuarios where id $_POSTjson[id]");
  $query = $pdo->query("Update usuarios set ativo = 0 where id = $_POSTjson[id]");
  if($query){
    $result = json_encode(array('success'=>true,'msg'=>"Usuario Excluido com Sucesso!"));

  }else{
    $result = json_encode(array('success'=>false,'msg'=>"Falha ao Excluir o Usuario!"));
  }
  echo $result;

}//Final do Excluir
else if($_POSTjson['requisicao']=='login'){
  $query = $pdo->query("SELECT * FROM usuarios where usuario = '$_POSTjson[usuario]' and senha = md5('$_POSTjson[senha]') and ativo = 1");

  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  for($i=0;$i < count($res);$i++){
      $dados[] = array(
        'id'=>$res[$i]['id'],
        'nome'=>$res[$i]['nome'],
        'usuario'=>$res[$i]['usuario'],
        'senha'=>$res[$i]['senha'],
        'senha_original'=>$res[$i]['senha'],
        'nivel'=>$res[$i]['nivel'],
        'ativo'=>$res[$i]['ativo']
      );
  if($query){
    $result = json_encode(array('success'=>true,'result'=>$dados));

  }else{
    $result = json_encode(array('success'=>false,'msg'=>"Falha ao Efetuar o Login!"));
  }
  echo $result;

}//Final do Login
}
else if($_POSTjson['requisicao']=='ativar'){
  $query = $pdo->query("UPDATE from usuarios set ativo = 1 where id = $_POSTjson[id]");
  
  if($query){
    $result = json_encode(array('success'=>true,'msg'=>"Usuario Ativado com Sucesso!"));

  }else{
    $result = json_encode(array('success'=>false,'msg'=>"Falha ao Ativar o Usuario!"));
  }
  echo $result;

}//Final do ativar
