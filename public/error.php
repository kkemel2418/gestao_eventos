<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        .error-container h1 {
            color: #ff4d4d;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .error-container p {
            color: #333;
            margin-bottom: 1.5rem;
        }
        .error-container a {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            font-size: 1rem;
        }
        .error-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <?php
        $error_msg= "Ocorreu um erro.";
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'missing_id':
                    $error_msg = "ID do evento não fornecido.";
                    break;
                case 'event_not_found':
                    $error_msg = "Evento não encontrado.";
                    break;
                case 'fetch_error':
                    $error_msg = "Erro ao buscar os dados do evento.";
                    break;
            }
        }
        ?>
        <h1>!Erro!</h1>
        <p><?php echo $error_msg; ?></p>
        <a href="index.php">Voltar</a>
    </div>
</body>
</html>
