<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Header:Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . "/../backend/Dao/ProdutoDao.php";
require_once __DIR__ . "/../backend/Dao/ClienteDao.php";
require_once __DIR__ . "/../backend/Model/Produto.php";

$produtoDao = new ProdutoDao();

$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$inputBody = json_decode(file_get_contents('php://input'),true);
switch ($action) {
    case 'listar':
        echo json_encode($produtoDao->getAll());
        break;
    case 'buscar':
        if ($id) {
            $produto = $produtoDao->getById($id);
            if ($produto) {
                http_response_code(200);
                echo json_encode($produto);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Produto não encontrado!']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Você não passou o ID!']);
        }
        break;
       case 'cadastrar':
        if($_SERVER['REQUEST_METHOD']==='POST' && $inputBody){
            if(empty($inputBody['nome']) ||
            empty($inputBody['preco'])||
            empty($inputBody['data_validade'])||
            empty($inputBody['cliente_id'])){
              http_response_code(404);
                echo json_encode(['error' => 'Dados inválidos!']); 
                exit; 
            }
            $clienteId=$inputBody['cliente_id'];
            $clienteDao = new ClienteDao();
            $cliente = $clienteDao->getById($clienteId);
            if(!$cliente){
           echo json_encode([
            'success' => false,
            'error' => 'Cliente não encontrado!'
]);
                exit;    
            }
            $produto = new Produto(
                null,
                $inputBody['nome'],
                $inputBody['preco'],
                $inputBody['data_validade'],
                $cliente
            );
            if($produtoDao->create($produto)){
  http_response_code(200);
                echo json_encode([
                    'success'=>true,
                    'message'=>"Produto cadasrado com sucesso!",
                    "produto"=>$produto
                ]);
            }else{
        http_response_code(400);
           echo json_encode([
         'success' => false,
         'error' => 'Produto não cadastrado!'
]);  
            }
        }else{
         echo json_encode([
         'success' => false,
        'error' => 'Método incorreto!'
]);   
        }
        break;
      case 'editar':

    if ($_SERVER['REQUEST_METHOD']==='PUT' && $inputBody && $id) {

        if (
            empty($inputBody['nome']) ||
            empty($inputBody['preco']) ||
            empty($inputBody['data_validade']) ||
            empty($inputBody['cliente_id'])
        ) {

            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Dados inválidos!'
            ]);
            exit;
        }

        $clienteId = $inputBody['cliente_id'];

        $clienteDao = new ClienteDao();
        $cliente = $clienteDao->getById($clienteId);

        if (!$cliente) {

            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Cliente não encontrado!'
            ]);
            exit;
        }

        $produto = new Produto(
            $id,
            $inputBody['nome'],
            $inputBody['preco'],
            $inputBody['data_validade'],
            $cliente
        );

        if ($produtoDao->update($produto)) {

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Produto alterado com sucesso!'
                
            ]);

        } else {

            http_response_code(400);
            echo json_encode([
                'success' =>false,
                'error' => 'Produto não alterado!'
            ]);
        }

    } else {

        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Método incorreto!'
        ]);
    }
    break;   
    case 'excluir':
        if ($id && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            if ($produtoDao->excluir($id)) {
                   http_response_code(200);
                echo json_encode([
                  'success' => true,
                  'message' => 'Produto removido com sucesso!'
]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' =>false,
                    'error'=>'Produto não removido!']);
            }
        } else {
            http_response_code(400);
            echo json_encode([
            'success' =>false,
            'error'=> 'Método incorreto!']);
        }
        break;
    default:
        echo json_encode(['success' =>false,
        'error'=> 'Ação inválida ,favor informar o action!']);
        break;
}
