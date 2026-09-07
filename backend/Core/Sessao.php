<?php
if(!isset($_SESSION)){
session_start();
}
function verificarAcesso(){
    if(!isset($_SESSION['id'])){
        session_destroy();
        header("location:index.php?acesso_negado");
    }
}

function login($id,$email,$tipo){
    $_SESSION['id'] = $id;
    $_SESSION['email'] = $email;
    $_SESSION['tipo'] = $tipo;
}
function logout(){
        session_destroy();
        header("location:index.php?sair");
        exit;  
}
function verificarTipo(){
    if($_SESSION['tipo'] !='admin'){
     header("location:../../nao-autorizado.php");
     exit;
}

}
function verificarTipos(){
    if($_SESSION['tipos'] !='admin' || 'recepcionista'){
     header("location:../../nao-autorizado.php");
     exit;
}
}
?>