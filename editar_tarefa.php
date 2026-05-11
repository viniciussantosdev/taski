<?php

session_start();

// VERIFICA LOGIN
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// CONEXÃO
$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// PEGAR ID
if (!isset($_GET['id'])) {
    die("ID não informado.");
}

$id = $_GET['id'];

// BUSCAR TAREFA
$stmt = $conn->prepare("
    SELECT * FROM tarefas
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Tarefa não encontrada.");
}

$tarefa = $result->fetch_assoc();

$stmt->close();

//
// ATUALIZAR
//
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    $stmt = $conn->prepare("
        UPDATE tarefas
        SET titulo = ?, descricao = ?
        WHERE id = ?
    ");

    $stmt->bind_param("ssi", $titulo, $descricao, $id);

    if ($stmt->execute()) {

        header("Location: home.php");
        exit();

    } else {

        echo "Erro ao atualizar.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
</head>

<body>

<h2>Editar Tarefa</h2>

<form method="POST">

    <input
        type="text"
        name="titulo"
        value="<?php echo $tarefa['titulo']; ?>"
        required
    >

    <br><br>

    <textarea
        name="descricao"
        required
    ><?php echo $tarefa['descricao']; ?></textarea>

    <br><br>

    <button type="submit">
        Salvar Alterações
    </button>

</form>

<br>

<a href="home.php">
    Voltar
</a>

</body>
</html>

<?php
$conn->close();
?>