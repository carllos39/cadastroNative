<?php
 class Conexao{
    public static function getInstance(){
        $db_host="localhost";
        $db_name="cadastrodb";
        $db_user="root";
        $db_pass="";

        try{
       $pdo = new PDO("mysql:db_host={$db_host};dbname={$db_name};",$db_user,$db_pass);
       return $pdo;
        }catch(PDOException $e){
            die("Erro de conexão com banco" .$e->getMessage());
        }
    }

    
 }
 //$db = Conexao::getInstance();
 ?>