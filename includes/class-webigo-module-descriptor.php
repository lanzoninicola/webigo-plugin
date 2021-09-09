<?php


class Webigo_Module_Descriptor {

    private $module_root_path;

    private $name;

    private $class_name;

    private $folder_name;

    private $bootstrap_class_file;

    public function __construct(string $name, string $class_name, string $folder_name, string $bootstrap_class_file) {
    
        $this->module_root_path = plugin_dir_path(__DIR__) . 'modules/';
        
        $this->name = $name;
        $this->class_name = $class_name;
        $this->folder_name = $folder_name;
        $this->bootstrap_class_file = $bootstrap_class_file;
    }

   /**
     *  Return the name of module
     * 
     *  @return  string 
     * 
     */
    public function name() {
        
        return $this->name;
    }


    /**
     *  Return the name of module class 
     * 
     *  @return  string 
     * 
     */
    public function class_name() {

        return $this->class_name;
    }

    /**
     *  Return the name of module class file 
     * 
     *  @return  string 
     * 
     */
    public function bootstrap_class_file() {
        
        return $this->module_root_path. $this->folder_name . '/' . $this->bootstrap_class_file;
    }

}