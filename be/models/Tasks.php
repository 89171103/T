<?php

class Tasks
{
    private $connection;
    private $table = 'tasks';

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function get_task($id = 0)
    {
        $query = "SELECT * FROM " . $this->table;
        $condition = "";
        if ($id > 0)
            $condition = " WHERE `id`=" . $id;

        $query .= $condition;

        return $this->connection->query($query);

    }

    public function create_task($data)
    {
        $query = "INSERT INTO " . $this->table . "(name, person, description)";
        $values = " VALUES ('" . $data['name'] . "', '" . $data['person'] . "', '" . $data['desc'] . "')";

        $query .= $values;

        try {
            $this->connection->query($query);
        } catch (PDOException $e) {
            echo 'Inserting ERROR ' . $e->getMessage();
            die();
        }

    }

    public function update_task($data)
    {
        $query = "UPDATE " . $this->table . " SET ";
        $values = "name='" . $data['name'] . "', person='" . $data['person'] . "', description='" . $data['desc'] . "' ";
        $condition = "WHERE id =" . $data['id'];

        $query .= $values;
        $query .= $condition;

        $res = $this->get_task($data['id']);

        if ($res->rowCount() < 1) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array('message' => "Task not existing", 'error' => 1));
            die();
        }

        try {
            $this->connection->query($query);
        } catch (PDOException $e) {
            header("HTTP/1.1 500 Internal Server Error");
            echo 'Inserting ERROR: ' . $e->getMessage() . " \n\n";
            die();
        }

    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table;
        $condition = " WHERE id=" . $id;

        $query .= $condition;

        $res = $this->get_task($id);

        if ($res->rowCount() < 1) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array('message' => "Task not existing", 'error' => 1));
            die();
        }

        try {
            $this->connection->query($query);
        } catch (PDOException $e) {
            header("HTTP/1.1 500 Internal Server Error");
            echo 'Inserting ERROR: ' . $e->getMessage() . " \n\n";
            die();
        }

    }

    public function end_task($data)
    {
        $query = "UPDATE " . $this->table . " SET ";
        if ($data['done']) {
            $values = "endDate='" . date('Y-m-d h:i:s')."'";
        } else {
            $values = "endDate=NULL";
        }
        $condition = " WHERE id=" . $data['id'];

        $query .= $values;
        $query .= $condition;

        $res = $this->get_task($data['id']);

        if ($res->rowCount() < 1) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array('message' => "Task not existing", 'error' => 1));
            die();
        }


        try {
            $this->connection->query($query);
        } catch (PDOException $e) {
            header("HTTP/1.1 500 Internal Server Error");
            echo 'Inserting ERROR: ' . $e->getMessage() . " \n\n";
            die();
        }

    }

}