<?php

// https://developer.wordpress.org/reference/functions/wp_enqueue_script/

class Webigo_Scripts_Register
{

    private $action_callback;

    private $scripts_register;

    public function __construct()
    {
        $this->scripts = array();
        $this->action_callback = 'enqueue_scripts';
    }

    /**
     * Encapsulate the info regarding the scripts to load
     *
     * @since    1.0.0
     * @param    string               $module           Refer to the plugin module name
     * @param    string               $src              Source path of CSS file
     * @param    array                $dependencies     Pass the name of the dependencies (generally it is the module assigned to the third party resource)
     * @param    string               $version          The version of scriptsheet
     * @param    bool                 $is_admin         Optional - Is it for the admin or public side of site? (default false)
     * @param    bool                 $in_footer        Optional - The js will load in footer (default true)
     */

    public function add(string $module, string $src, array $dependencies, string $version = '1.0', bool $is_admin = false, bool $in_footer = true)
    {

        $action_name = $is_admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

        $this->scripts_register[] = array(
            $module => array(
                'action_name'     => $action_name,
                'action_callback' => $this->action_callback,
                'module'          => $module,
                'src'             => $src,
                'dependencies'    => $dependencies,
                'version'         => $version,
                'in_footer'       => $in_footer,
            )
        );
    }

    public function get_action_callback()
    {

        return $this->action_callback;
    }


    /**
     * Register the scriptsheets for the module
     *
     * @since    1.0.0
     */
    public function enqueue_script()
    {
        foreach ($this->scripts_register as $module => $script_data) {

            wp_enqueue_script($module, $script_data['src'], $script_data['dependecies'], $script_data['version'], $script_data['in_footer']);    
        }

    }
}
