<?php
/**
 * Inspiration from https://stackoverflow.com/questions/32150835/woocommerce-overriding-the-template-through-a-plugin
 */
/**
 * Override default WooCommerce templates and template parts from plugin.
 * 
 * E.g.
 * Override template 'woocommerce/loop/result-count.php' with 'my-plugin/woocommerce/loop/result-count.php'.
 * Override template part 'woocommerce/content-product.php' with 'my-plugin/woocommerce/content-product.php'.
 *
 * Note: We used folder name 'woocommerce' in plugin to override all woocommerce templates and template parts.
 * You can change it as per your requirement.
 */
 
 /**
  * TO OVERRIDE THE TEMPLATE THE MODULE NEED TO ADD THE BELOW FILTERS
  * You have to use the first OR the second filter depending on which filter was used
  * to load the template (https://stackoverflow.com/questions/51805358/custom-templates-in-woocommerce-3)
  *
  * Override Template Part's.
  * add_filter( 'wc_get_template_part', array( CLASS, METHOD_THAT_EXECUTE_THE_override_woocommerce_template_part_FUNCTION), 10, 3 );
  * Override Template's.
  * add_filter( 'woocommerce_locate_template', array( $this->woo_custom_template, METHOD_THAT_EXECUTE_THE_override_woocommerce_template_FUNCTION ), 10, 3 );
   */

class Webigo_Woo_Custom_Template_Handler{

    /**
     * Determines if a custom template must be loaded
     * 
     * @var bool
     */
    private $enabled_custom_template;

    /**
     * The root directory name of custom template
     * Default: "woocommerce" with the result path of
     * /wp-content/plugins/my-plugin/woocommerce/
     */
    private $template_root_dirname;

    /**
     * Full path of Custom Templates
     * 
     * @var string
     */
    private $template_directory_path;

    public function __construct()
    {
        $this->enabled_custom_template = false;
        $this->template_root_dirname = 'woocommerce';
        $this->template_directory_path = untrailingslashit( WEBIGO_PLUGIN_PATH ) . '/' . $this->template_root_dirname . '/templates/';
    }

    
    /**
     * Set the flag to true, so the custom template will be loaded
     */
    
    public function enable_custom_template()
    {
        $this->enabled_custom_template = true;
    }

    /**
     * Return the value of "enabled_custom_template" prop
     */
    public function is_custom_template_enabled()
    {
        return $this->enabled_custom_template;
    }

    /**
     * Template Part's
     *
     * @param  string $template Default template file path.
     * @param  string $slug     Template file slug.
     * @param  string $name     Template file name.
     * @return string           Return the template part from plugin.
     */
    public function override_woocommerce_template_part( $template, $slug, $name ) {

        if ( $this->is_custom_template_enabled() ) {
            if ( $name ) {
                $path = $this->template_directory_path . "/{$slug}-{$name}.php";
            } else {
                $path = $this->template_directory_path . "/{$slug}.php";
            }
            return file_exists( $path ) ? $path : $template;
        }

        return $template;
    }

    /**
     * Template File
     *
     * @param  string $template      Default template file  path.
     * @param  string $template_name Template file name.
     * @param  string $template_path Template file directory file path.
     * @return string                Return the template file from plugin.
     */
    function override_woocommerce_template( $template, $template_name, $template_path ) {

        if ( $this->is_custom_template_enabled() ) {
            $path = $this->template_directory_path . $template_name;
            return file_exists( $path ) ? $path : $template;
        }
        
        return $template;
    }

}





