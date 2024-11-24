<?php
namespace App\Model;

use Exception;

class EventModel
{
    private $connect;

    public function __construct()
    {
        $this->connect = new \mysqli("localhost", "root", "123456", "gestao_eventos");

        if ($this->connect->connect_error) {
            die("Falha na conexão: " . $this->connect->connect_error);
        }
    }

    public function getAllEvents()
    {
        $query = "SELECT id, titulo, descricao, data_inicio, data_fim FROM eventos WHERE ativo = 1 ORDER BY data_inicio ASC";
        $result = $this->connect->query($query);

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    public function getEventsInRange($startDate, $endDate)
    {

        $query = "SELECT id, titulo, descricao, data_inicio, data_fim FROM eventos 
                  WHERE ativo = 1 AND data_inicio BETWEEN ? AND ? ORDER BY data_inicio ASC";
        
        $prepare = $this->connect->prepare($query);
        
        if (!$prepare) {
            throw new Exception("Erro ao preparar a consulta: " . $this->connect->error);
        }

        $prepare->bind_param("ss", $startDate, $endDate);
        $prepare->execute();
        $result = $prepare->get_result();

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    public function getEventById($id)
    {
        $query = "SELECT * FROM eventos WHERE id = ?";
        $prepare = $this->connect->prepare($query);

        if (!$prepare) {
            throw new Exception("Erro ao preparar a consulta: " . $this->connect->error);
        }

        $prepare->bind_param("i", $id);
        $prepare->execute();
        $result = $prepare->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            throw new Exception("Evento com ID $id não encontrado.");
        }
    }

    public function createEvent($titulo, $descricao, $data_inicio, $data_fim)
    {
        $prepare = $this->connect->prepare(
            "INSERT INTO eventos (titulo, descricao, data_inicio, data_fim, created_at, updated_at, ativo) 
            VALUES (?, ?, ?, ?, NOW(), NOW(), 1)"
        );
        
        if (!$prepare) {
            throw new Exception("Erro ao preparar a consulta: " . $this->connect->error);
        }

        $prepare->bind_param("ssss", $titulo, $descricao, $data_inicio, $data_fim);

        if (!$prepare->execute()) {
            throw new Exception("Erro ao criar evento: " . $prepare->error);
        }

        $prepare->close();
        return "Evento criado com sucesso!";
    }

    public function updateEvent($id, $titulo, $descricao, $data_inicio, $data_fim)
    {
        $prepare = $this->connect->prepare(
            "UPDATE eventos SET titulo = ?, descricao = ?, data_inicio = ?, data_fim = ?, updated_at = NOW() WHERE id = ?"
        );

        if (!$prepare) {
            throw new Exception("Erro ao preparar a consulta: " . $this->connect->error);
        }

        $prepare->bind_param("ssssi", $titulo, $descricao, $data_inicio, $data_fim, $id);

        if (!$prepare->execute()) {
            throw new Exception("Erro ao atualizar evento: " . $prepare->error);
        }

        $prepare->close();
        return "Evento atualizado com sucesso!";
    }

    //  (soft delete)
    public function deleteEvent($id)
    {
        $prepare = $this->connect->prepare("UPDATE eventos SET ativo = 0, updated_at = NOW() WHERE id = ?");
        if (!$prepare) {
            throw new Exception("Erro ao preparar a consulta: " . $this->connect->error);
        }

        $prepare->bind_param("i", $id);

        if (!$prepare->execute()) {
            throw new Exception("Erro ao excluir evento: " . $prepare->error);
        }

        $prepare->close();
        return "Evento excluído com sucesso!";
    }
}
