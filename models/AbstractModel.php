<?php

require_once 'db/Config.php';
require_once 'db/Database.php';

abstract class AbstractModel
{
    private $db;
    private $table_name;

    protected function __construct($table_name, $db) {
        $this->db = $db;
        $this->table_name = $table_name;
    }

    protected function add($new_values) {
        return $this->db->insert($this->table_name, $new_values);
    }

    protected function edit($id, $upd_fields) {
        return $this->db->updateOnID($this->table_name, $id, $upd_fields);
    }

    protected function getField($field_out, $field_in, $value_in) {
        return $this->db->getField($this->table_name, $field_out, $field_in, $value_in);
    }

    public function get($id) {
        return $this->db->getElementOnID($this->table_name, $id);
    }

    public function getUsersWithoutOrders() {
        return $this->db->getUsersWithoutOrders();
    }

    public function getUsersWithOrders() {
        return $this->db->getUsersWithOrders();
    }

    public function getEmailDuplicates() {
        return $this->db->getEmailDuplicates();
    }
}
