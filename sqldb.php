<?php

// TODO: Proper Error Handling
// TODO: Type Checking
// TODO: Feature to run custom fucntion after connecting

class SQLDB {
    private $root;
    private $db_file;
    private $dsn;
    private $connected;
    public $pdo;

    function __construct() {
        $this->root = realpath(__DIR__);
        $this->connected = false;
    }

    function Connect($file_path) {
        $this->db_file = "$this->root/$file_path";
        $this->dsn = "sqlite:$this->db_file";
        try {
            $this->pdo = new PDO($this->dsn);
            $this->connected = true;
            return $this->pdo;
        } catch (PDOException $e) {
            echo "Error: Database connection failed: " . $e->getMessage();
        }
    }

    function Initialize($schema_path, $foreign_key_enable = 1) {
        if(!$this->connected) {
            echo "Error: Not yet connected to the database.<br>";
            return;
        }
        if($foreign_key_enable) $this->pdo->exec('PRAGMA foreign_keys = ON;');
        $schema_file = "$this->root/$schema_path";
        // if (filesize($this->db_file) > 0) return;
        if(file_exists($schema_file)) {
            $schema = file_get_contents($schema_file);
            try {
                $res = $this->pdo->exec($schema);
            } catch (PDOException $e) {
                echo "Error: Couldn't run schema on database " . $e->getMessage();
            }
            if($res === false) {
                echo "Error: Couldn't run schema on database file" . print_r($this->pdo->errorInfo());
                return;
            }
        } else {
            echo "Error: SQL schema not found.";
            return;
        }
    }

    function StartDBConnection($file_path, $schema_path, $foreign_key_enable = 1) {
        $this->Connect($file_path);
        $this->Initialize($schema_path, $foreign_key_enable);
    }
}