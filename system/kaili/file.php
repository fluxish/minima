<?php

namespace Kaili;

/**
 * Kaili File Class
 *
 * Class that wrap a file in the filesystem
 *
 * @package Kaili
 */
class File
{

    /**
     * Create a Directory object
     * @param string $file the name of the config file to load at the creation
     * @return Config
     */
    public static function factory($path)
    {
        return new static($path);
    }
    
    /**
     * The absolute path
     * @var string
     */
    private $_path;
    
    /**
     * The name of the file
     * @var string
     */
    private $_name;
    
    /**
     * The name of the file, including extension
     * @var type 
     */
    private $_base_name;
    
    /**
     * The direcotry part of the absolute path
     * @var string
     */
    private $_dir_name;
    
    /**
     * The extension of the file
     * @var string
     */
    private $_ext;
    
    /**
     * The mime type
     * @var string
     */
    private $_mime;
    
    /**
     * The size, in bytes
     * @var long
     */
    private $_size;
    
    /**
     * Time of the last access, in Unix timestamp format
     * @var string
     */
    private $_last_access;
    
    /**
     * Time of the last modification, in Unix timestamp format
     * @var string
     */
    private $_last_modification;

    
    /**
     * Create a File object
     * @param string $path the name of the config file to load at the creation
     * @return Config
     */
    public function __construct($path)
    {
        if($path === null)
            throw new \InvalidArgumentException('A valid path is required.');
        $this->_path = $path;
        
        // get file info
        $this->_file_info();
    }
    
    /**
     * Rename this file
     * @param string $name the new name of this file
     * @return File 
     */
    public function rename($name)
    {
        $res = rename($this->_path, $this->_dir_name.DS.$name);
        if($res){
            $this->_name = $name;
            return $this;
        }
        return $res;
    }
    
    /**
     * Move this file to other location
     * @param strint $to path of the location in witch move the file
     * @return File 
     */
    public function move($to)
    {
        $res = copy($this->_dir_name, $to);
        if($res){
            unlink($this->_dir_name.DS.$this->_name);
            $this->_file_info($to.DS.$this->_name);
            return $this;
        }
        return $res;
    }
    
    /**
     * Remove this file from filesystem
     * @return bool TRUE on success or FALSE on failure
     */
    public function remove()
    {
        $res = unlink($this->_path);
        return $res;
    }
    
    public function read()
    {
        return $this->__toString();
    }
    
    public function write($data)
    {
        $fp = fopen($this->_path, 'w');
        $n = fprintf($fp, '%s', $data);
        fclose($fp);
        if($n) return $this;
        return false;
    }
    
    public function append($data)
    {
        $fp = fopen($this->_path, 'a');
        $n = fprintf($fp, '%s', $data);
        fclose($fp);
        if($n) return $this;
        return false;
    }
    
    /**
     * Returns the absolute path of this file.
     * @return string
     */
    public function get_path()
    {
        return $this->_path;
    }
    
    /**
     * Returns the name of this file, including extension.
     * @return string
     */
    public function get_base_name()
    {
        return $this->_base_name;
    }
    
    /**
     * Returns the path of this file, excluding basename.
     * @return string
     */
    public function get_dir_name()
    {
        return $this->_dir_name;
    }
    
    /**
     * Returns the name of the file.
     * @return string
     */
    public function get_name()
    {
        return $this->_name;
    }
    
    /**
     * Return the extension
     * @return string
     */
    public function get_ext()
    {
        return $this->_ext;
    }
    
    /**
     * Returns the mime type
     * @return string
     */
    public function get_mime()
    {
        return $this->_mime;
    }
    
    /**
     * Returns the size of the file, in bytes
     * @return long
     */
    public function get_size()
    {
        return $this->_size;
    }
    
    /**
     * Returns the time of the last access to the file, in Unix timestamp format
     * @return string
     */
    public function get_last_access()
    {
        return $this->_last_access;
    }
    
    /**
     * Returns the time of the last modification of the file, in Unix timestamp format
     * @return string
     */
    public function get_last_modification()
    {
        return $this->_last_modification;
    }
    
    /**
     * Returns entire content of the file in a string
     * @return string
     */
    public function __toString()
    {
        return file_get_contents($this->_path);
    }
    
    /**
     * Set the file informations
     */
    private function _file_info()
    {
        $pathinfo = pathinfo($this->_path);
        if(!isset($pathinfo['extension']))
            $pathinfo['extension'] = '';

        $lstat = lstat($this->_path);
        
        $this->_name = $pathinfo['filename'];
        $this->_base_name = $pathinfo['base_name'];
        $this->_dir_name = $pathinfo['dirname'];
        $this->_ext = $pathinfo['extension'];
        $this->_mime = mime_content_type($path);
        $this->_size = $lstat['size'];
        $this->_last_access = $lstat['atime'];
        $this->_last_modification = $lstat['mtime'];
    }
}

