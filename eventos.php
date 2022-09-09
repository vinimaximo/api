<?php 
    include_once('conn.php');

    //Variavel que recebe o conteudo da requisição do app decodificando-a (Json)
    $_POSTjson = json_decode(file_get_contents('php://input', true),true);

    if($_POSTjson['requisicao']== 'adicionar'){
        $query = $pdo->prepare("insert into eventos set nome =:nome, data=:data,capacidade=:capacidade,ativo= 1,usuarios_id=:usuarios_id");

        $data_antiga = strtotime ($_POSTjson['data']);
        $newdatetime = date('Y-m-d H-i-s',$data_antiga );

        $query ->bindValue(":nome", $_POSTjson['nome']);
        $query ->bindValue(":data", $newdatetime);
        $query ->bindValue(":capacidade", $_POSTjson['capacidade']);   
        $query ->bindValue(":usuarios_id", $_POSTjson['usuarios_id']);  
        
        $query->execute();
        $id = $pdo->lastInsertId();

        if ($query) {
          $result = json_encode(array('success'=>true,'id'=>$id));
         
        }else{
          $result = json_encode(array('false'=>true,'msg'=>'Falha ao Inserir o Evento!!'));

        }

       
        echo $result;  
      // Final requisição add  

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
            'data'=>$res[$i]['data'],
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
      $query = $pdo->prepare("UPDATE eventos SET nome=:nome, data=:data, capacidade=:capacidade WHERE id =:id");
      $query->bindValue(":nome",$_POSTjson['nome']);
      $query->bindValue(":data",$_POSTjson['data']);
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
