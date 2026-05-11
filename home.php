<?php
session_start();

if (!isset($_SESSION['usuario'])) { //verifica login
    header("Location: index.php");
    exit();
}


$conn = new mysqli("localhost", "root", "", "sistema_usuarios"); //coon: conecta com o banco de dados

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}


if (isset($_GET['excluir'])) { //função excluir tarefa

    $id = $_GET['excluir'];

    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?"); //deleta da tabela
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { //cadastro de tarefas

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    $stmt = $conn->prepare("
        INSERT INTO tarefas (titulo, descricao, status)
        VALUES (?, ?, 'pendente')
    ");

    $stmt->bind_param("ss", $titulo, $descricao);

    if ($stmt->execute()) {
        echo "Tarefa cadastrada com sucesso!<br><br>";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tarefas</title>
</head>
<body>
<h2>Bem vindo! </h2>

<p>
Bem-vindo à Taski! Aqui você organiza suas tarefas e aumenta sua produtividade 🚀
</p>

<a href="logout.php">Sair</a>

<hr>

<h2>Cadastrar Tarefa</h2>

<form method="POST">

    <input
        type="text"
        name="titulo"
        placeholder="Título"
        required
    >
    <br><br>

    <textarea
        name="descricao"
        placeholder="Descrição"
        required
    ></textarea>

    <br><br>

    <button type="submit">
        Cadastrar
    </button>

</form>

<hr>

<h2>Lista de Tarefas</h2>

<?php

if (isset($_GET['status']) && isset($_GET['id'])) {

    $id = $_GET['id'];
    $novoStatus = $_GET['status']; // status da tarefa

    $stmt = $conn->prepare("
        UPDATE tarefas
        SET status = ?
        WHERE id = ? 
    ");

    $stmt->bind_param("si", $novoStatus, $id);
    $stmt->execute(); //executa o status da tarefa

    $stmt->close();
}


if (isset($_GET['excluir'])) {

    $id = $_GET['excluir'];

    $stmt = $conn->prepare("
        DELETE FROM tarefas
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute(); //mesma coisa que status porém, exclui a tarefa

    $stmt->close();
}

$sql = "SELECT * FROM tarefas ORDER BY id DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) { //lista de tarefas

        echo "<div style='
            border:1px solid #ccc;
            padding:15px;
            margin-bottom:10px;
            border-radius:10px;
        '>";


        if ($row['status'] == 'pendente') { //mostra na tela um botão de pendente

            echo "
            <a href='?id=".$row['id']."&status=concluido'
               style='
                    text-decoration:none;
                    color:orange;
                    font-weight:bold;
               '>
               🟡 Pendente
            </a>
            ";

        } else { //se clicar em "pendente, fica em concluido"

            echo "
            <a href='?id=".$row['id']."&status=pendente'
               style='
                    text-decoration:none;
                    color:green;
                    font-weight:bold;
               '>
               🟢 Concluído
            </a>
            ";
        }

        echo "<h3>" . $row['titulo'] . "</h3>"; //pega do banco de dados o título da tarefa

        echo "<p>" . $row['descricao'] . "</p>"; //mostra a descrição da tabela


        echo "
        <br><br>

<a href='editar_tarefa.php?id=".$row['id']."'
   style='
        color:blue;
        margin-right:10px;
        text-decoration:none;
   '>
   Editar
</a>

<a href='?excluir=".$row['id']."' 
   onclick=\"return confirm('Deseja excluir?')\"
   style='
        color:red;
        text-decoration:none;
   '>
   Excluir
</a>";
    }

} else {

    echo "Nenhuma tarefa cadastrada.";

}

$conn->close();


?>


</body>
</html>