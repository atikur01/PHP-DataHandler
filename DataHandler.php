<?php

class DataHandler
{
    private $connection;
    private $tableName;

    public function __construct($host, $database, $username, $password, $tableName)
    {
        $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->tableName = $tableName;
    }

    public function insert($data)
    {
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", $data) . "'";

        $query = "INSERT INTO $this->tableName ($columns) VALUES ($values)";

        $this->executeQuery($query);
    }

    public function update($id, $data)
    {
        $setClause = implode(", ", array_map(function ($key, $value) {
            return "$key='$value'";
        }, array_keys($data), $data));

        $query = "UPDATE $this->tableName SET $setClause WHERE id=$id";

        $this->executeQuery($query);
    }

    public function delete($id)
    {
        $query = "DELETE FROM $this->tableName WHERE id=$id";

        $this->executeQuery($query);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id=$id";

        return $this->executeQuery($query);
    }

    public function getAll()
    {
        $query = "SELECT * FROM $this->tableName";

        return $this->executeQuery($query);
    }

    private function executeQuery($query)
    {
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Example usage
$dataHandler = new DataHandler('localhost', 'students', 'root', '', 'students');

// Insert example
$dataHandler->insert(['name' => 'John Doe', 'age' => 25, 'grade' => 'A']);

// Update example
//$dataHandler->update(1, ['name' => 'Updated John Doe', 'age' => 26, 'grade' => 'B']);

// Delete example
//$dataHandler->delete(1);

// Get by ID example
//$result = $dataHandler->getById(2);
//print_r($result);

// Get all example
//$result = $dataHandler->getAll();
//print_r($result);

?>
