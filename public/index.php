<?php
/* Sistema de Gestão de Eventos 
 * Author: kkemel.amin
 * Data: 20/11/2024
 */

require_once realpath(__DIR__ . '/../autoload.php');

use App\Config\Database;
$connect = Database::getConnection();

$eventos = [];

try {
    $conn = Database::getConnection();
    $query = "SELECT id, titulo, descricao, data_inicio, data_fim FROM eventos WHERE ativo = 1 ORDER BY data_inicio ASC";
    $prepare = $conn->prepare($query);

    if ($prepare) {
        $prepare->execute();
        $result = $prepare->get_result(); 

        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
    } else {
        throw new Exception("Erro ao preparar a consulta SQL.");
    }
} catch (Exception $e) {
    die("Erro ao acessar o banco de dados: " . $e->getMessage());
}

include 'header.html'; 
?>

<body>
    <header>
        <h1>Gestão de Eventos</h1>
    </header>

    <div class="container">
        <div class="button-container">
            <a href="create.php" class="button">Criar Novo Evento</a>
            <div>
                <button onclick="showGrid()">Modo Lista</button>
                <button onclick="showCalendar()">Modo Calendário</button>
            </div>
        </div>

        <!-- Modo Lista -->
        <div id="grid-view" style="display: block;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($eventos)) : ?>
                        <?php foreach ($eventos as $evento) : ?>
                            <tr>
                                <td><?php echo $evento['id']; ?></td>
                                <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($evento['data_inicio'])); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($evento['data_fim'])); ?></td>
                                <td class="actions">
                                    <a href="update.php?id=<?php echo $evento['id']; ?>">Editar</a>
                                    <a href="delete.php?id=<?php echo $evento['id']; ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir este evento?');">Excluir</a>
                                    <a href="view.php?id=<?php echo $evento['id']; ?>">Visualizar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Nenhum evento encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Calendário -->
        <div id="calendar-view" style="display:none;">
            <div id="calendar"></div>
        </div>
    </div>

    <script>
    function showGrid() {
        document.getElementById('calendar-view').style.display = 'none';
        document.getElementById('grid-view').style.display = 'block';
    }

    function showCalendar() {
        document.getElementById('grid-view').style.display = 'none';
        document.getElementById('calendar-view').style.display = 'block';

        var eventos = <?php echo json_encode($eventos); ?>;

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
            },
            // Responsividade: muda a view com base no tamanho da tela
            windowResize: function(view) {
                if (window.innerWidth <= 768) { // Se for um dispositivo móvel
                    $('#calendar').fullCalendar('changeView', 'agendaDay'); // Exibe a agenda diária
                } else {
                    $('#calendar').fullCalendar('changeView', 'month'); // Exibe a view mensal para desktop
                }
            }
        });

        // Inicializar a view de acordo com a largura da tela
        if (window.innerWidth <= 768) {
            $('#calendar').fullCalendar('changeView', 'agendaDay'); // Para dispositivos móveis
        } else {
            $('#calendar').fullCalendar('changeView', 'month'); // Para telas grandes
        }
    }

    // Inicializa a função showCalendar quando o documento estiver pronto
    $(document).ready(function() {
        // Chama o modo calendário ao carregar a página
        showCalendar();
    });
    </script>
</body>
</html>
