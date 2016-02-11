<?php
class Jellybean {
    protected $pdo;
    protected $dsn;
    protected $table;
    protected $where  = array();
    protected $params = array();
    private static $instance;
    public function __construct($args) {
        if (is_array($args)) {
            if (array_key_exists('driver', $args) and array_key_exists('dbname', $args)) {
                $driver = $args['driver'];
                $dbname = $args['dbname'];
            
                if ($driver == "sqlite")
                    $dsn = "sqlite:$dbname";
                else 
                    $dsn = join(',', $args);
                try {
                    $_pdo = new PDO($dsn);
                }
                catch (PDOException $err) {
                    fputs(STDERR, "Something went wrong: $err");
                    exit(1);
                }
                $this->pdo = $_pdo;
                $this->dsn = $dsn;
            }
            else {
                die("Bad usage\n");
            }
        }
        return $this;
    }
    public function _clone() {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        self::$instance->connect($this->dsn);
        return self::$instance;
    }
    public function table($rs) {
        try {
            $_table = $this->pdo->query("SELECT 1 from $rs");
        }
        catch (PDOException $err) {
            fputs(STDERR, "Table '$rs' doesn't seem to exist");
            exit(1);
        }
        $this->table = $rs;
        return $this;
    }
    public function search($args = NULL) {
        $results = array();
        if (is_null($args)) {
            try {
                $stmt = $this->pdo->query("SELECT * FROM $this->table");
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch (PDOException $err) {
                fputs(STDERR, "There was a problem searching: $err\n");
                exit(1);
            }
            return $results;            
        }
        else {
            $bindVals   = array();
            $query      = array();
            foreach ($args as $key => $value) {
                reset($args);
                
                // last iteration
                end($args);
                if ($key === key($args))
                    array_push($query, "$key=?");
                else
                    array_push($query, "$key=? AND");
                
                array_push($bindVals, $value);
            }
            
            try {
                $sql = "SELECT * FROM $this->table WHERE " . join(" ", $query);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($bindVals);
            }
            catch (PDOException $err) {
                fputs(STDERR, "There was a problem searching: $err\n");
                exit(1);
            }
            
            $this->where  = $query;
            $this->params = $bindVals;
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    public function count($col = 'id') {
        try {
            $bindParams = array($col);
            $sql = "SELECT count(?) FROM $this->table";
            if (count($this->where) > 0 and count($this->params) > 0) {
                $sql = "$sql WHERE " . join(" ", $this->where);
                $bindParams = array_merge($bindParams, (array)$this->params);
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($bindParams);
        }
        catch (PDOException $err) {
            fputs(STDERR, "There was a problem getting row count for column $col: $err\n");
            exit(1);
        }
        return $stmt->fetchColumn();
    }
}
?>
