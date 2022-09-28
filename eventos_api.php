<?php

require('config.php');

//Variavel que recebe o conteudo da requisição do app decodificando-a (Json)
$_POSTjson = json_decode(file_get_contents('php://input', true), true);

if($_POSTjson['requisicao']=='avatar'){
   
}

if ($_POSTjson['requisicao'] == 'adicionar') { // Requisição para Add


  $eve = new Evento(
    $_POSTjson['nome'],
    $_POSTjson['data_evento'],
    $_POSTjson['capacidade'],
    $_POSTjson['usuarios_id'],
    $_POSTjson['avatar']
  );
 $eve->setdata($_POSTjson);
  $eve->insert();
  if ($eve->getId() > 0) {
    $result = json_encode(array('success' => true, 'id' => $eve->getId()));
  } else {
    $result = json_encode(array('success' => false, 'msg' => 'Falha ao inserir o Evento'));
  }
  echo $result;
} else if ($_POSTjson['requisicao'] == 'list') {
  $eve = new Evento();
  if ($_POSTjson['nome'] == '') {
    $res = Evento::getList();  
  } else {
    $res = $eve->search($_POSTjson['nome']); 
  }
  for ($i = 0; $i < count($res); $i++) {
    $dados[] = array(
      'id' => $res[$i]['id'],
      'nome' => $res[$i]['nome'],
      'data_evento' => $res[$i]['data_evento'],
      'capacidade' => $res[$i]['capacidade'],
      'usuarios_id' => $res[$i]['usuarios_id'],
      'ativo' => $res[$i]['ativo']
    );
  }
  if (count($res) > 0) {
    $result = json_encode(array('success' => true, 'result' => $dados));
  } else {
    $result = json_encode(array('success' => false, 'result' => 'Eita Cláudia...'));
  }
  echo $result;
} //Fim do Listar


else if ($_POSTjson['requisicao'] == 'edit') {
  $eve = new Evento();
  $eve->setNome($_POSTjson['nome']);
  $eve->setdata_evento($_POSTjson['data_evento']);
  $eve->setcapacidade($_POSTjson['capacidade']);
  $eve->setusuarios_id($_POSTjson['usuarios_id']);
  $eve->setId($_POSTjson['id']);

  if ($eve->update()) {
    $result = json_encode(array('success' => true, 'msg' => "Deu tudo certo com alteração!"));
  } else {
    $result = json_encode(array('success' => false, 'msg' => "Dados incorretos! Falha ao atualizar o evento!"));
  }



  echo $result;
} //final da requisição Editar
else if ($_POSTjson['requisicao'] == 'excloi') {
  //$query = $pdo->query("Delete from eventos where id = $_POSTjson[id]");
  $eve = new Evento();
  $res = $eve->delete($_POSTjson['id']);
  if ($res) {
    $result = json_encode(array('success' => true, 'msg' => "Evento excluído com sucesso!"));
  } else {
    $result = json_encode(array('success' => false, 'msg' => "Falha ao excluir o evento!"));
  }
  echo $result;
} //final do excluir
else if ($_POSTjson['requisicao'] == 'ativ') {
  $eve = new Evento();
    $eve->setId($_POSTjson['id']);
    $res = $eve->ativar();
  if ($res) {
    $result = json_encode(array('success' => true, 'msg' => "Evento Ativado com sucesso!"));
  } else {
    $result = json_encode(array('success' => false, 'msg' => "Falha ao ativar o evento!"));
  }
  echo $result;
}//final do ativar
