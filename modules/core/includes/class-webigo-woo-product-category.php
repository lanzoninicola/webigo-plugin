<?php



class Webigo_Woo_Product_Category {


    private $taxonomy;

    private $id;

    private $name;

    private $slug;

    private $description;

    /**
     * The category permalink
     * 
     * @var string
     */
    private $link;


    /**
     * The URL of category image
     * 
     * @var string
     */
    private $image_url;

        /**
     * The ID of Parent Category
     * 
     * @var int
     */
    private $parent = 0;


    /**
     * The array of Children Categories
     * 
     * @var Webigo_Woo_Product_Category[]
     */
    private $children = array();


    /**
     * Tells the level of nesting in the category hierarchy
     */
    private $hierarchy_level = 0;


    /**
     * @param object|string  WP_Term|term_id
     * @param string $taxonomy  Optional - Default is "product category" taxonomy
     */
    public function __construct( $term, string $taxonomy = 'product_cat' )
    {
        $this->taxonomy = $taxonomy;

        $wp_term = null;

        if ( gettype( $term ) === 'object' ) {
            $wp_term = $term;
        }

        if ( gettype( $term ) === 'string' ) {
            $wp_term = get_term_by( 'id', $term, $this->taxonomy );
        }

        $this->set_id( $wp_term );
        $this->set_name( $wp_term );
        $this->set_slug( $wp_term );
        $this->set_description( $wp_term );
        $this->set_link( $wp_term );
        $this->set_image_url();
        $this->set_parent( $wp_term );

        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-woo-product.php';
    }

    /** Setter Methods */

    public function set_id( object $term )
    {
        $this->id = $term->term_id;
    }

    public function set_name( object $term )
    {
        $this->name =  $term->name;
    }

    public function set_slug( object $term )
    {
        $this->slug =  $term->slug;
    }

    public function set_description( object $term )
    {
        $this->description =  $term->description;
    }

    public function set_link( object $term )
    {
        $this->link =  get_term_link( $term->slug, $this->taxonomy );
    }

    public function set_image_url( )
    {
        $thumbnail_id = get_term_meta( $this->id(), 'thumbnail_id', true );
        $this->image_url =  wp_get_attachment_url( $thumbnail_id );
    }

    public function set_parent( object $term )
    {
        $this->parent = $term->parent;
    }



    /** Getter Methods */

    public function id() : int
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function slug() : string
    {
        return $this->slug;
    }

    public function description() : string
    {
        return $this->description;
    }

    public function link( ) : string
    {
        return $this->link;
    }

    public function image_url() : string
    {
        return $this->image_url;
    }

    /**
     * Return the ID of category parent
     * @return int
     */
    public function parent() : int
    {
        return $this->parent;
    }

    
    /**
     * @param Webigo_Woo_Product_Category[]
     * 
     * */    
    public function set_children( array $children )
    {
        $this->children = $children;
    }

    /**
     * Return the list of children
     * 
     * @return Webigo_Woo_Product_Category[]
     */
    public function children() : array
    {
        return $this->children;
    }

     /**
     *  @param int hierarchy level 
     */    
    public function set_hierarchy_level( int $level )
    {
        $this->hierarchy_level = $level;
    }

    /**
     * Return the number of level in the categoriy hierarchy
     * 
     * @return int
     */
    public function hierarchy_level() : int
    {
        return $this->hierarchy_level;
    }


    /**
     * Get the list of product on behalf of the category.
     * 
     * @param  string array|array_indexed - Determines the output array structure
     * @return array of Webigo_Woo_Product
     */
    public function get_products ( string $output = 'array' ) : array
    {

        $products = array();

        $query = new WC_Product_Query( array(
            'status'   => 'publish',
            'category' => array(  $this->slug ),
            'return'   => 'objects',
        ) );


        if ( $output == 'array' ) {
            foreach ( $query->get_products() as $product ) {
                array_push( $products, new Webigo_Woo_Product( $product ) );
            }
        }

        if ( $output == 'array_indexed' ) {
            foreach ( $query->get_products() as $product ) {
                $products[$product->get_id()] = new Webigo_Woo_Product( $product );
            }
        }

        return $products;
    }

    /**
     * Returns the number of products in the category
     * 
     * @return int
     */
    public function count_products() {
        $wc_products_category = $this->get_products();

        return count( $wc_products_category );
    }

    /**
     * Check if the category is empty or not
     * 
     * @return bool
     */
    public function is_empty() : bool
    {
        $wc_products_category = $this->get_products();

        return count( $wc_products_category ) === 0 ? true : false;
    }


}

