<?php
require_once __DIR__."/../Core/Conexao.php";
require_once __DIR__."/../Model/Cliente.php";
class ClienteDao{
    private $bd;

    public function __construct()
    {
    $this->bd = Conexao::getInstance();
    }

public function create(Cliente $cliente){
    $sql="INSERT INTO cliente (nome,email,senha) VALUES(:nome,:email,:senha)";
    $stmt =$this->bd->prepare($sql);
    $stmt->execute([
        ':nome'=>$cliente->getNome(),
        ':email'=>$cliente->getEmail(),
        ':senha'=>$cliente->getSenha()
    ]);
}
public function update(Cliente $cliente){
    $sql="UPDATE cliente SET nome=:nome,email=:email,senha=:senha WHERE id=:id";
    $stmt =$this->bd->prepare($sql);
    $stmt->execute([
        ':id'=>$cliente->getId(),
        ':nome'=>$cliente->getNome(),
        ':email'=>$cliente->getEmail(),
        ':senha'=>$cliente->getSenha()
    ]);
}

public function excluir( $id){
    $sql="DELETE FROM cliente WHERE id=:id";
    $stmt =$this->bd->prepare($sql);
    $stmt->execute([
        ':id'=>$id   
    ]);
}
public function getAll(){
    $sql="SELECT * FROM cliente";
    $clientes=[];
    $stmt =$this->bd->query($sql);
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
  $clientes[]=new Cliente(
    $row['id'],
    $row['nome'],
    $row['email'],
    $row['senha']
  );
    }
    return $clientes;
}
public function getById($id):Cliente{
    $sql="SELECT * FROM cliente WHERE id=:id";
    $stmt =$this->bd->prepare($sql);
    $stmt->execute(['id'=>$id]);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? new  Cliente(
    $row['id'],
    $row['nome'],
    $row['email'],
    $row['senha']
  ):null;
    }
 public function getByEmail($email){
    $sql="SELECT * FROM  cliente WHERE email=:email";
    $stmt = $this->bd->prepare($sql);
    $stmt->execute([':email'=>$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){
   return new Cliente(
    $row['id'],
    $row['nome'],
    $row['email'],
    $row['senha']
   );
    }else{
        return null;
    }
 }   
}

 ?>