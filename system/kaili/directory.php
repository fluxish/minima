<?php

namespace Kaili;

/**
 * Kaili Directory Class
 *
 * Scanner for files and directories
 *
 * @package Kaili
 */
class Directory
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
     * Path of directory
     * @var string
     */
    private $_path;

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

    private function _file_info($file)
    {
        $pathinfo = pathinfo($file);
        if(!isset($pathinfo['extension']))
            $pathinfo['extension'] = '';

        $lstat = lstat($file);

        return array(
            'name' => $pathinfo['basename'],
            'path' => $pathinfo['dirname'],
            'ext' => $pathinfo['extension'],
            'mime' => mime_content_type($file),
            'size' => $lstat['size'],
            'last_access' => $lstat['atime'],
            'last_modification' => $lstat['mtime']
        );
    }

    const SCAN_DIRS = 'dirs';
    const SCAN_FILES = 'files';
    const SCAN_ALL = 'all';
    const TYPES_ALL = '.*';
    const SORT_ASC = 0;
    const SORT_DESC = 1;
    const SORT_NONE = 2;
}

