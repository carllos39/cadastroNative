<?php
require_once __DIR__ . "/Cliente.php";

class Endereco implements JsonSerializable
{

    private ?int $id;
    private String $logradouro;
    private String $bairro;
    private String $cidade;
    private String $estado;
    private ?Cliente $cliente;

    public function __construct(?int $id, string $logradouro, String $bairro, String $cidade, String $estado, Cliente $cliente)
    {
        $this->id = $id;
        $this->logradouro = $logradouro;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->cliente = $cliente;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getLogradouro()
    {
        return $this->logradouro;
    }
    public function getBairro()
    {
        return $this->bairro;
    }
    public function getCidade()
    {
        return $this->cidade;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getCliente()
    {
        return $this->cliente;
    }
    #[Override]
    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "logradouro" => $this->logradouro,
            "bairro" => $this->bairro,
            "cidade" => $this->cidade,
            "estado" => $this->estado,
            "cliente" => $this->cliente,
        ];
    }
}
