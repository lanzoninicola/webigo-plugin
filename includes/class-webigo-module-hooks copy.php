<?php



class Webigo_Module_Hooks {

    
    /**
     * Collection of Module hooks.
     * This array is iterated inside the Webigo class (class-webigo.php) 
     * and passed to load() method of Loader class
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
     * @param array $hook_data
     * @return void|false if all parameters are not passed
     * 
     * $hook_data = array( 
     *                  hook     => (string) hook name,
     *                  callback => (string|array) callback function to call
     *              )                
     * 
     */
    public function register(array $hook_data) {

        if( ! isset( $hook_data['hook'] ) ) {
            return false;
        }

        if( ! isset( $hook_data['callback'] ) ) {
            return false;
        }

        $hook_name = $hook_data['hook'];
        $priority = isset( $hook_data['priority'] ) ? $hook_data['priority'] : 10 ;

        if ( is_array( $hook_data['callback'] ) && !empty( $hook_data['callback'] ) ) {
            $callback = array();
            
            if ( is_object( $hook_data['callback'][0] ) ) {
                $callback[0] = $hook_data['callback'][0];
            } else {
                $callback[0] = false;
            }
            
            if ( is_string( $hook_data['callback'][1] ) ) {
                $callback[1] = $hook_data['callback'][1];
            } else {
                $callback[1] = false;
            }
        }
        
        $this->hooks[$hook_name] = $callback;

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