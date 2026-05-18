<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// VERIFICA ADMIN
if ($_SESSION['tipo'] != 'admin') {
    die("Acesso negado.");
}

$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

if ($conn->connect_error) {
    die("Erro de conexão");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <title>Painel Admin - Taski</title>

    <link rel="shortcut icon" href="img/favicon-16x16.png" type="image/x-icon">

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #4a2ca5;
        }

        header {
            width: 100%;
            height: 70px;
            background: linear-gradient(90deg, #4a00e0, #8e2de2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        header img {
            height: 100px;
        }

        .main {
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: linear-gradient(135deg, #0f8b8d, #5b3c88);
            padding: 30px;
            border-radius: 25px;
        }

        h2 {
            color: white;
            margin-bottom: 20px;
        }

        .usuario-card {
            background: white;
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 15px;
        }

        .usuario {
            font-size: 20px;
            font-weight: bold;
            color: #4a00e0;
        }

        .tarefas {
            margin-top: 10px;
            color: #333;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #4a90c2;
            color: white;
            text-decoration: none;
            border-radius: 15px;
        }

    </style>
</head>

<body>

<header>
    <img src="img/logo-taski-png.png" alt="Taski">
</header>

<div class="main">

<div class="container">

<h2>Painel Administrativo</h2>

<?php

$sql = "
SELECT
    usuarios.usuario,
    COUNT(tarefas.id) AS total_tarefas

FROM usuarios

LEFT JOIN tarefas 
ON usuarios.id = tarefas.usuario_id

GROUP BY usuarios.id

ORDER BY total_tarefas DESC
";

$resultado = $conn->query($sql);

while($row = $resultado->fetch_assoc()) {

    echo "

    <div class='usuario-card'>

        <div class='usuario'>
            ".$row['usuario']."
        </div>

        <div class='tarefas'>
            Total de tarefas:
            <strong>".$row['total_tarefas']."</strong>
        </div>

    </div>

    ";
}

?>

<a class='logout' href='logout.php'>
    Sair
</a>

</div>
</div>

</body>
</html>