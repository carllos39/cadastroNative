<?php
session_start();
header('Contente-Type:Application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
header('Access-Control-Allow-Headers:Content-Type:');

if($_SERVER['REQUEST_METHOD']==='OPTIONS'){
    http_response_code(200);
    exit;
}
require_once __DIR__."/../backend/Dao/EnderecoDao.php";
require_once __DIR__."/../backend/Dao/ClienteDao.php";
require_once __DIR__."/../backend/Model/Endereco.php";
require_once __DIR__."/../backend/Core/Sessao.php";


$enderecoDao =new EnderecoDao();

$action=$_GET['action']?? null;
$id=$_GET['id']?? null;
$inputBody=json_decode(file_get_contents('php://input'),true);

switch($action){
    case 'listar':
        echo json_encode($enderecoDao->getAll($idCliente,$tipo));
        break;
    case 'buscar':
        if($id){
            $endereco=$enderecoDao->getById($id);
            if($endereco){
                http_response_code(200);
            echo json_encode($endereco);
            }else{
                http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Endereço não encontrado!'
                ]);
            }
        }else{
           http_response_code(400);
                echo json_encode([
                 'success' => false,
                  'error' => 'Você não informou o Id!'
                ]);    
        }
        break;
        case 'cadastrar':
            if($_SERVER['REQUEST_METHOD']==='POST' && $inputBody){
          if(empty($inputBody['logradouro'])||
             empty($inputBody['bairro'])||
             empty($inputBody['cidade'])||
             empty($inputBody['estado'])||
             empty($inputBody['cliente_id'])
          ){
             http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Dados inválidos!'
                ]);
                exit;
          }
          $clienteId = $inputBody['cliente_id'];
          $clienteDao = new ClienteDao();
          $cliente = $clienteDao->getById($clienteId);
          if(!$cliente){
                 http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Cliente não encontrado!'
                ]);
                exit;
          } 
          $endereco = new Endereco(
            null,
            $inputBody['logradouro'],
            $inputBody['bairro'],
            $inputBody['cidade'],
            $inputBody['estado'],
            $cliente
          );
          if($enderecoDao->create($endereco)){
            http_response_code(200);
            echo json_encode(['success'=>true,
                              'message'=>'Endereço cadastrado com sucesso!' ]);
          }else{
                 http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Endereço não cadastrado!'
                ]);
          }
            }else{
                     http_response_code(400);
                echo json_encode([
                 'success' => false,
                  'error' => 'Dados inválidos ou métodos incorreto!'
                ]);
            }
            break;
              case 'editar':
            if($_SERVER['REQUEST_METHOD']==='PUT' && $inputBody && $id){
          if(empty($inputBody['logradouro'])||
             empty($inputBody['bairro'])||
             empty($inputBody['cidade'])||
             empty($inputBody['estado'])||
             empty($inputBody['cliente_id'])
          ){
             http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Dados inválidos!'
                ]);
                exit;
          }
          $clienteId = $inputBody['cliente_id'];
          $clienteDao =new ClienteDao();
          $cliente = $clienteDao->getById($clienteId);
          if(!$cliente){
                 http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Cliente não encontrado!'
                ]);
                exit;
          } 
          $endereco = new Endereco(
            $id,
            $inputBody['logradouro'],
            $inputBody['bairro'],
            $inputBody['cidade'],
            $inputBody['estado'],
            $cliente
          );
          if($enderecoDao->update($endereco)){
            http_response_code(200);
            echo json_encode(['success'=>true,
                              'message'=>'Endereço editado com sucesso!' ]);
          }else{
                 http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Endereço não editado!'
                ]);
          }
            }else{
                     http_response_code(400);
                echo json_encode([
                 'success' => false,
                  'error' => 'Dados inválidos ou métodos incorreto!'
                ]);
            }
            break;
            case 'excluir':
    if($id && $_SERVER['REQUEST_METHOD']==='DELETE'){
       if($enderecoDao->excluir($id)){
            http_response_code(200);
            echo json_encode(['success'=>true,
                              'message'=>'Endereço excluido com sucesso!' ]);
       }else{
           http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Endereço não excluido!'
                ]); 
       }
    }else{
            http_response_code(400);
                echo json_encode([
                 'success' => false,
                  'error' => 'Você não informou o ID!'
                ]);
    }
    break;
    default:
        http_response_code(404);
                echo json_encode([
                 'success' => false,
                  'error' => 'Ação inválida ,favor informar o action!'
                ]);
}  

?>