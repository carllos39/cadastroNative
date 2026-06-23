<?php
require_once __DIR__ . "/../Core/Conexao.php";
require_once __DIR__ . "/../Model/Endereco.php";
require_once __DIR__."/../Model/Cliente.php";

class EnderecoDao
{
    private $bd;

    public function __construct()
    {
        $this->bd = Conexao::getInstance();
    }
    public function create(Endereco $endereco)
    {
        $sql = "INSERT INTO endereco(logradouro,bairro,cidade,estado,cliente_id)VALUES(:logradouro,:bairro,:cidade,:estado,:cliente_id)";
        $stmt = $this->bd->prepare($sql);
        $clienteId = $endereco->getCliente() ? $endereco->getCliente()->getId() : null;
        $stmt->execute([
            ':logradouro' => $endereco->getLogradouro(),
            ':bairro' => $endereco->getBairro(),
            ':cidade' => $endereco->getCidade(),
            ':estado' => $endereco->getEstado(),
            ':cliente_id' => $clienteId

        ]);
        return true;
    }
    public function update(Endereco $endereco)
    {
        $sql = "UPDATE endereco SET logradouro=:logradouro,bairro=:bairro,cidade=:cidade,estado=:estado,cliente_id=:cliente_id WHERE id=:id";
        $stmt = $this->bd->prepare($sql);
        $clienteId = $endereco->getCliente() ? $endereco->getCliente()->getId() : null;
        $stmt->execute([
            ':id' => $endereco->getId(),
            ':logradouro' => $endereco->getLogradouro(),
            ':bairro' => $endereco->getBairro(),
            ':cidade' => $endereco->getCidade(),
            ':estado' => $endereco->getEstado(),
            ':cliente_id' => $clienteId

        ]);
         return true;
    }
    public function excluir($id)
    {
        $sql = "DELETE FROM endereco WHERE id=:id";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public function getAll()
    {
        $sql = "SELECT endereco.*,
        cliente.id AS cliente_id,
        cliente.nome AS cliente_nome,
        cliente.email AS cliente_email,
        cliente.senha AS cliente_senha
        FROM endereco JOIN cliente ON endereco.cliente_id = cliente.id ";
        $stmt = $this->bd->query($sql);
        $enderecos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clienteId = new Cliente(
                $row['cliente_id'],
                $row['cliente_nome'],
                $row['cliente_email'],
                $row['cliente_senha']
            );
            $enderecos[] = new Endereco(
                $row['id'],
                $row['logradouro'],
                $row['bairro'],
                $row['cidade'],
                $row['estado'],
                $clienteId
            );
        }
        return $enderecos;
    }
        public function getById($id):Endereco
    {
        $sql = "SELECT endereco.*,
        cliente.id AS cliente_id,
        cliente.nome AS cliente_nome,
        cliente.email AS cliente_email,
        cliente.senha AS cliente_senha
        FROM endereco JOIN cliente ON endereco.cliente_id = cliente.id WHERE endereco.id =:id ";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $clienteId = new Cliente(
                $row['cliente_id'],
                $row['cliente_nome'],
                $row['cliente_email'],
                $row['cliente_senha']
            );
        return $row ? new Endereco(
                $row['id'],
                $row['logradouro'],
                $row['bairro'],
                $row['cidade'],
                $row['estado'],
                $clienteId
            ):null;
        }
        
    }

