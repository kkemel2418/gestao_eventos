<?php
require_once realpath(__DIR__.'/../autoload.php');

use App\Model\EventModel;

$eventModel = new EventModel();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $evento = $eventModel->getEventById($id);
    } catch (Exception $e) {
        die("Erro: ".$e->getMessage());
    }
} else {
    die("ID do evento não fornecido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $data_inicio = $_POST['data_inicio'];
    $hora_inicio = $_POST['hora_inicio'];
    $data_fim = $_POST['data_fim'];
    $hora_fim = $_POST['hora_fim'];

    $datahora_inicio = date('Y-m-d H:i:s', strtotime($data_inicio.' '.$hora_inicio));
    $datahora_fim = date('Y-m-d H:i:s', strtotime($data_fim.' '.$hora_fim));

    try {
        if (strtotime($datahora_inicio) >= strtotime($datahora_fim)) {
            throw new Exception("A data e hora de início devem ser anteriores à data e hora de término.");
        }

        $intervaloMinimo = 60 * 60; // 1 hora em segundos
        if ((strtotime($datahora_fim) - strtotime($datahora_inicio)) < $intervaloMinimo) {
            throw new Exception("O intervalo entre as datas deve ser de pelo menos 1 hora.");
        }

        $eventModel->updateEvent($id, $titulo, $descricao, $datahora_inicio, $datahora_fim);
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        // Exibe a mensagem de erro, se ocorrer
        $error_message = "Erro ao atualizar evento: ".$e->getMessage();
    }
}


include 'header.html'; 
?>

<body>
    <header>
        <h1>Atualizar Evento</h1>
    </header>

    <div style="margin-bottom: 1rem;">
        <a href="index.php" style="text-decoration: none; color: #007BFF; font-weight: bold;">&larr; Voltar para a Lista de Eventos</a>
    </div>

    <div class="container">
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form id="event-form" action="update.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-info">
                <span class="required">Todos os campos são obrigatórios.</span>
            </div>
            <label for="titulo" class="required">Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required>
            <label for="descricao" class="required">Descrição:</label>
            <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($evento['descricao']); ?></textarea>
            <label for="data_inicio" class="required">Data Hora de Início:</label>
            <div style="display: flex; gap: 10px;">
                <input type="date" name="data_inicio" id="data_inicio" value="<?php echo date('Y-m-d', strtotime($evento['data_inicio'])); ?>" required>
                <input type="time" name="hora_inicio" id="hora_inicio" value="<?php echo date('H:i', strtotime($evento['data_inicio'])); ?>" required>
            </div>
            <label for="data_fim" class="required">Data Hora de Término:</label>
            <div style="display: flex; gap: 10px;">
                <input type="date" name="data_fim" id="data_fim" value="<?php echo date('Y-m-d', strtotime($evento['data_fim'])); ?>" required>
                <input type="time" name="hora_fim" id="hora_fim" value="<?php echo date('H:i', strtotime($evento['data_fim'])); ?>" required>
            </div>
            <button type="submit">Atualizar Evento</button>
        </form>
        <a href="index.php">Voltar para a lista de eventos</a>
    </div>

    <script>
        $(document).ready(function() {
            // Bloqueia datas anteriores ao dia atual
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // Janeiro é 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            // Define o valor de "min" nos campos de data para bloquear datas passadas
            $('#data_inicio, #data_fim').attr('min', today);

            // Função de validação de datas
            function validateDateTime() {
                var dataInicio = $('#data_inicio').val();
                var horaInicio = $('#hora_inicio').val();
                var dataFim = $('#data_fim').val();
                var horaFim = $('#hora_fim').val();

                if (dataInicio && horaInicio && dataFim && horaFim) {
                    var dataHoraInicio = new Date(dataInicio + ' ' + horaInicio);
                    var dataHoraFim = new Date(dataFim + ' ' + horaFim);

                    if (dataHoraInicio >= dataHoraFim) {
                        // Se a data de início for maior ou igual à de término, exibe uma mensagem de erro
                        $('#error_message').text("A data e hora de início devem ser anteriores à data e hora de término.").show();
                        $('button[type="submit"]').prop('disabled', true); // Desativa o botão de envio
                    } else {
                        // Se as datas forem válidas, oculta a mensagem de erro e habilita o botão de envio
                        $('#error_message').hide();
                        $('button[type="submit"]').prop('disabled', false);
                    }
                }
            }

            // Validação na submissão do formulário
            $('#event-form').submit(function(e) {
                var dataInicio = $('#data_inicio').val();
                var horaInicio = $('#hora_inicio').val();
                var dataFim = $('#data_fim').val();
                var horaFim = $('#hora_fim').val();

                var dataHoraInicio = new Date(dataInicio + ' ' + horaInicio);
                var dataHoraFim = new Date(dataFim + ' ' + horaFim);

                if (dataHoraInicio >= dataHoraFim) {
                    alert("A data e hora de início devem ser anteriores à data e hora de término.");
                    e.preventDefault(); // Impede o envio do formulário
                }
            });

            // Chama a função de validação quando a página é carregada, caso já haja uma data preenchida
            validateDateTime();
        });
    </script>
</body>
</html>
