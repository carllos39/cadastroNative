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
    $sql="INSERT INTO cliente (nome,email,senha,tipo) VALUES(:nome,:email,:senha,:tipo)";
    $stmt =$this->bd->prepare($sql);
    $stmt->execute([
        ':nome'=>$cliente->getNome(),
        ':email'=>$cliente->getEmail(),
        ':senha'=>$cliente->getSenha(),
        ':tipo'=>$cliente->getTipo()
    ]);
    return true;
}
public function update(Cliente $cliente){
    $sql="UPDATE cliente SET nome=:nome,email=:email,senha=:senha,tipo=:tipo WHERE id=:id";
    $stmt =$this->bd->prepare($sql);
    $stmt->execute([
        ':id'=>$cliente->getId(),
        ':nome'=>$cliente->getNome(),
        ':email'=>$cliente->getEmail(),
        ':senha'=>$cliente->getSenha(),
          ':tipo'=>$cliente->getTipo()
    ]);
    return true;
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
    $row['senha'],
    $row['tipo']
  );
    }
    return $clientes;
}
public function getById($id):Cliente{
    $sql="SELECT * FROM cliente WHERE id=:id";
    $stmt =$this->bd->prepare($sql);
    $stmt->execute([':id'=>$id]);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? new  Cliente(
    $row['id'],
    $row['nome'],
    $row['email'],
    $row['senha'],
    $row['tipo']
  ):null;
    }
 public function getByEmail($email,$tipo){
    $sql="SELECT * FROM  cliente WHERE email=:email AND tipo=:tipo";
    $stmt = $this->bd->prepare($sql);
    $stmt->execute([
        ':email'=>$email,
        ':tipo'=>$tipo,

         ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){
   return new Cliente(
    $row['id'],
    $row['nome'],
    $row['email'],
    $row['senha'],
    $row['tipo']
   );
    }else{
        return null;
    }
 }  
 

}

 ?>