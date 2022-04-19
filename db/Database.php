<?php

require_once 'Config.php';

class Database
{
    private $mysqli;

    public function __construct() {
        $this->mysqli = new mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB);
        $this->mysqli->query("SET NAMES 'utf8'");
    }

    private function query($query) {
        return $this->mysqli->query($query);
    }

    private function select($table_name, $fields, $where = "", $order = "", $up = true, $limit = "") {
        for ($i = 0; $i < count($fields); $i++) {
            if ((strpos($fields[$i], "(") === false) && ($fields[$i] != "*")) $fields[$i] = "`".$fields[$i]."`";
        }
        $fields = implode(",", $fields);
        if (!$order) $order = "ORDER BY `id`";
        else {
            if ($order != "RAND()") {
                $order = "ORDER BY `$order`";
                if (!$up) $order .= " DESC";
            }
            else $order = "ORDER BY $order";
        }
        if ($limit) $limit = "LIMIT $limit";
        if ($where) $query = "SELECT $fields FROM $table_name WHERE $where $order $limit";
        else $query = "SELECT $fields FROM $table_name $order $limit";
        $result_set = $this->query($query);
        if (!$result_set) return false;
        $data = [];
        $i = 0;
        while ($row = $result_set->fetch_assoc()) {
            $data[$i] = $row;
            $i++;
        }
        $result_set->close();
        return $data;
    }

    public function insert($table_name, $new_values) {
        $query = "INSERT INTO $table_name (";
        foreach ($new_values as $field => $value) $query .= "`".$field."`,";
        $query = substr($query, 0, -1);
        $query .= ") VALUES (";
        foreach ($new_values as $value) $query .= "'".addslashes($value)."',";
        $query = substr($query, 0, -1);
        $query .= ")";
        return $this->query($query);
    }

    public function update($table_name, $upd_fields, $where) {
        $query = "UPDATE $table_name SET ";
        foreach ($upd_fields as $field => $value) $query .= "`$field` = '".addslashes($value)."',";
        $query = substr($query, 0, -1);
        if ($where) {
            $query .= " WHERE $where";
            return $this->query($query);
        }
        else return false;
    }

    public function updateOnID($table_name, $id, $upd_fields) {
        return $this->update($table_name, $upd_fields, "`id` = '$id'");
    }

    public function getField($table_name, $field_out, $field_in, $value_in) {
        $data = $this->select($table_name, array($field_out), "`$field_in`='".addslashes($value_in)."'");
        if (count($data) !== 1) return false;
        return $data[0][$field_out];
    }

    public function getElementOnID($table_name, $id) {
        $arr = $this->select($table_name, array("*"), "`id` = '$id'");
        return $arr[0];
    }

    public function getUsersWithoutOrders(): array
    {
        $query = 'SELECT users.login FROM users LEFT JOIN orders ON orders.user_id = users.id GROUP BY users.login HAVING COUNT(orders.id) = 0';
        $result = $this->query($query);
        $data = [];
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $data[$i] = $row;
            $i++;
        }
        $result->close();
        return $data;
    }

    public function getUsersWithOrders(): array
    {
        $query = 'SELECT users.login FROM users LEFT JOIN orders ON orders.user_id = users.id GROUP BY users.login HAVING COUNT(orders.id) > 2';
        $result = $this->query($query);
        $data = [];
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $data[$i] = $row;
            $i++;
        }
        $result->close();
        return $data;
    }

    public function getEmailDuplicates()
    {
        $query = 'SELECT email FROM users GROUP BY email HAVING count(email) > 1';
        $result = $this->query($query);
        $data = [];
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $data[$i] = $row;
            $i++;
        }
        $result->close();
        return $data;
    }

    public function __destruct() {
        if ($this->mysqli) $this->mysqli->close();
    }
}
