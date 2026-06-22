<?php
require_once __DIR__."/Cliente.php";
class Produto implements JsonSerializable
{
    private ?int $id;
    private String $nome;
    private float $preco;
    private String $data_validade;
    private ? Cliente $cliente;
    public function __construct(?int $id, String $nome, float $preco, String $data_validade,Cliente $cliente)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->data_validade = $data_validade;
        $this->cliente = $cliente;
    }

    public function getId(){
        return $this->id;
    }
      public function getNome(){
        return $this->nome;
    }
      public function getPreco(){
        return $this->preco;
    }
      public function getData_validade(){
        return $this->data_validade;
    }
      public function getCliente(){
        return $this->cliente;
    }

    
    public function jsonSerialize(): mixed
    {
        return [
            'id'=>$this->id,
            'nome'=>$this->nome,
            'preco'=>$this->preco,
            'data_validade'=>$this->data_validade,
            'cliente'=>$this->cliente,
        ];

    }
}
