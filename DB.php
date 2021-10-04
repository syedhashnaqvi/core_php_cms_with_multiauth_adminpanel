<?php
namespace root;
use mysqli;
class DB {

    private static $instance;
    protected $connection;
	protected $query;
    protected $show_errors = TRUE;
    protected $query_closed = TRUE;
	public $query_count = 0;


    private function __construct(){
        include "App\config.php";
		$dbhost = $database['host'];
		$dbname = $database['db'];
		$dbuser = $database['user'];
		$dbpass = $database['pass'];
        $charset = $database['charset'];

		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->connection->connect_error) {
			$this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		$this->connection->set_charset($charset);
    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
        self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($query) {
        if (!$this->query_closed) {
            $this->query->close();
        }
		if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
           	if ($this->query->errno) {
				$this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
           	}
            $this->query_closed = FALSE;
			$this->query_count++;
        } else {
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
		return $this;
    }
    public function fetchAll($callback = null) {
	    $params = array();
        $row = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
		return $result;
	}

    
	public function fetchArray() {
	    $params = array();
        $row = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
        $this->query->close();
        $this->query_closed = TRUE;
		return $result;
	}

	public function fetchObject() {
	    return (object) self::fetchArray();
	}

	public function close() {
		return $this->connection->close();
	}

    public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

    public function lastInsertID() {
    	return $this->connection->insert_id;
    }

    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }

	private function _gettype($var) {
	    if (is_string($var)) return 's';
	    if (is_float($var)) return 'd';
	    if (is_int($var)) return 'i';
	    return 'b';
	}

	public function findById($id,$tableName){
		return $this->query('SELECT * FROM '.$tableName.' WHERE id = '.$id)->fetchObject();
	}

	public function getAll($tableName){
		return $this->query('SELECT * FROM '.$tableName)->fetchAll();
	}

	public function store($data, $tableName)
	{
		$cols = implode(',',array_keys($data));
		$vals=[];
		foreach($data as $key=>$value){
			$vals[] = "'" .(mysqli_real_escape_string($this->connection,$value)) . "'";
		}
		$vals = implode(',',$vals);
		return $this->query("INSERT INTO ".$tableName." (".$cols.") VALUES (".$vals.")")->affectedRows();
	}

	public function update($id,$data, $tableName)
	{
		$cols = implode(',',array_keys($data));
		$updateColmnVals=[];
		foreach($data as $key=>$value){
			$updateColmnVals[] = $key."='" .(mysqli_real_escape_string($this->connection,$value)) . "'";
		}
		$updateColmnVals = implode(',',$updateColmnVals);
		return $this->query("UPDATE ".$tableName." set ".$updateColmnVals." where id = ".$id)->affectedRows();
	}

	public function destroy($tableName,$colmn,$value){
		return $this->query("DELETE FROM ".$tableName." WHERE ".$colmn."='".mysqli_real_escape_string($this->connection,$value)."'")->affectedRows();
	}
}