<?php 
    
    require('config.php');

    //Variavel que recebe o conteudo da requisição do app decodificando-a (Json)
    $_POSTjson = json_decode(file_get_contents('php://input', true),true);

    if ($_POSTjson['requisicao'] == 'adicionar') { // Requisição para Add
      try {
          // Preparando Query
          $query = $pdo->prepare('insert into eventos set nome = :nome, data_evento = :data_evento, capacidade = :capacidade, ativo = :ativo, usuarios_id = :usuarios_id');
  
          // Passando o valor dos parametros da query
          $query->bindValue(":nome", $_POSTjson['nome']);
          $query->bindValue(":data_evento", $_POSTjson['data_evento']);
          $query->bindValue(":capacidade", $_POSTjson['capacidade']);
          $query->bindValue(":ativo", $_POSTjson['ativo']);
          $query->bindValue(":usuarios_id", $_POSTjson['usuarios_id']);
  
          // Executando Query
          $query->execute();
  
          // Pegando ultimo id criado
          $id = $pdo->lastInsertId();
  
          // Retornando
          echo json_encode(($query) ? array('success' => true, 'id' => $id) : array('success' => false, 'msg' => 'Falha ao inserir o evento'));
      } catch (\Throwable $e) {
          // Entregando JSON ERRO
          echo json_encode(array('success' => false, 'msg' => $e));
      }
  }else if($_POSTjson['requisicao'] == 'list'){
      if($_POSTjson['nome'] == ''){
          $query = $pdo->query("SELECT * FROM eventos ORDER BY id DESC LIMIT $_POSTjson[start], $_POSTjson[limit]");
      }else{
            $busca = $_POSTjson['nome'].'%';
            $query = $pdo->query("SELECT * FROM eventos WHERE nome LIKE '$busca' or usuarios_id LIKE '$busca' order BY id desc limit $_POSTjson[start], $_POSTjson[limit]");
      }
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      for($i=0;$i < count($res);$i++){
          $dados[] = array(
            'id'=>$res[$i]['id'],
            'nome'=>$res[$i]['nome'],
            'data_evento'=>$res[$i]['data_evento'],
            'capacidade'=>$res[$i]['capacidade'],
            'usuarios_id'=>$res[$i]['usuarios_id'],       
            'ativo'=>$res[$i]['ativo']
          );
      }
      if(count($res)>0){
        $result = json_encode(array('success'=>true,'result'=>$dados));
      }else{
        $result = json_encode(array('success'=>false,'result'=>'Eita Cláudia...'));
      }
      echo $result;
    
    
    }//Fim do Listar

    
    else if($_POSTjson['requisicao']=='edit'){
      $query = $pdo->prepare("UPDATE eventos SET nome=:nome, data_evento=:data_evento, capacidade=:capacidade WHERE id =:id");
      $query->bindValue(":nome",$_POSTjson['nome']);
      $query->bindValue(":data_evento",$_POSTjson['data_evento']);
      $query->bindValue(":capacidade",$_POSTjson['capacidade']);
      $query->bindValue(":id",$_POSTjson['id']);   
      $query->execute();
      if ($query){
          $result = json_encode(array('success'=>true, 'msg'=>"Deu tudo certo com alteração!"));
      }else{
          $result = json_encode(array('success'=>false,'msg'=>"Dados incorretos! Falha ao atualizar o evento!"));
      }
      
    

      echo $result;
    } //final da requisição Editar
    else if($_POSTjson['requisicao']=='excloi'){
      //$query = $pdo->query("Delete from eventos where id = $_POSTjson[id]");
      $query = $pdo->query("update eventos set ativo = 0 where id = $_POSTjson[id]");
      if ($query){
          $result = json_encode(array('success'=>true, 'msg'=>"Evento excluído com sucesso!"));
      }else{
          $result = json_encode(array('success'=>false,'msg'=>"Falha ao excluir o evento!"));
      }
      echo $result;
    }//final do excluir
    else if($_POSTjson['requisicao']=='ativ'){
      $query = $pdo->query("UPDATE eventos set ativo = 1 where id = $_POSTjson[id]");
      if ($query){
          $result = json_encode(array('success'=>true, 'msg'=>"Evento Ativado com sucesso!"));
      }else{
          $result = json_encode(array('success'=>false,'msg'=>"Falha ao ativar o evento!"));
      }
      echo $result;
    }//final do ativar
    
?>
