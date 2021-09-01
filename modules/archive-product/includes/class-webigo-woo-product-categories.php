<?php



class Webigo_Woo_Product_Categories
{


    protected $taxonomy = 'product_cat';

    protected $query_params;

    /**
     * Collection of of Category object
     * 
     * @var array Webigo_Woo_Hzbi_Product_Category
     */
    protected $categories;

    public function __construct()
    {
        $this->categories = array();
        $this->load_dependencies();
        $this->set_query_params();
        $this->load_categories();
    }

    protected function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product-category.php';
    }

    protected function set_query_params() {
        
        $orderby      = 'name';
        // $show_count   = 0;      // 1 for yes, 0 for no
        // $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 0;      // 1 for yes, 0 for no  
        // $title        = '';
        $empty        = 0;

        
        $this->query_params = array(
            'taxonomy'     => $this->taxonomy,
            'orderby'      => $orderby,
            // 'show_count'   => $show_count,
            // 'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            //  'title_li'     => $title,
            'hide_empty'   => $empty
        );

    }


    protected function load_categories()
    {

        $woo_categories = get_terms( ['taxonomy' => 'product_cat'] );

        foreach ($woo_categories as $woo_category) {

            $category_object = new Webigo_Woo_Product_Category();

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

    public function get_categories() {

        return $this->categories;

    }
}
