<?php

class SQLDB {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $init_script;
    public $pdo;
    
    public function Connect() {
        if($this->pdo != null) return $this->pdo;
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4";
        $opts = [
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $this->pdo = new PDO($dsn, $this->user, $this->pass, $opts);
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

    public function __construct($host, $user, $pass, $dbname, $init_script = null) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->dbname = $dbname;
        $this->init_script = $init_script;
        $this->Connect();
    }
}
