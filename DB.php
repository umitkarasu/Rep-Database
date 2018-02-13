<?php
  class DB {
    private static $_instance = null;
    private $_pdo,
    $_query,
    $_results,
    $_count = 0,
    $_dbSettings;

    private function __construct($dbSettings) {
      $_dbSettings = $dbSettings;
      try {
        $this->_pdo = new PDO(
          'mysql:host='.$dbSettings['host'].';dbname='.$dbSettings['database'],
          $dbSettings['username'],
          $dbSettings['password']
        );
        echo '<br>connected to '.$dbSettings['database'].' database.';
      } catch(PDOexception $e) {
        die($e->getMessage());
      }

    }

    public static function getInstance($dbSettings) {

      if (!isset(self::$_instance)) {
  			self::$_instance=new DB($dbSettings);
  		}

      return self::$_instance;
    }

    public function query($sql) {

  		$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  		$this->_query = $this->_pdo->prepare($sql);
  		if($this->_query->execute()) {

  				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
  				$this->_count = $this->_query->rowCount();

  			} else {

  				return false;

  			}
  	}


    public function find($table, $fields = [], $conditions = []) {

      $sql = 'SELECT *';

      if(!empty($fields)) {

        $fields = implode(',', $fields);
        $sql = 'SELECT '.$fields;

      }

      $sql .= ' FROM '.$table;

      if(!empty($conditions)) {

        $conditionSet = [];

        foreach ($conditions as $key => $value) {

          $conditionSet[] = $key.''.$value;

        }

        $conditions = implode(' AND ', $conditionSet);

        $sql .= ' WHERE '.$conditions;

      }
      $this->query($sql);

    }

    public function createDB($name) {

      $sql = 'CREATE DATABASE '.$name;

      if (!empty($this->_pdo->query($sql))) {

          echo $name.' Database created successfully';

      } else {
          echo "Error creating database! ";
      }
    }

    public function copyTableSchema($table, $database) {

      if ($this->_pdo->query("CREATE TABLE {$database}.{$table} LIKE {$this->_dbSettings}.{$table}")) {

        echo $table." created.";

      }

    }

    public function insert($table, $results, $database) {

      $values = [];

      foreach ($results as $result) {

        $keys = array_keys($result);

        $values = array_values($result);

        $val = '';

        foreach ( $values as $value) {

          if (is_numeric($value)) {

            $val .= $value.',';

          } elseif (empty($value)) {

            $val .= 'NULL,';

          } else {

            $val .= "'".$value."',";

          }

        }
        $val = rtrim($val , ',');

  			$sql="INSERT INTO {$database}.{$table} (`".implode('`,`', $keys)."`) VALUES ({$val})";
        print_r($sql);
        $this->_pdo->query($sql);

  		}
  	}



    public function results() {

  		return $this->_results;

  	}

    public function count() {

  		return $this->_count;

  	}







}
 ?>
