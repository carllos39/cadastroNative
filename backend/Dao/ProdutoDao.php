<?php
require_once __DIR__."/../Core/Conexao.php";
require_once __DIR__."/../Model/Produto.php";
require_once __DIR__."/../Model/Cliente.php";

class ProdutoDao{
    private $bd;
    public function __construct()
    {
        $this->bd = Conexao::getInstance();
    }
    public function create(Produto $produto){
        $sql="INSERT INTO produto (nome,preco,data_validade,cliente_id) VALUES(:nome,:preco,:data_validade,:cliente_id)";
        $stmt=$this->bd->prepare($sql);
        $clienteId = $produto->getCliente()?$produto->getCliente()->getId():null; 
        $stmt->execute([
            ':nome' => $produto->getNome(),
            ':preco' => $produto->getPreco(),
            ':data_validade' => $produto->getData_validade(),
            ':cliente_id' => $clienteId
        ]);
        return true;
    }
      public function update(Produto $produto){
        $sql="UPDATE produto SET nome=:nome,preco=:preco,data_validade=:data_validade,cliente_id=:cliente_id WHERE id=:id";
        $stmt=$this->bd->prepare($sql);
        $clienteId = $produto->getCliente()?$produto->getCliente()->getId():null; 
        $stmt->execute([
            ':id' => $produto->getId(),
            ':nome' => $produto->getNome(),
            ':preco' => $produto->getPreco(),
            ':data_validade' => $produto->getData_validade(),
            ':cliente_id' => $clienteId
        ]);
         return true;
    }
        public function excluir($id){
        $sql="DELETE FROM produto  WHERE id=:id";
        $stmt=$this->bd->prepare($sql);
        $stmt->execute([
            ':id' => $id    
        ]);
    }
    public function getAll(){
        $sql= "SELECT produto.*,
        cliente.id AS cliente_id,
        cliente.nome AS cliente_nome,
        cliente.email AS cliente_email,
        cliente.senha AS cliente_senha
        FROM produto JOIN cliente ON produto.cliente_id = cliente.id ";
        $stmt =$this->bd->query($sql);
        $produtos=[];
        while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
    $clienteId = new Cliente(
        $row['cliente_id'],
        $row['cliente_nome'],
        $row['cliente_email'],
        $row['cliente_senha'],
    );
     $produtos[] = new Produto(
        $row['id'],
        $row['nome'],
        $row['preco'],
        $row['data_validade'],
        $clienteId
     );
        }
        return $produtos;
    }
        public function getById($id):Produto{
        $sql= "SELECT produto.*,
        cliente.id AS cliente_id,
        cliente.nome AS cliente_nome,
        cliente.email AS cliente_email,
        cliente.senha AS cliente_senha
        FROM produto JOIN cliente ON produto.cliente_id = cliente.id WHERE produto.id=:id ";
        $stmt =$this->bd->prepare($sql);
        $stmt->execute([':id'=>$id]);
        $row =$stmt->fetch(PDO::FETCH_ASSOC);
    $clienteId = new Cliente(
        $row['cliente_id'],
        $row['cliente_nome'],
        $row['cliente_email'],
        $row['cliente_senha']
    );
     return $row ? new Produto(
        $row['id'],
        $row['nome'],
        $row['preco'],
        $row['data_validade'],
        $clienteId
     ):null;
        }
        
    }

?>