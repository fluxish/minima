<?php  

namespace Kaili;

/**
 * Kaili ACL Access Control List Class
 *
 * Class to manage an access control list system
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */

class Acl
{
    /**
     * The access control list, as multidimensional array 
     * $_acl = array(:role => array(:controller => array(:action => true|false)))
     * @var array
     */
    private $_acl;
    
    
    public function __construct()
    {
        $this->_acl = array();
    }
    
    /**
     * Add a role to ACL
     * @param string $role name of the role
     */
    public function add_role($role)
    {
        $this->_acl[$role] = array();
    }
    
    /**
     * Remove a role from ACL
     * @param string $role name of the role to remove
     */
    public function remove_role($role)
    {
        unset($this->_acl[$role]);
    }
    
    /**
     * Allows a particular action of a controller for a role. If only controller 
     * and role are provided, then allows all actions of the controller. If only role 
     * is provided, then allows all action of all controller for the role.
     * @param string $role name of the role
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function allow($role, $controller = null, $action = null)
    {
        if($controller === null){
            return $this->_add(array('role'=>$role));
        }
        else if($action === null){
            return $this->_add(array('role'=>$role, 'controller'=>$controller));
        }
        else{
            return $this->_add(array('role'=>$role, 'controller'=>$controller, 
                'action'=>$action));
        }
    } 
    
    /**
     * Denies a particular action of a controller for a role. If only controller 
     * and role are provided, then denies all actions of the controller. If only role 
     * is provided, then denies all action of all controller for the role.
     * @param string $role name of the role
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function deny($role, $controller = null, $action = null)
    {
        if($controller === null){
            return $this->_remove(array('role'=>$role));
        }
        else if($action === null){
            return $this->_remove(array('role'=>$role, 'controller'=>$controller));
        }
        else{
            return $this->_remove(array('role'=>$role, 'controller'=>$controller, 
                'action'=>$action));
        }
    }
    
    /**
     * Returns if an action of a controller for a role is allowed or not. If only 
     * controller and role are provided, then returns if all actions of the controller 
     * are allowed or not. If only role is provided, then returns if all action 
     * of all controller for the role are allowed or not.
     * @param string $role name of the role
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function is_allowed($role, $controller = null, $action = null)
    {
        if($controller === null){
            return $this->_get(array('role'=>$role));
        }
        else if($action === null){
            return $this->_get(array('role'=>$role, 'controller'=>$controller));
        }
        else{
            return $this->_get(array('role'=>$role, 'controller'=>$controller, 
                'action'=>$action));
        }
    }
    
    
    private function _get($data)
    {
        if(isset($this->_acl[$data['role']][$data['controller']][$data['action']])){
            return $this->_acl[$data['role']][$data['controller']][$data['action']];
        }
        else if(isset($this->_acl[$data['role']][$data['controller']][0])){
            return $this->_acl[$data['role']][$data['controller']][0];
        }
        else if(isset($this->_acl[$data['role']][0])){
            return $this->_acl[$data['role']][0];
        }
        return false;
    }
    
    private function _add($data)
    {
        if(isset($data['action'])){
            $this->_acl[$data['role']][$data['controller']][$data['action']] = true;
            return true;
        }
        else if(isset($data['controller'])){
            $this->_acl[$data['role']][$data['controller']][0] = true;
            return true;
        }
        else if(isset($data['role'])){
            $this->_acl[$data['role']][0] = true;
            return true;
        }
        return false;
    }
    
    private function _remove($data)
    {
        if(isset($data['action'])){
            $this->_acl[$data['role']][$data['controller']][$data['action']] = false;
            return true;
        }
        else if(isset($data['controller'])){
            $this->_acl[$data['role']][$data['controller']][0] = false;
            return true;
        }
        else if(isset($data['role'])){
            $this->_acl[$data['role']][0] = false;
            return true;
        }
        return false;
    }
}

