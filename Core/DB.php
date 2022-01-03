<?php
/**
 * DB.php
 * @category   Database MYSQL
 * @package    HashPhp
 * @author     Hashmat Ali
 * @copyright  2022 Hash
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    1.0
 */
namespace Core;
use Core\Config;
use mysqli;
class DB{
    private static $instance;
    private $connection;
    private $query;
    private static $table;

    function __construct(){ $this->connection = $this->getConnection();}

    private function getConnection(){
        $conn = new mysqli(Config::get('db.host'), Config::get('db.user'), Config::get('db.pass'),Config::get('db.db'));
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
		if(Config::get('charset') && !empty(trim(Config::get('charset')))) $conn->set_charset(Config::get('charset'));
        return $conn;
    }
    
    public static function table($table=NULL){
        if(!$table) die("Error: Instance Table name missing!");
        self::$table =  $table;
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql){
        $this->query = $sql;
        return $this;
    }

    public function select($cols=NULL){
        $columns = '*';
        if($cols){
            $columns = $cols;
        }
        $this->query = "SELECT ".$columns." FROM ".self::$table;
        
        return $this;
    }

    public function where($conditions=NULL,$or = false){
        $keys = array_keys($conditions);
        $last_key = end($keys);
        if(!$conditions || count($conditions)==0)return $this;
        $where=" WHERE ";
        $and = " AND ";
        if($or){
            $and = " OR ";
        }
        if(count($conditions)==1){
            $where .= "`".array_key_first($conditions)."` = '".$conditions[array_key_first($conditions)]."'";
        }else{
            foreach($conditions as $key=>$val){
                $where .= "`".$key."` = '".$val."'";
                if($last_key != $key){
                    $where.=$and;
                }
            }
        }
        $this->query .= $where;
        return $this;
    }

    public function orWhere($conditions=NULL){
        return $this->where($conditions,true);
    }

    public function get($limit=NULL){
        if($limit && is_int($limit)){ $this->query.=" LIMIT ".$limit; }
        if(!$result = $this->connection->query($this->query)){
            die("Query: (".$this->query.")<br>Database Error: ".$this->connection->error);
        }
        $data=[];
        if($result->num_rows==1){ return (object)$result->fetch_assoc();}
        else if ($result->num_rows > 1) {
            while($row = $result->fetch_assoc()) { $data[]=(object)$row; }
            return $data;
        } else {
            return NULL;
        }
    }

    public function first(){
        return $this->get(1);
    }

    public function take($limit=NULL){
        if(!$limit) $limit=0;
        return $this->get($limit);
    }

    public function insert($data){
        $cols = implode(',',array_keys($data));
        $this->query = "INSERT INTO ".self::$table." ($cols) VALUES (".$this->sanitize(array_values($data)).")";
        return $this->execute();   
    }

    public function update($data,$id=NULL){
        if(!$id) die("Identification Missing!");
        $cols = implode(',',array_keys($data));
        $updateColmnVals=[];
		foreach($data as $key=>$value){
			$updateColmnVals[] = $key."='" .(mysqli_real_escape_string($this->connection,$value)) . "'";
		}
		$updateColmnVals = implode(',',$updateColmnVals);
        $this->query = "UPDATE ".self::$table." SET ".$updateColmnVals." WHERE id=$id";
        return $this->execute();  
    }

    public function destroy($colmn,$value){
		$this->query = "DELETE FROM ".self::$table." WHERE ".$colmn."='".mysqli_real_escape_string($this->connection,$value)."'";
        return $this->execute();
    }

    private function execute(){
        if ($this->connection->query($this->query) === TRUE) {return true;} 
        else { die("Error: " . $this->query . "<br>" . $this->connection->error);}
    }

    private function sanitize($data){
        $vals=[];
		foreach($data as $key=>$value){ $vals[] = "'" .(mysqli_real_escape_string($this->connection,$value)) . "'";}
		$vals = implode(',',$vals);
        return $vals;
    }
}
