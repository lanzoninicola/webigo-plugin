<?php


require_once WEBIGO_PLUGIN_PATH . 'includes/interface-webigo-hooks.php';

class Webigo_Module_Hooks implements IWebigo_Hooks {

    
    /**
     * Collection of Module hooks.
     * This array is iterated inside the Webigo class (class-webigo.php) 
     * and passed to load() method of Loader class
     * 
     * $hooks = {
     *  0 => {
     *         'name':     (string) hook_name
     *         'callback': (array)  array( object , method )        
     *         'priority': (string) hook_priority
     *    }
     *  1 => {
     *         'name':     (string) hook_name
     *         'callback': (array)  array( object , method )        
     *         'priority': (string) hook_priority
     *    }
     * }
     *  
     * @var array
     */
    private $hooks;

    public function __construct() {

        $this->hooks = array();
    }


    /**
     * Add to the hooks collection (array) the registered hook.
     * This function can be called multiple times.
     * 
     * @param array $user_hook_data
     * @return void|false if all parameters are not passed
     * 
     * $user_hook_data = array( 
     *                  'hook'     => (string) hook name,
     *                  'callback' => (string|array) callback function to call
     *                  'priority' => (string) hook priority
     *              )                
     * 
     */
    public function register( array $user_hook_data ) {

        if( ! isset( $user_hook_data['hook'] ) ) {
            return false;
        }

        if( ! isset( $user_hook_data['callback'] ) ) {
            return false;
        }

        $hook_name     = $user_hook_data['hook'];
        $priority      = isset( $user_hook_data['priority'] ) ? $user_hook_data['priority'] : 10 ;
        $accepted_args = isset( $user_hook_data['accepted_args'] ) ? $user_hook_data['accepted_args'] : 1 ;

        if ( is_array( $user_hook_data['callback'] ) && !empty( $user_hook_data['callback'] ) ) {
            $callback = array();
            
            if ( is_object( $user_hook_data['callback'][0] ) ) {
                $callback[0] = $user_hook_data['callback'][0];
            } else {
                $callback[0] = false;
            }
            
            if ( is_string( $user_hook_data['callback'][1] ) ) {
                $callback[1] = $user_hook_data['callback'][1];
            } else {
                $callback[1] = false;
            }
        }

        $hook_data = array(
            'name'          => $hook_name,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        array_push( $this->hooks, $hook_data );

    }

    /**
     * Returns the collection of Module hooks
     * 
     * @return array $this->hooks
     */
    public function hooks() {

        return $this->hooks;

    }


}