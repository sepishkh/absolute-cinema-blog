<?php

namespace AbsCin\Database;

use PDO;

// TODO: Make this singleton
class DBConnection {
    private $pdo;
    
    public function Connect() {
        if($this->pdo != null) return $this->pdo;
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $this->user, $this->pass, $this->opts);
        if($this->init_script != null) $this->RunScript($this->init_script);
        return $this->pdo;
    }

    public function RunScript($script_file) {
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

    public function __construct(
        private string $host,
        private string $user,
        private string $pass,
        private string $dbname,
        private ?array $opts = null,
        private ?string $init_script = null
    ) {}
}
