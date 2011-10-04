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
    function __construct($path)
    {
        if($path === null) 
            throw new \InvalidArgumentException('A valid path is required.');
        $this->_path = $path;
    }
}

