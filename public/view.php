<?php

require_once '../autoload.php';

use App\Model\EventModel;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erro: Nenhum ID de evento foi fornecido.");
}

$id = intval($_GET['id']); 
$eventModel = new EventModel();

try {
    $evento = $eventModel->getEventById($id);

    if (!$evento) {
        throw new Exception("Evento não encontrado.");
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

include 'header.html'; 
?>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    font-family: Arial, sans-serif;
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.page-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.back-button {
    display: inline-block;
    margin-bottom: 20px;
    font-size: 1rem;
    color: #007bff;
    text-decoration: none;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #e2e6ea;
}

.event-details {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.event-info {
    margin-top: 20px;
}

.event-info p {
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.event-info strong {
    color: #007bff;
}

.fas {
    margin-right: 8px;
}

 </style>   

<body>
    <div class="container">
        <h1 class="page-title">Detalhes do Evento</h1>
        
        <div class="event-details">
            <a href="index.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Voltar para a Lista de Eventos
            </a>
            
            <div class="event-info">
                <p><strong>Título:</strong> <?php echo htmlspecialchars($evento['titulo']); ?></p>
                <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($evento['descricao'])); ?></p>
                <p><strong>Data e Hora de Início:</strong> <?php echo date('d/m/Y H:i', strtotime($evento['data_inicio'])); ?></p>
                <p><strong>Data e Hora de Término:</strong> <?php echo date('d/m/Y H:i', strtotime($evento['data_fim'])); ?></p>
                <p><strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($evento['created_at'])); ?></p>
                <p><strong>Atualizado em:</strong> <?php echo date('d/m/Y H:i', strtotime($evento['updated_at'])); ?></p>
            </div>
        </div>
        
        <a href="index.php" class="back-button">
            <i class="fas fa-arrow-left"></i> Voltar para a Lista de Eventos
        </a>
    </div>
</body>
</html>

