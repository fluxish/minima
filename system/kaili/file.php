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
    
    private $_path;
    
    private $_name;
    
    /**
     * Path of directory
     * @var string
     */
    private $_dir_name;
    private $_ext;
    private $_mime;
    private $_size;
    private $_last_access;
    private $_last_modification;

    
    /**
     * Create a File object
     * @param string $file the name of the config file to load at the creation
     * @return Config
     */
    public function __construct($file)
    {
        if($file === null)
            throw new \InvalidArgumentException('A valid file is required.');
        
        // get file info
        $this->_file_info($file);
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
    
    public function remove()
    {
        $res = unlink($this->_path);
        return $res;
    }
    
    public function get_path()
    {
        return $this->_path;
    }
    
    public function get_dir_name()
    {
        return $this->_dir_name;
    }

    public function get_name()
    {
        return $this->_name;
    }

    public function get_ext()
    {
        return $this->_ext;
    }

    public function get_mime()
    {
        return $this->_mime;
    }

    public function get_size()
    {
        return $this->_size;
    }

    public function get_last_access()
    {
        return $this->_last_access;
    }

    public function get_last_modification()
    {
        return $this->_last_modification;
    }
    
    private function _file_info($file)
    {
        $pathinfo = pathinfo($file);
        if(!isset($pathinfo['extension']))
            $pathinfo['extension'] = '';

        $lstat = lstat($file);
        
        $this->_path = $file;
        $this->_name = $pathinfo['basename'];
        $this->_dir_name = $pathinfo['dirname'];
        $this->_ext = $pathinfo['extension'];
        $this->_mime = mime_content_type($file);
        $this->_size = $lstat['size'];
        $this->_last_access = $lstat['atime'];
        $this->_last_modification = $lstat['mtime'];
    }
}

