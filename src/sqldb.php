<?php

class SQLDB {
    public $pdo;

    function __construct($host, $user, $pass, $dbname) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $opts = [
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $this->pdo = new PDO($dsn, $user, $pass, $opts);
    }

    function RunScript($script_file) {
        if ($this->pdo == null) {
            echo "Error: Not connected to the database.<br>";
            return;
        }
        if (!file_exists($script_file)) {
            echo("Error: SQL script not found.");
            return null;
        }
        $script_tmstmp = "$script_file.timestamp";
        if ((int)filemtime($script_file) <= (int)file_get_contents($script_tmstmp)) {
            return 0;
        }
        $this->pdo->exec(file_get_contents($script_file));
        $file = fopen($script_tmstmp, "w");
        fwrite($file, time());
        fclose($file);
    }
}
