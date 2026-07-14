<?php

namespace AbsCin\Database;

use PDO;
use PDOException;
use Exception;

class DBConnection {
    private static ?self $instance = null;
    private static PDO $pdo;
    
    private function __construct(
        private string $host,
        private string $user,
        private string $pass,
        private string $dbname,
        private ?array $opts = null,
        private ?string $init_script = null
    ) {}

    public function Connect() {
        if($this->pdo != null) return $this->pdo;
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $this->opts);
        } catch (PDOException $e) {
            throw new Exception("Database Connection failed: " . $e->getMessage());
        }
        if($this->init_script != null) $this->RunScript($this->init_script);
        return $this->pdo;
    }

    private function RunScript($script_file) {
        if (!file_exists($script_file)) {
            echo("Error: SQL script not found.");
            return null;
        }
        $script_tmstmp = "$script_file.timestamp";
        if ((int)filemtime($script_file) <= (int)file_get_contents($script_tmstmp)) {
            return 0;
        }
        $this->Connect()->exec(file_get_contents($script_file));
        $file = fopen($script_tmstmp, "w");
        fwrite($file, time());
        fclose($file);
    }

    public static function Init(
        string $host,
        string $user,
        string $pass,
        string $dbname,
        ?array $opts = null,
        ?string $init_script = null
    ) {
        self::$instance ??= new self($host, $user, $pass, $dbname, $opts, $init_script);
        return self::$instance;
    }

    public static function GetInstance() {
        if(self::$instance === null) {
            throw new Exception("No Database Connection Found.");
        }
        return self::$instance;
    }
}
