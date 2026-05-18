<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$id = $_GET['id'];

// BUSCAR TAREFA
$sql = "SELECT * FROM tarefas WHERE id = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$tarefa = $resultado->fetch_assoc();

if (!$tarefa) {
    die("Tarefa não encontrada.");
}

// SALVAR ALTERAÇÕES
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    $update = $conn->prepare("
        UPDATE tarefas
        SET titulo = ?, descricao = ?
        WHERE id = ?
    ");

    $update->bind_param("ssi", $titulo, $descricao, $id);

    if ($update->execute()) {

        header("Location: home.php");
        exit();

    } else {

        echo "Erro ao atualizar tarefa.";

    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title>Editar Tarefa</title>

    <link rel="shortcut icon" href="img/favicon-16x16.png" type="image/x-icon">

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #4a2ca5;
            color: #1f1735;
        }

        header {
            width: 100%;
            min-height: 70px;
            padding: 10px 20px;
            background: linear-gradient(90deg, #4a00e0, #8e2de2);
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 10px 30px rgba(34, 12, 85, 0.2);
        }

        .brand-box {
            padding: 10px 24px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.96);
        }

        header img {
            height: 68px;
        }

        .page {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .card {
            width: min(650px, 100%);
            padding: 30px;
            border-radius: 26px;
            background: linear-gradient(135deg, #0f8b8d, #5b3c88);
            box-shadow: 0 18px 45px rgba(18, 8, 44, 0.22);
            color: #fff;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 30px;
        }

        .subtitle {
            margin-bottom: 24px;
            color: rgba(255,255,255,0.88);
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: bold;
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 12px 14px;
            border: none;
            border-radius: 16px;
            background: #f4f2fb;
            color: #1f1735;
            font-size: 14px;
        }

        .field textarea {
            min-height: 160px;
            resize: vertical;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 22px;
        }

        .btn,
        .back-link {

            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border: none;
            border-radius: 16px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
        }

        .btn {
            background-color: #4a90c2;
            color: #fff;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #3b7aa3;
        }

        .back-link {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }

    </style>
</head>

<body>

<header>
    <div class="brand-box">
        <img src="img/logo-taski-png.png" alt="Taski">
    </div>
</header>

<main class="page">

    <section class="card">

        <h2>Editar Tarefa</h2>

        <p class="subtitle">
            Atualize o título e a descrição da tarefa.
        </p>

        <form method="POST">

            <div class="field">

                <label for="titulo">Título</label>

                <input
                    id="titulo"
                    type="text"
                    name="titulo"
                    value="<?php echo $tarefa['titulo']; ?>"
                    required
                >

            </div>

            <div class="field">

                <label for="descricao">Descrição</label>

                <textarea
                    id="descricao"
                    name="descricao"
                    required
                ><?php echo $tarefa['descricao']; ?></textarea>

            </div>

            <div class="actions">

                <button class="btn" type="submit">
                    Salvar Alterações
                </button>

                <a class="back-link" href="home.php">
                    Voltar
                </a>

            </div>

        </form>

    </section>

</main>

</body>
</html>

<?php
$conn->close();
?>