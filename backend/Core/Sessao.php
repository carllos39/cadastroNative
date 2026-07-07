<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verificarAcesso()
{
    if (!isset($_SESSION['id'])) {
        session_destroy();

        header("Location: ../../login.php");
        exit;
    }
}

function login($id, $email, $tipo)
{
    $_SESSION['id'] = $id;
    $_SESSION['email'] = $email;
    $_SESSION['tipo'] = $tipo;
}

function logout()
{
    session_unset();
    session_destroy();
}

function verificarTipo()
{
    if (
        !isset($_SESSION['tipo']) ||
        $_SESSION['tipo'] !== 'admin'
    ) {
        header("Location: ../../nao_autorizado.php");
        exit;
    }
}
?>