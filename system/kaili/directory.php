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
     * @param string $file the name of the config file to load at the creation
     * @return Config
     */
    public static function factory($path)
    {
        return new static($path);
    }
    
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
        $this->_dir_info();
        
        
        $this->_dir = array();
    }

    public function scan($sort_order = self::SORT_NONE, $hidden = false)
    {
        $res = array();
        $arr = scandir($this->_path, $sort_order);
        foreach($arr as $f) {
            $file = $this->_path.$f;
            if(is_dir($file) && !($f == '.' || $f == '..')) {
                $res[$file] = $this->_dir_info($file);
            }
            else if(is_file($file)) {
                $res[$file] = $this->_file_info($file);
            }
        }
        return $res;
    }

    public function scan_dirs($sort_order = self::SORT_NONE, $hidden = false)
    {
        $res = array();
        $arr = scandir($this->_path, $sort_order);
        foreach($arr as $d) {
            $dir = $this->_path.$d;
            if(is_dir($dir) && !($d == '.' || $d == '..')) {
                $res[$dir] = $this->_dir_info($dir);
            }
        }
        return $res;
    }

    public function scan_files($file_types = self::TYPES_ALL, $sort_order = self::SORT_NONE, $hidden = false)
    {
        $res = array();
        $arr = scandir($this->_path, $sort_order);
        foreach($arr as $f) {
            $f = $this->_path.$f;
            if(is_file($f)) {
                $res[$f] = $this->_file_info($f);
            }
        }
        return $res;
    }

    private function _dir_info()
    {
        $pathinfo = pathinfo($this->_path);
        $lstat = lstat($this->_path);

        $this->_name = $pathinfo['filename'];
        $this->_base_name = $pathinfo['basename'];
        $this->_dir_name = $pathinfo['dirname'];
        $this->_extension = null;
        $this->_mime = mime_content_type($this->_path);
        $this->_size = $lstat['size'];
        $this->_last_access = $lstat['atime'];
        $this->_last_modification = $lstat['mtime'];
    }
    
    const SCAN_DIRS = 'dirs';
    const SCAN_FILES = 'files';
    const SCAN_ALL = 'all';
    const TYPES_ALL = '.*';
    const SORT_ASC = 0;
    const SORT_DESC = 1;
    const SORT_NONE = 2;

}

