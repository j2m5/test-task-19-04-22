<?php
require_once 'AbstractModel.php';

class Order extends AbstractModel
{
    public function __construct($db) {
        parent::__construct("orders", $db);
    }
}
