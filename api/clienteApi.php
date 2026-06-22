<?php 
header("Content-type:application/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTION");
header("Access-Control-Allow-Headers:Content-type");

if($_SERVER['REQUEST_METHOD']==='OPTIONS'){
 http_response_code(200);
 exit;
}
require_once __DIR__."/../backend/Dao/ClienteDao.php";
require_once __DIR__."/../backend/Model/Cliente.php";

$clienteDao =new ClienteDao();
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$inputBody = json_decode(file_get_contents('php://input'),true);
switch($action){
    case 'logar':
        if(isset($_POST['email']) && isset($_POST['senha'])){
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        $cliente = $clienteDao->getByEmail($email);
        if($cliente){
            if(password_verify($senha,$cliente->getSenha())){
                http_response_code(200);
                echo json_encode([
                    'success'=>true,
                    'message'=>"Login realizado com sucesso!",
                    "cliente"=>$cliente
                ]);
            }else{
                http_response_code(404);
                echo json_encode(["error"=>"Cliente não encontrado!"]);
            }
        }else{
        http_response_code(400);
         echo json_encode(["error"=>"Email e senha são obrigatórios!"]);    
        }
     }
     break;
     case 'listar':
        echo json_encode($clienteDao->getAll());
        break;
        case 'buscar':
            if($id){
                $cliente = $clienteDao->getById($id);
                if($cliente){
                echo json_encode($cliente);
                }else{
         http_response_code(404);
         echo json_encode(["error"=>"Cliente não encontrado!"]); 
                }
                }else{
            http_response_code(400);
         echo json_encode(["error"=>"Você não passou o ID!"]); 
            }
            break;
            case 'cadastrar':
    if($_SERVER['REQUEST_METHOD']==='POST' && $inputBody){
        if(!$inputBody['nome'] ||!$inputBody['email']||!$inputBody['senha']){
            http_response_code(400);
        echo json_encode(['error'=>'Dados obrigatórios não informados!']);
        exit;
        }
        $cliente =new Cliente(null,
                              $inputBody['nome'],
                              $inputBody['email'],
             password_hash( $inputBody['senha'],PASSWORD_DEFAULT));
           if($clienteDao->create($cliente)){
               http_response_code(200);
                echo json_encode([
                    'success'=>true,
                    'message'=>"Cliente cadastrado com sucesso!",
                    "cliente"=>$cliente
                ]);
           }else{
               http_response_code(404);
         echo json_encode(["error"=>"Cliente não cadastrado!"]);  
           }                   
    }else{
           http_response_code(400);
        echo json_encode(['error'=>'Método incorreto!']);
    }
    break;
               case 'alterar':
    if($_SERVER['REQUEST_METHOD']==='PUT' && $inputBody && $id){
        if(!$inputBody['nome'] ||!$inputBody['email']||!$inputBody['senha']){
            http_response_code(400);
        echo json_encode(['error'=>'Dados obrigatórios não informados!']);
        exit;
        }
        $cliente =new Cliente($id,
                              $inputBody['nome'],
                              $inputBody['email'],
                              $inputBody['senha']);
           if($clienteDao->update($cliente)){
            http_response_code(200);
            echo json_encode(['sucess'=>'Cliente alterado com sucesso!']);
           }else{
               http_response_code(404);
         echo json_encode(["error"=>"Cliente não alterado!"]);  
           }                   
    }else{
           http_response_code(400);
        echo json_encode(['error'=>'Método incorreto!']);
    }
    break;
    case 'excluir':
        if($id && $_SERVER['REQUEST_METHOD']==='DELETE'){
         if($clienteDao->excluir($id)){
            http_response_code(200);
            echo json_encode(['message'=>'Cliente removido com sucesso!']);
         }else{
            http_response_code(404);
            echo  json_encode(['error'=>'Cliente não removido!']);
         }
        }else{
           http_response_code(400);
        echo json_encode(['error'=>'Você não passou o ID!']);    
        }
        break;
        default:
           http_response_code(400);
        echo json_encode(['error' => 'Ação inválida ,favor informar o action!']);
        break;
}

?>