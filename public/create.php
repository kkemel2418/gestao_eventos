<?php

require_once '../autoload.php'; 

use Utils\ApiUtils;
use App\Model\EventModel;

// Instancia os objetos necessários
$eventModel = new EventModel();

// Trata a requisição POST para criar um evento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitiza e valida os campos recebidos
        $titulo = ApiUtils::sanitizeString($_POST['titulo'], true); 
        $descricao = ApiUtils::sanitizeString($_POST['descricao'], true); 
        $data_inicio = ApiUtils::sanitizeDateTime($_POST['data_inicio'], $_POST['hora_inicio']);
        $data_fim = ApiUtils::sanitizeDateTime($_POST['data_fim'], $_POST['hora_fim']);


        if (strtotime($data_inicio) >= strtotime($data_fim)) {
            throw new Exception("A data e hora de início devem ser anteriores à data e hora de término.");
        }

        $eventModel->createEvent($titulo, $descricao, $data_inicio, $data_fim);

        ApiUtils::redirect('index.php');
    } catch (Exception $e) {
    
        $error_message = ApiUtils::formatErrorMessage($e->getMessage());
    }
}

$eventos = $eventModel->getAllEvents();

include 'header.html'; 
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script>
    $(document).ready(function() {
        // Bloqueia datas anteriores ao dia atual
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // Janeiro é 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        $('#data_inicio, #data_fim').attr('min', today);

        if ($('#calendar').is(':visible')) {
            showCalendar();
        } else {
            console.warn('O calendário está escondido. Verifique a visibilidade do elemento.');
        }
        $('#data_fim, #hora_fim').on('change', function() {
            validateDateTime();
        });

        function validateDateTime() {
            var dataInicio = $('#data_inicio').val();
            var horaInicio = $('#hora_inicio').val();
            var dataFim = $('#data_fim').val();
            var horaFim = $('#hora_fim').val();

            if (dataInicio && horaInicio && dataFim && horaFim) {
                var dataHoraInicio = new Date(dataInicio + ' ' + horaInicio);
                var dataHoraFim = new Date(dataFim + ' ' + horaFim);

                if (dataHoraInicio >= dataHoraFim) {
   
                    $('#error_message').text("A data e hora de início devem ser anteriores à data e hora de término.").show();
                    $('button[type="submit"]').prop('disabled', true); 
                } else {

                    $('#error_message').hide();
                    $('button[type="submit"]').prop('disabled', false);
                }
            }
        }
        validateDateTime();
    });

    function showGrid() {
        document.getElementById('calendar-view').style.display = 'none';
        document.getElementById('grid-view').style.display = 'block';
    }

    function showCalendar() {
        document.getElementById('grid-view').style.display = 'none';
        document.getElementById('calendar-view').style.display = 'block';

        var eventos = <?php echo json_encode($eventos); ?>;

        // Verifica se o calendário já foi inicializado e destrua, se necessário
        if ($('#calendar').hasClass('fc')) {
            $('#calendar').fullCalendar('destroy');
        }

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: eventos.map(function(evento) {
                return {
                    title: evento.titulo,
                    start: evento.data_inicio,
                    end: evento.data_fim,
                    description: evento.descricao
                };
            }),
            eventClick: function(event) {
                alert('Título: ' + event.title + '\nDescrição: ' + event.description);
            }
        });
    }
</script>

<body>
    <header>
        <h1>Criar Novo Evento</h1>
    </header>

    <div style="margin-bottom: 1rem;">
        <a href="index.php" style="text-decoration: none; color: black; font-weight: bold; margin: 10px;">
            <i class="fas fa-arrow-left"></i> Voltar para a Lista de Eventos
        </a>
    </div>

    <div class="container">
        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>

        <form action="create.php" method="POST">
            <div class="form-info">
                <span class="required">Todos os campos são obrigatórios.</span>
            </div>
            
            <label for="titulo" class="required">Título:</label>
            <input type="text" name="titulo" id="titulo" required>
            
            <label for="descricao" class="required">Descrição:</label>
            <textarea name="descricao" id="descricao" required></textarea>
            
            <label for="data_inicio" class="required">Data Hora de Início:</label>
            <div style="display: flex; gap: 10px;">
                <input type="date" name="data_inicio" id="data_inicio" required>
                <input type="time" name="hora_inicio" id="hora_inicio" required>
            </div>
            
            <label for="data_fim" class="required">Data Hora de Término:</label>
            <div style="display: flex; gap: 10px;">
                <input type="date" name="data_fim" id="data_fim" required>
                <input type="time" name="hora_fim" id="hora_fim" required>
            </div>

            <!-- Exibe mensagem de erro -->
            <div id="error_message" style="color: red; display: none;"></div>

            <button type="submit" disabled>Novo Evento</button>
        </form>
        
        <a href="index.php">Voltar para a lista de eventos</a>
    </div>
</body>
</html>
