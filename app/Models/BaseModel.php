<?php
namespace App\Models;

use App\Core\Database;

abstract class BaseModel {
    protected \PDO $db;
    public function __construct() { $this->db = Database::connection(); }
}
