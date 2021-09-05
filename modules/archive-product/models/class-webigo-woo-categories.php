<?php



class Webigo_Woo_Categories
{

    protected $taxonomy = 'product_cat';

    /**
     * Collection of of Category object
     * 
     * @var array Webigo_Woo_Category
     */
    protected $categories;

    public function __construct()
    {
        $this->categories = array();
        $this->load_dependencies();
        // $this->set_query_params();
    }

    protected function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-category.php';
    }

    public function load() : void
    {

        $woo_categories = (object) get_terms( array('taxonomy' => 'product_cat') );

        // TODO: managing errors if not it is hard to debug
        // For future Nicola: the $woo_categories var was an WP_Error object
        if ( ! is_wp_error( $woo_categories ) ) {
            
            foreach ($woo_categories as $woo_category) {

                $category_object = new Webigo_Woo_Category();
    
                $id           = $woo_category->term_id;
                $name         = $woo_category->name;
                $slug         = $woo_category->slug;
                $description  = $woo_category->description;
                $url          = get_term_link($woo_category->term_id, $this->taxonomy);
               
                // get the category image
                $thumbnail_id = get_term_meta($woo_category->term_id, 'thumbnail_id', true);
                $image_url    = wp_get_attachment_url($thumbnail_id);
    
                $category_object->init( $id, $name, $slug, $description, $url, $image_url );
    
                array_push($this->categories, $category_object->get_instance() );
            }
        }

    }

    /**
     * 
     * @return array of Webigo_Woo_Category
     */
    public function get_categories() : array
    {
        return $this->categories;
    }
}
