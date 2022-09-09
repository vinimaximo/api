<?php 
 //Api - Aplicação para recusros de app mobile ionic
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
else if($postjson['requisicao']=='editar'){
  $query = $pdo->prepare("UPDATE usuarios SET nome=:nome, usuario=:usuario, senha= :senha, senha_original = :senha_original, nivel=:nivel WHERE id = :id");
  $query->bindValue(":nome",$postjson['nome']);
  $query->bindValue(":usuario",$postjson['usuario']);
  $query->bindValue(":senha",md5($postjson['senha']));
  $query->bindValue(":senha_original",$postjson['senha']);
  $query->bindValue(":nivel",$postjson['nivel']);
  $query->bindValue(":id",$postjson['id']);
  $query->execute();
  if ($query){
      $result = json_encode(array('success'=>true, 'msg'=>"Deu tudo certo com alteração!"));
  }else{
      $result = json_encode(array('success'=>false,'msg'=>"Dados incorretos! Falha ao atualizar o usuário! (WRH014587)"));
  }
  echo $result;
} //final da requisição Editar
else if($postjson['requisicao']=='excluir'){
  //$query = $pdo->query("Delete from usuarios where id = $postjson[id]");
  $query = $pdo->query("update usuarios set ativo = 0 where id = $postjson[id]");
  if ($query){
      $result = json_encode(array('success'=>true, 'msg'=>"Usuário excluído com sucesso!"));
  }else{
      $result = json_encode(array('success'=>false,'msg'=>"Falha ao excluir o usuário!"));
  }
  echo $result;
}//final do excluir
else if($postjson['requisicao']=='login'){
  $query = $pdo->query("SELECT * from usuarios where usuario = '$postjson[usuario]' and senha = md5('$postjson[senha]') and ativo = 1");

  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  for($i=0;$i < count($res); $i++){
      $dados = array(
          'id'=>$res[$i]['id'],
          'nome'=>$res[$i]['nome'],
          'usuario'=>$res[$i]['usuario'],
          'nivel'=>$res[$i]['nivel'],
          'ativo'=>$res[$i]['ativo']
      );
  }
  if ($query){
      $result = json_encode(array('success'=>true, 'result'=>$dados));
  }else{
      $result = json_encode(array('success'=>false,'msg'=>"Falha ao efetuar o login!"));
  }
  echo $result;
}//final do login
else if($postjson['requisicao']=='ativar'){
  $query = $pdo->query("UPDATE usuarios set ativo = 1 where id = $postjson[id]");
  if ($query){
      $result = json_encode(array('success'=>true, 'msg'=>"Usuário Ativado com sucesso!"));
  }else{
      $result = json_encode(array('success'=>false,'msg'=>"Falha ao ativar o usuário!"));
  }
  echo $result;
}//final do ativar
?>
