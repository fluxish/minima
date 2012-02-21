<?php

namespace Kaili;

/**
 * Kaili Xml Data Class
 *
 * Class to manage an xml file as data storage or as db table
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */
 
class Xmldata
{
    /**
     * @var SimpleXMLElement
     */
    private $_root;
    
    /**
     * @var string
     */
    private $_file_path;
    
    /**
     * @var string
     */
    private $_element_name;
    
    /**
     * Create new XmlData object
     */
    public function __construct()
    {
    }
    
    public function __destruct()
    {
        unset($this->_root);
    }
    
    /**
     * Load (or create) an XMLData file
     * @param string $file file path 
     * @param string $root_name name of the root element
     */
    public function load($file, $root_name = 'root')
    {
        $this->_file_path = $file;
        if(file_exists($file)){
            $this->_root = simplexml_load_file($file);
        }
        else{
            $this->_root = simplexml_load_string("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$root_name></$root_name>");
        }
    }
    
    /**
     * Add a new element to XMLData
     * @param mixed $elem string|SimpleXMLElement  
     * @param array $data data 
     * @return mixed id
     */
    public function add($elem, $data)
    {
        if(is_string($elem)){
            $elem = $this->_root->addChild($elem);
        }
        $id = $this->_uniqueid();
        $elem->addAttribute('id', $id);
        
        foreach($data as $k=>$v){
            //if key is an attribute
            if(substr($k, 0, 1) == '@'){
                if(is_array($v)) throw new Exception('The value of an attribute can\'t be an array');
                $elem->addAttribute(ltrim($k, '@'), $v);
            }
            else{
                if(is_array($v)){
                    if(is_assoc($v)){
                        $el = $elem->addChild($k);
                        $this->add($el, $v);
                    }
                    else{
                        for($i=0; $i<count($v); $i++){
                            $el = $elem->addChild($k);
                            $this->add($el, $v[$i]);
                        }
                    }
                }
                else{
                    $elem->addChild($k, $v);
                }
            }
        }
        $this->_save();
        return $id;
    }
    
    /**
     * Find and return the element data
     * @param string $xpath an xpath string
     * @return array 
     */
    public function get($xpath)
    {
        return $this->_root->xpath($xpath);
    }
    
    /**
     * Find and return an element data by id 
     * @param string $id
     */
    public function get_by_id($id)
    {
        return $this->get("//*[@id='$id']");
    }
    
    /**
     * Set new data for an element
     * @param string $xpath
     * @param string $data
     */
    public function set($xpath, $data)
    {
        $elems = $this->get($xpath);
        foreach($elems as $elem){
            foreach($data as $k=>$v){
                if(substr($k, 0, 1) == '@'){
                    if(is_array($v)) throw new Exception('The value of an attribute can\'t be an array');
                    $attr_name = ltrim($k, '@');
                    $elem->attributes()->$attr_name = $v;
                }
                else{
                    $elem->$k = $v;
                }
            }
        }
        $this->_save();
    }
    
    /**
     * Set new data for an element founded by id
     * @param string $id
     * @param string $data
     */
    public function set_by_id($id, $data)
    {
        $this->set("//*[@id='$id']", $data);
    }
    
    /**
     * Remove an element
     * @param string $xpath
     */
    public function remove($xpath)
    {
        $elems = $this->_root->xpath($xpath);
        for($i=0; $i < count($elems); $i++){
            $dom=dom_import_simplexml($elems[$i]);
            $dom->parentNode->removeChild($dom);
        }
        $this->_save();
    }
    
    /**
     * Remove an element founded by id
     * @param string $xpath
     */
    public function remove_by_id($id)
    {
        $this->remove("//*[@id='$id']");
    }
    
    /**
     * Save current XMLData to file
     */
    private function _save()
    {
        $dom = new DOMDocument('1.0');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($this->_root->asXML());
        return $dom->save($this->_file_path) > 0;
    }
    
    /**
     * Generate a unique ID
     * @return string
     */
    private function _uniqueid()
    {
        return md5(uniqid(mt_rand(0, mt_getrandmax()), true));
    }
 }

