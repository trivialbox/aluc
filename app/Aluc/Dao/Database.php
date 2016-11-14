<?php
namespace Aluc\Dao;

define('CONFIG_FILE', __DIR__ . '/../../../config/database.json');

/**
 * Clase para interactuar con la base de datos.
 */
class Database {
    private $host;
    private $user;
    private $pass;
    private $name;
    private $port;

    private $conn;

    public function __construct() {
        $this->load_configuration();
    }

    private function load_configuration() {
        $json_file = file_get_contents(CONFIG_FILE);
        $config = json_decode($json_file, true);

        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->name = $config['name'];
        $this->port = $config['port'];
    }

    public function connect() {
        $this->conn = new \mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->name,
            $this->port
        );
        $this->conn->set_charset("utf8");
        if ($this->conn->connect_error) {
            throw new \Exception(
                "Conexión fallida. {$this->conn->connect_error}"
            );
        }
    }

    public function disconnect() {
        if (isset($this->conn)) {
            $this->conn->close();
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function error() {
        return $this->conn->error;
    }

    public function call($procedure_name, $values) {
        $items = $this->quote_array_string($values);
        $values = implode(',', $items);

        $sql = "CALL {$procedure_name($values)}";

        if (!$this->query($sql)) {
            throw new \Exception(
                "Error al insertar {$values}. {$this->error()}"
            );
        }
    }

    public function insert($table_name, $values) {
        $items = $this->cat_values($values);
        $keys = implode(',', $items['keys']);
        $values = implode(',', $items['values']);
        $sql = "INSERT INTO {$table_name} ({$keys}) VALUES ({$values})";
        if (!$this->query($sql)) {
            throw new \Exception(
                "Error al insertar {$values}. {$this->error()}"
            );
        }
    }

    private function cat_values($array) {
        echo var_dump($array) . "<br>";
        $keys = array_keys($array);
        echo "keys" . $keys;
        $values = $this->quote_array_string(array_values($array));
        return array(
            'keys' => $keys,
            'values' => $values
        );
    }

    private function quote_array_string($array) {
        $values = array();
        foreach ($array as $value) {
            $values[] = $this->quote_string($value);
        }
        return $values;
    }

    private function quote_string($string) {
        return "'{$string}'";
    }

    public function select($table_name, $columns = '*', $where = null, $order = null) {
        if ($columns !== '*') {
            $columns = $this->quote_array_string($columns);
            $columns = implode(',', $columns);
        }
        $sql = "SELECT {$columns} FROM {$table_name}";
        if ($where != null) {
            $sql .= " WHERE {$where}";
        }
        if ($order != null) {
            $sql .= " ORDER BY {$order}";
        }
        $result = $this->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function delete($view_name, $where) {
        $sql = "DELETE FROM {$view_name} WHERE {$where}";
        $this->query($sql);
    }

    public function update($view_name, $columns, $where) {
        $sql = "UPDATE {$view_name}";
        echo "colunm" . var_dump($columns) . "<br>";
        $columns_set = array();
        foreach ($columns as $key => $value){
            array_push($columns_set,$key . '=' . $this->quote_string($value));

        }
        echo var_dump($columns_set);
        $sql .= " SET " . implode(',', $columns_set);
        $sql .= " WHERE = {$this->quote_string($where)}";
        echo "<br>" . "sql ---->  " . $sql . "<br>";
        if (!$this->query($sql)) {
            throw new \Exception(
                "Error al actualizar {$values}. {$this->error()}"
            );
        }
    }




}
