<?php
session_start();
include("conexao.php");

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha'";
$resultado = mysqli_query($conexao, $sql);

if (mysqli_num_rows($resultado) > 0) {

    $dados = mysqli_fetch_assoc($resultado);

    $_SESSION['usuario'] = $dados['usuario'];

    $_SESSION['tipo'] = $dados['tipo'];

    $_SESSION['id'] = $dados['id'];

    if ($dados['tipo'] == 'admin') {

        header("Location: admin.php");

    } else {

        header("Location: home.php");

    }

    exit();

} else {

    echo "Usuário ou senha incorretos!";
}

?>