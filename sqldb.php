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

    function Initialize($schema_path) {
        if (!$this->connected) {
            echo "Error: Not yet connected to the database.<br>";
            return;
        }
        $schema_file = "$this->root/$schema_path";
        $schema_tmstmp = "$schema_file.timestamp";
        if (file_exists($schema_file)) {
            if (file_exists($schema_tmstmp)) {
                if (filemtime($schema_file) <= file_get_contents($schema_tmstmp)) {
                    return;
                }
            }
            try {
                $this->pdo->exec(file_get_contents($schema_file));
                $file = fopen($schema_tmstmp, "w");
                fwrite($file, time());
                fclose($file);
            } catch (PDOException $e) {
                echo "Error: Couldnt run schema on database " . $e->getMessage();
            }
        } else {
            echo "Error: SQL schema not found.";
            return;
        }
    }

    function StartDBConnection($file_path, $schema_path) {
        $this->Connect($file_path);
        $this->Initialize($schema_path);
    }
}
