<?php
class Cliente implements JsonSerializable
{
    private ?int $id;
    private String $nome;
    private String $email;
    private String $senha;
    private String $tipo;

    public function __construct(?int $id,string $nome,string $email, String $senha,$tipo)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->tipo = $tipo;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getSenha()
    {
        return $this->senha;
    }
       public function getTipo()
    {
        return $this->tipo;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'tipo'=>$this->tipo
        ];
    }
}
