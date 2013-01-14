<?php

/* * *********************
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 * ********************* */

/**
 * A database wrapper for MySQL
 * Extends from DBWrapper and implements all the required methods as well as a few MySQL specific ones.
 *
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */
class MySQLWrapper extends DBWrapper {
    
    /**
     * The sql query for deleting data from the database
     * @var string 
     */
    private $delete_statement = "DELETE FROM {TABLE_NAME} WHERE {WHERE}";
    
    /**
     * The sql query for selecting data from the database
     * @var string 
     */
    private $select_statement = "SELECT {FIELDS} FROM {TABLE_NAME} {JOINS} {WHERE} {GROUP} {ORDER} {LIMIT}";
    
    /**
     * The sql query for inserting data into the database
     * @var string 
     */
    private $insert_statement = 'INSERT INTO {TABLE_NAME} ({FIELDS}) VALUES("{VALUES}")';
    
    /**
     * The sql query for updating data in the database
     * @var string 
     */
    private $update_statement = "UPDATE {TABLE_NAME} SET {FIELDS} WHERE {WHERE}";
    
    /**
     * The sql query for joining tables
     * @var string 
     */
    private $join_statement = "{TYPE} JOIN {TABLE_NAME} ON {FIELD_1} = {FIELD_2}";
    
    /**
     * Constructs the database wrapper object
     * @param string $data
     * @param string $user
     * @param string $pass
     * @param string $host 
     */
    public function __construct($data, $user, $pass, $host) {
        $this->data = $data;
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        
        $this->connect();
    }
    
    /**
     * Closes the current connection 
     */
    public function close(){
        mysql_close($this->conn);
    }
    
    /**
     * Initiates a connection 
     */
    public function connect(){
        $this->conn = mysql_connect($this->host, $this->user, $this->pass);
        
        if(!$this->conn) {
            echo mysql_error();
        }
        
        mysql_select_db($this->data, $this->conn);
    }
    
    /**
     * Deletes data from a table
     * @param string $table_name
     * @param string $where 
     */
    public function delete_data($table_name, $where = 1){
        $statement = replace_tokens($this->delete_statement, array('TABLE_NAME' => "`" . $table_name . "`", 'WHERE' => $where));
        
        $success = $this->execute($statement);
        
        return $success;
    }
    
    /**
     * Executes a raw MySQL query
     * @param string $query 
     */
    public function execute($query){
        $success = mysql_query($query, $this->conn);
        
        if(!$success) {
            return $this->show_error();
        } else {
            return $success;
        }
    }
    
    /**
     * Inserts new data to the database
     * @param string $table_name
     * @param array $fields_data 
     */
    public function insert_data($table_name, $fields_data){
        $fields_data = $this->sanity($fields_data);
        $fields = implode(', ', array_keys($fields_data));
        $data = implode('", "', $fields_data);
        
        $statement = replace_tokens($this->insert_statement, array('TABLE_NAME' => $table_name, 'FIELDS' => $fields, 'VALUES' => $data));
        
        $success = $this->execute($statement);
        return $success;
    }
    
    /**
     * Replaces a piece of data in the database with new values
     * Not sure if this function is neccessary, I've never needed to use it...
     * @param string $table_name
     * @param string $fields_data
     * @param string $where
     */
    public function replace_data($table_name, $fields_data, $where){
        
    }
    
    /**
     * Selects data from the database
     * @param string $table
     * @param array $fields
     * @param string $where
     * @param string $limit
     * @param string $order
     * @param string $group
     * @param string $join 
     */
    public function select_data($table, $fields, $where = null, $limit = null, $order = null, $group = null, $join = null){
        //the table
        $table_name = (is_array($table)) ? "`{$table[0]}` AS {$table[1]}" : "`$table`";

        //the fields
        $field_string = (is_array($fields)) ? implode(", ", $fields) : $fields;
        
        //the where
        $where_string = ($where != null) ? "WHERE " . $where : "";
        
        //the limit
        $limit_string = ($limit != null) ? "LIMIT " . $limit : "";
        
        //the order
        $order_string = ($order != null) ? "ORDER BY " . $order : "";
        
        //the group
        $group_string = ($group != null) ? "GROUP BY " . $group : "";
        
        $statement = replace_tokens($this->select_statement, array('TABLE_NAME' => "$table_name", 'FIELDS' => $field_string, 'WHERE' => $where_string, 'LIMIT' => $limit_string, 'ORDER' => $order_string, 'GROUP' => $group_string, 'JOINS' => $join));
        //echo $statement;
        $success = $this->execute($statement);
        if(mysql_num_rows($success) != 0) { 
            $data = array();
            while($row = mysql_fetch_assoc($success)) {
                $data[] = $row;
            }
            
            return $data;
        } else {
            return null;
        }
    }
    
    /**
     * Updates data in the database (added literal to remove the quotation marks 
     * neccessary in some queries)
     * @param string $table_name
     * @param array $fields_data
     * @param string $where 
     * @param boolean $literal
     */
    public function update_data($table_name, $fields_data, $where, $literal = false){
        $fields_data = $this->sanity($fields_data);
        //$where = $this->sanity($where);
        $last = end(array_keys($fields_data));
        $field_string = "";
        foreach($fields_data as $field => $data) {
            $field_string .= $field . " = ";
            if($literal) {
                $field_string .= $data;
            } else {
                $field_string .= "'" . $data . "'";
            }
            
            if($field != $last) {
                $field_string .= ", ";
            }
        }
        
        $statement = replace_tokens($this->update_statement, array('TABLE_NAME' => $table_name, 'FIELDS' => $field_string, 'WHERE' => $where));
        //echo $statement;
        $success = $this->execute($statement);
        
        return $success;
        
    }
    
    /**
     * Echos a mysql error and halts execution 
     */
    protected function show_error() {
        echo mysql_error($this->conn);
        exit;
    }
    
    /**
     * Builds a join statement for joining two tables
     * @param string $type
     * @param string $table_name
     * @param array $fields
     * @return string 
     */
    public function build_joins($type, $table, $fields) {
        
        $table_name = (is_array($table)) ? "`{$table[0]}` AS {$table[1]}" : "`$table`";
        
        $statement = replace_tokens($this->join_statement, array('TYPE' => $type, 'TABLE_NAME' => "$table_name", 'FIELD_1' => $fields[0], 'FIELD_2' => $fields[1]));
        
        return $statement;
    }
    
    /**
     * Sanitizes a string or an array of strings
     * @param string/array $data
     * @return string/array 
     */
    private function sanity($data) {
        if(is_array($data)) {
            $returning = array();
            foreach($data as $key => $piece) {
                if(get_magic_quotes_gpc()) {
                    $key = stripslashes($key);
                    $piece = stripslashes($piece);
                }
                $san_key = mysql_real_escape_string($key, $this->conn);
                $san_piece = mysql_real_escape_string($piece, $this->conn);
                $returning[$san_key] = $san_piece;
            }
        } else {
            if(get_magic_quotes_gpc()) {
		$data = stripslashes($data);	
            }
            $returning = mysql_real_escape_string($data, $this->conn);
        }
        return $returning;
    }
    
    /**
     * Returns the id the item that was last inserted into the database
     * @return int 
     */
    public function get_insert_id() {
        return mysql_insert_id($this->conn);
    }
}

?>
