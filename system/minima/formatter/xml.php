<?php  

namespace Minima\Formatter;

/**
 * Minima Formatter_Xml Class
 *
 * This class generate an output in xml format
 *
 * @package	Minima
 * @subpackage Formatter
 */
 
class Xml
{
    
    public function __construct()
    {
        // load words inflector helper
         Loader::get_instance()->helper('inflector');
    }

    public function format($data)
    {
        //Set action as root name
        if($data instanceof SimpleXMLElement){
            $xml = $data;
        }
        else{
            $root_name = Request::current()->get('action');
            $xml = simplexml_load_string("<?xml version='1.0'?><$root_name></$root_name>");
            $this->_scan_obj($xml, $data);
        }
        
        
        $dom = new DOMDocument('1.0');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml->asXML());
        return $dom->saveXML();
    }
    
    private function _scan_obj($xml, $data)
    {
        if(is_object($data)) $data = get_object_vars($data);
        foreach($data as $k=>$v){
            if(is_numeric($k)){
                $k = '_'.$k;
            }
            if(is_object($v) || is_array($v)){
                $el = $xml->addChild($k);
                $this->_scan_obj($el, $v);
            }
            else{
                $xml->addChild($k, $v);
            }
        }
    } 
}

