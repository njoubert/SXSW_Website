<?php
class SQLQuery {
    protected $_dbHandle;
    protected $_result;
	protected $_table;
	protected $_numRows;
	protected $_debug;

	function __construct() {
		$this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	}

	function __destruct() {
	}

    /** Connects to database **/

    function connect($address, $account, $pwd, $name) {
        $this->_dbHandle = @mysql_connect($address, $account, $pwd);
        if ($this->_dbHandle != 0) {
            if (mysql_select_db($name, $this->_dbHandle)) {
                return 1;
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }
    }
    
    /** Toggle debug mode on **/
    function toggleDebug($on=true) {
    	if($on) {
    		$this->_debug = true;
    	} else {
    		$this->_debug = false;
    	}
    }

    /** Disconnects from database **/

    function disconnect() {
        if (@mysql_close($this->_dbHandle) != 0) {
            return 1;
        }  else {
            return 0;
        }
    }
    
	function chooseTable($tableName) {
		$this->_table = mysql_real_escape_string($tableName);
	}
		
    function selectAll() {
    	$query = 'select * from `'.$this->_table.'`';
    	return $this->query($query);
    }
    
    function select($id) {
    	$query = 'select * from `'.$this->_table.'` where `id` = \''.mysql_real_escape_string($id).'\'';
    	return $this->query($query);    
    }
	
	function selectWhatWhere($fields, $condition) {
		$query = 'select ' . mysql_real_escape_string($fields) . ' from `'.$this->_table.'` where '.$condition;
		return $this->query($query);    
    }

	function selectAllOrderBy($order) {
    	$query = 'SELECT * FROM `'.$this->_table.'` ORDER BY '.mysql_real_escape_string($order);
    	return $this->query($query);
    }
	
	function delete($id) {
    	$query = 'DELETE FROM `'.$this->_table.'` WHERE `id` = \''.mysql_real_escape_string($id).'\'';
    	$this->cQuery($query);    
    }
    
    function deleteWhere($condition) {
    	$query = 'DELETE FROM `'.$this->_table.'` WHERE '.$condition;
    	$this->cQuery($query);    
    }

	function addItem($fields, $values) {
		$valToInsert = "";
		if(is_array($values)) {
			$DS = "";
			foreach($values as $key => $value) {
				$valToInsert .= $DS . "'" . mysql_real_escape_string($value) . "'";
				$DS = ",";
			}
		} else {
			$valToInsert = mysql_real_escape_string($values);
		}
		$query = 'INSERT INTO ' .$this->_table. '(id,' . mysql_real_escape_string($fields) . ') VALUES(NULL,' . $valToInsert . ')';
		return $this->cQuery($query);    
    }
    
    function addItemsArray($values) {
    	$fieldsToInsert = "";
		$valToInsert = "";
		if(is_array($values)) {
			$DS = "";
			foreach($values as $key => $value) {
				$fieldsToInsert .= $DS . mysql_real_escape_string($key);
				$valToInsert .= $DS . "'" . mysql_real_escape_string($value) . "'";
				$DS = ",";
			}
		} else {
			$valToInsert = mysql_real_escape_string($values);
		}
		$query = 'INSERT INTO ' .$this->_table. '(' . $fieldsToInsert . ') VALUES(' . $valToInsert . ')';
		return $this->cQuery($query);
    }
    
    function replaceItem($values) {
    	$fieldsToInsert = "";
		$valToInsert = "";
		if(is_array($values)) {
			$DS = "";
			foreach($values as $key => $value) {
				$fieldsToInsert .= $DS . mysql_real_escape_string($key);
				$valToInsert .= $DS . "'" . mysql_real_escape_string($value) . "'";
				$DS = ",";
			}
		} else {
			$valToInsert = mysql_real_escape_string($values);
		}
		$query = 'REPLACE INTO ' .$this->_table. '(' . $fieldsToInsert . ') VALUES(' . $valToInsert . ')';
		return $this->cQuery($query);
    }
    
    function addItemIgnore($fields, $values) {
		$valToInsert = "";
		if(is_array($values)) {
			$DS = "";
			foreach($values as $key => $value) {
				$valToInsert .= $DS . "'" . mysql_real_escape_string($value) . "'";
				$DS = ",";
			}
		} else {
			$valToInsert = mysql_real_escape_string($values);
		}
		$query = 'INSERT IGNORE INTO ' .$this->_table. '(id,' . mysql_real_escape_string($fields) . ') VALUES(NULL,' . $valToInsert . ')';
		return $this->cQuery($query);    
    }

	function insert($fields, $values) {
		$query = 'INSERT INTO ' . $this->_table . '(' . mysql_real_escape_string($fields) . ') VALUES(' . mysql_real_escape_string($value) . ')';
		return $this->cQuery($query);    
    }
    
    function appendUpdateString($_string, $_array, $_keys) {
	    foreach($_keys as $_key) {
	        if(array_key_exists($_key, $_array)) array_push($_string, $_key."='".mysql_real_escape_string(trim($_array[$_key]))."'");
	    }
	    return $_string;
	}
	
	function updateArrayToString($_string) {
		$_return = "";
		$_separator = "";
		foreach($_string as $_key => $_value) {
			$_return .= $_separator.$_value;
			$_separator = ",";
		}
		return $_return;
	}
	
	function updateWhatWhereArray($_values, $_fields, $_where) {
		$updateArray = array();
		$updateArray = $this->appendUpdateString($updateArray, $_values, $_fields);
		if(count($updateArray) > 0) {
	    	$valToInsert = $this->updateArrayToString($updateArray);
	    	return $this->updateWhatWhere($valToInsert, $_where);
	    } else return 0;
	}
	
	function updateWhatWhereArray2($_keyvalues, $_where) {
		$_values = $_keyvalues;
		$_fields = array_keys($_keyvalues);
		$updateArray = array();
		$updateArray = $this->appendUpdateString($updateArray, $_values, $_fields);
		if(count($updateArray) > 0) {
	    	$valToInsert = $this->updateArrayToString($updateArray);
	    	return $this->updateWhatWhere($valToInsert, $_where);
	    } else return 0;
	}
	
	function updateWhatWhere($fields, $where) {
		$query = 'UPDATE ' . $this->_table . ' SET ' . $fields . ' WHERE ' . $where;
		return $this->cQuery($query);    
    }

	function getLastId() {
		return mysql_insert_id($this->_dbHandle);    
    }
	
	private function cQuery($query) {
		return mysql_query($query, $this->_dbHandle);
	}
	
	function query($query, $singleResult = 0) {
		if($this->_debug) {
			var_dump($query);
		}
		
		$this->_result = mysql_query($query, $this->_dbHandle);

		if (preg_match("/select/i",$query)) {
			$this->_numRows = mysql_num_rows($this->_result);
			$result = array();
			$table = array();
			$field = array();
			$tempResults = array();
			$numOfFields = mysql_num_fields($this->_result);
			for ($i = 0; $i < $numOfFields; ++$i) {
			    array_push($table,mysql_field_table($this->_result, $i));
			    array_push($field,mysql_field_name($this->_result, $i));
			}
			while ($row = mysql_fetch_row($this->_result)) {
				for ($i = 0;$i < $numOfFields; ++$i) {
					$table[$i] = $table[$i];
					$tempResults[$table[$i]][$field[$i]] = $row[$i];
				}
				if ($singleResult == 1) {
		 			mysql_free_result($this->_result);
					return $tempResults;
				}
				array_push($result,$tempResults);
			}
			mysql_free_result($this->_result);
			return($result);
		}
	}

    function getNumRows() {
        return $this->_numRows;
    }

    function error() {
        return mysql_error($this->_dbHandle);
    }
}
?>