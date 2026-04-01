<?php
session_start();

if (!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>

<h2>Bem vindo, <?php echo $_SESSION['usuario'];?>!</h2>

<p>
Bem-vindo à Taski! Aqui você organiza suas tarefas e aumenta sua produtividade 🚀
</p>

<form action="logout.php" method="POST">
    <button type="submit">Sair</button>
</form>

</body>
</html>