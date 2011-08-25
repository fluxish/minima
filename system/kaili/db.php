<?php 

namespace Kaili;

/**
 * Kaili DB Class
 *
 * Class to manage sql operations to db. Extends PDO class.
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 * @see         http://www.php.net/manual/en/class.pdo.php
 * @see         http://www.php.net/manual/en/class.pdostatement.php
 */

class Db extends PDO
{
    private $_config;
    
    public function __construct()
    {
        $this->_config = Loader::get_instance()->load('config');
        
        $dbconnection = $this->_config->item('dbconnection');
        
        $dsn = $this->_config->item('db',$dbconnection,'dbdriver') .
        ':host=' . $this->_config->item('db',$dbconnection,'hostname') .
        ';port=' . $this->_config->item('db',$dbconnection,'port') .
        ';dbname=' . $this->_config->item('db',$dbconnection,'database');
        
        try{
            parent::__construct($dsn, $this->_config->item('db',$dbconnection,'username'), 
                $this->_config->item('db',$dbconnection,'password'));
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    
    /**
     * Generate and execute a SELECT statement
     * @param string $table the name of the table
     * @param array $fields an associative array with key-value pairs
     * @param array $where an array of all fields in WHERE clause
     * @return PDO_Statement
     */
    public function select($table, $fields = '*', $where = array(), $options = array())
    {
        try{
            //select
            $query = '';
            if(is_array($fields)){
                $query = 'SELECT '.implode(', ', $fields);
            } else {
                $query = 'SELECT '.$fields;
            }
            //from
            $query .= ' FROM '.$table;
            //where
            if(!empty($where)){
                $query .= ' WHERE '.$this->_prepare_parameters($where, ' and ');
            }
            //other options
            if(!empty($options['order'])){
                $query .= ' ORDER BY '.$options['order'][0].' '.$options['order'][1];
            }
            if(!empty($options['limit'])){
                $query .= ' LIMIT '.$options['limit'][0].', '.$options['limit'][1];
            } 
            
            return $this->query($query);
        }
        catch (PDOException $e) {
            print_r($e);
        }
    }
    
    /**
     * Generate and execute an INSERT statement
     * @param string $table the name of the table
     * @param array $fields an associative array with key-value pairs
     * @param array $where an array of all fields in WHERE clause
     * @return PDO_Statement
     */
    public function insert($table, $fields)
    {
        try{
            $query = 'INSERT INTO '.$table.' SET '
                .$this->_prepare_parameters($fields, ', ');
            return $this->query($query);
        }
        catch (PDOException $e) {
            print_r($e);
        }
    }
    
    /**
     * Generate and execute an UPDATE statement
     * @param string $table the name of the table
     * @param array $fields an associative array with key-value pairs
     * @param array $where an array of all fields in WHERE clause
     * @return PDO_Statement
     */
    public function update($table, $fields, $where)
    {
        try{
            $query = 'UPDATE '.$table
                .' SET '.$this->_prepare_parameters($fields, ', ')
                .' WHERE '.$this->_prepare_parameters($where, ' and ');
            return $this->query($query);
        }
        catch (PDOException $e) {
            print_r($e);
        }
    }
    
    /**
     * Generate and execute a DELETE statement
     * @param string $table the name of the table
     * @param array $where an array of all fields in WHERE clause
     * @return PDO_Statement
     */
    public function delete($table, $where)
    {
        try{
            $query = 'DELETE FROM '.$table.' WHERE '
                .$this->_prepare_parameters($where, ' and ');
            
            return $this->query($query);
        }
        catch (PDOException $e) {
            print_r($e);
        }
    }
    
    public function count($table)
    {
        $count = $this->select($table, array('COUNT(*)'))->fetch();
        return $count[0];
    }
    
    /**
     * Prepare an array of key-value pairs for a statement
     * @param array $fields an associative array
     * @param string $sep a separator
     * @return string
     */ 
    private function _prepare_parameters($data, $sep)
    {
        $params = array();
        foreach($data as $f=>$v){
            $params[] = $f.'='.$this->quote($v);
        }
        return implode($sep, $params);
    }
}

