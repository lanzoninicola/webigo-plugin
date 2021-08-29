<?php

// https://developer.wordpress.org/reference/functions/wp_enqueue_style/

class Webigo_Styles_Register
{

    private $action_callback;

    private $styles_register;

    public function __construct()
    {
        $this->styles = array();
        $this->action_callback = 'enqueue_styles';
    }

    /**
     * Encapsulate the info regarding the stylesheet to load
     *
     * @since    1.0.0
     * @param    string               $module           Refer to the plugin module name
     * @param    string               $src              Source path of CSS file
     * @param    array                $dependencies     Pass the name of the dependencies (generally it is the module assigned to the third party resource)
     * @param    string               $version          The version of stylesheet
     * @param    bool                 $is_admin         Optional - Is it for the admin or public side of site? (default false)
     */

    public function add(string $module, string $src, array $dependencies, string $version = '1.0', bool $is_admin = false)
    {

        $action_name = $is_admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

        $this->styles_register[] = array(
            $module => array(
                'action_name'     => $action_name,
                'action_callback' => $this->action_callback,
                'module'          => $module,
                'src'             => $src,
                'dependencies'    => $dependencies,
                'version'         => $version,
            )
        );
    }

    public function get_action_callback()
    {

        return $this->action_callback;
    }

    
    /**
     * Register the stylesheets for the module
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        

        foreach ($this->styles as $module => $stylesheet_data) {

            wp_enqueue_style($module, $stylesheet_data['src'], $stylesheet_data['dependecies'], $stylesheet_data['version'], 'all');    
        }
        
    }


}



class Webigo_Module_Style {




    public function add(string $module, string $src, array $dependencies, string $version = '1.0', bool $is_admin = false)
    {

        $action_name = $is_admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

        $this->styles_register[] = array(
            $module => array(
                'action_name'     => $action_name,
                'action_callback' => $this->action_callback,
                'module'          => $module,
                'src'             => $src,
                'dependencies'    => $dependencies,
                'version'         => $version,
            )
        );
    }



    public function enqueue_style() {

        wp_enqueue_style($module, $stylesheet_data['src'], $stylesheet_data['dependecies'], $stylesheet_data['version'], 'all');    
    }


}
