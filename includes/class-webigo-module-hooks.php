<?php



class Webigo_Module_Hooks {

    
    /**
     * The name of module who instanciate this class, passed by 
     * the register_public_style or register_admin_style methods
     * 
     * @var array
     */
    private $hooks;

    public function __construct() {

        $this->hooks = array();
    }


    public function register($hook_data) {

        if( ! $hook_data['hook'] ) {
            return false;
        }

        $hook_name = $hook_data['hook'];
        $hook_callback = $hook_data['callback'];

        $this->hooks[$hook_name] = $hook_callback;
        
    }

    public function hooks() {

        return $this->hooks;

    }


}