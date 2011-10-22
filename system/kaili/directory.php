<?php

namespace Kaili;

class DirectoryException extends Exception{};

/**
 * Kaili Directory Class
 *
 * Scanner for files and directories
 *
 * @package Kaili
 */
class Directory extends File
{

    /**
     * Create a Directory object
     * @param string $path the path of the directory
     * @return File
     */
    public static function factory($path)
    {
        if(!is_dir($path))
            throw new DirectoryException('Directory "'.$path.'" not found.');
        return new static($path);
    }
    
    /**
     * Create a new file
     * @param string $path the path of the new file
     * @return File
     */
    public static function create($path)
    {
        if(is_dir($path))
            throw new DirectoryException('Directory "'.$path.'" aleredy exists.');
        mkdir($path);
        
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
     * @var string 
     */
    private $_base_name;
    
    /**
     * The directory part of the absolute path
     * @var string
     */
    private $_dir_name;
    
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
     * Array of content of the directory
     * @var array
     */
    private $_dir;

    /**
     * Create a Directory object
     * @param string $file the name of the config file to load at the creation
     * @return Config
     */
    public function __construct($path)
    {
        if($path === null)
            throw new \InvalidArgumentException('A valid path is required.');
        $this->_path = $path;
        
        // get directory info
        $this->_info();
        
        
        //$this->_dir = array();
    }
    
    /**
     * Rename this directory
     * @param string $name the new name of this directory
     * @return Directory 
     */
    public function rename($name)
    {
        $res = rename($this->_path, $this->_dir_name.DS.$name);
        if($res){
            $this->_path = $this->_dir_name.DS.$name;
            $this->_info();
            return $this;
        }
        return $res;
    }
    
    /**
     * Move this directory to other location
     * @param string $to path of the location in witch move the directory
     * @return Directory
     */
    public function move($to, $overwrite = true)
    {
        $to_path = $to.DS.$this->_base_name;
        
        // check if directory exists
        if(!is_dir($to))
            throw new \InvalidArgumentException('Directory "'.$to.'" not found.');
        
        // if overwriting is disabled, check if file exists
        if(!$overwrite && is_dir($to_path))
            throw new DirectoryException('Directory "'.$to_path.'" already exists.');
        
        $res = rename($this->_path, $to_path);
        // move the file
        if($res){
            $this->_path = $to_path;
            $this->_info();
            return $this;
        }
        return $res;
    }
    
    /**
     * Remove this directory from filesystem
     * @param bool $recursive set if has to remove files and directories inside it
     * @return bool TRUE on success or FALSE on failure
     */
    public function remove()
    {
        $res = rmdir($this->_path);
        return $res;
    }
    
    /**
     * Scan and returns files and directories inside this directory
     * @param type $sort_order no order (SORT_NONE, default), ascending (SORT_ASC), descending (SORT_DESC)
     * @param type $mode all (SCAN_ALL, default), directories (SCAN_DIRS), files (SCAN_FILES)
     * @param type $hidden set if include hidden files/directories in the results
     * @return type array
     */
    public function scan($sort_order = self::SORT_NONE, $mode = self::SCAN_ALL, $hidden = false)
    {
        $res = array();
        $arr = scandir($this->_path, $sort_order);
        foreach($arr as $f) {
            $f = $this->_path.DS.$f;
            if(is_file($f) and $mode != self::SCAN_DIRS)
                $res[$f] = File::factory($f);
            else if(is_dir($f) and $mode != self::SCAN_FILES)
                $res[$f] = Directory::factory($f);
        }
        return $res;
    }
    
    /**
     * Returns all directories inside this directory
     * @param type $sort_order no order (SORT_NONE, default), ascending (SORT_ASC), descending (SORT_DESC)
     * @param type $hidden set if include hidden directories in the results
     * @return type array
     */
    public function scan_directories($sort_order = self::SORT_NONE, $hidden = false)
    {
        return $this->scan($sort_order, self::SCAN_DIRS, $hidden);
    }
    
    /**
     * Returns all files inside this directory
     * @param type $sort_order no order (SORT_NONE, default), ascending (SORT_ASC), descending (SORT_DESC)
     * @param type $hidden set if include hidden files in the results
     * @return type array
     */
    public function scan_files($sort_order = self::SORT_NONE, $hidden = false)
    {
        return $this->scan($sort_order, self::SCAN_FILES, $hidden);
    }
    
    /**
     * Search files or directoris inside this directory, by pattern
     * @param string the pattern to search for
     * @param type $sort_order no order (SORT_NONE, default), ascending (SORT_ASC), descending (SORT_DESC)
     * @param type $mode all (SCAN_ALL, default), directories (SCAN_DIRS), files (SCAN_FILES)
     * @param type $hidden set if include hidden files/directories in the results
     * @return type array
     */
    public function search($pattern = '.*', $sort_order = self::SORT_NONE, $mode = self::SCAN_ALL, $hidden = false)
    {
        $res = array();
        $arr = scandir($this->_path, $sort_order);
        foreach($arr as $f) {
            if(preg_match($pattern, $f) != 0){
                var_dump($f);
                $f = $this->_path.DS.$f;
                if(is_file($f) and $mode != self::SCAN_DIRS)
                    $res[$f] = File::factory($f);
                else if(is_dir($f) and $mode != self::SCAN_FILES)
                    $res[$f] = Directory::factory($f);
            }
        }
        return $res;
    }
    
    /**
     * Search files or directoris inside this directory, by name
     * @param string the name to search for
     * @param type $sort_order no order (SORT_NONE, default), ascending (SORT_ASC), descending (SORT_DESC)
     * @param type $mode all (SCAN_ALL, default), directories (SCAN_DIRS), files (SCAN_FILES)
     * @param type $hidden set if include hidden files/directories in the results
     * @return type array
     */
    public function search_by_name($name = '.*', $sort_order = self::SORT_NONE, $mode = self::SCAN_ALL, $hidden = false)
    {
        $pattern = '/('.preg_quote($name).')/';
        return $this->search($pattern, $sort_order, $mode, $hidden);
    }
    
    /**
     * Returns the absolute path of this directory.
     * @return string
     */
    public function get_path()
    {
        return $this->_path;
    }
    
    /**
     * Returns the name of this directory
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
     * Returns the size of the directory, in bytes
     * @return long
     */
    public function get_size()
    {
        return $this->_size;
    }
    
    /**
     * Returns the time of the last access to the directory, in Unix timestamp format
     * @return string
     */
    public function get_last_access()
    {
        return $this->_last_access;
    }
    
    /**
     * Returns the time of the last modification of the directory, in Unix timestamp format
     * @return string
     */
    public function get_last_modification()
    {
        return $this->_last_modification;
    }
    
    /**
     * Returns entire content of the directory in a string
     * @return string
     */
    public function __toString()
    {
        return file_get_contents($this->_path);
    }
    
    /**
     * Set the directory infos 
     */
    private function _info()
    {
        $pathinfo = pathinfo($this->_path);
        $lstat = lstat($this->_path);

        $this->_name = $pathinfo['filename'];
        $this->_base_name = $pathinfo['basename'];
        $this->_dir_name = $pathinfo['dirname'];
        $this->_size = $lstat['size'];
        $this->_last_access = $lstat['atime'];
        $this->_last_modification = $lstat['mtime'];
    }
    
    /**
     * Directory scan mode
     * @var string 
     */
    const SCAN_DIRS = 'dirs';
    
    /**
     * File scan mode
     * @var string 
     */
    const SCAN_FILES = 'files';
    
    /**
     * All elements scan mode
     * @var string 
     */
    const SCAN_ALL = 'all';
    
    /**
     * Ascending sort
     * @var int
     */
    const SORT_ASC = 0;
    
    /**
     * Descending sort
     * @var int
     */
    const SORT_DESC = 1;
    
    /**
     * No sort
     * @var int
     */
    const SORT_NONE = 2;
}

