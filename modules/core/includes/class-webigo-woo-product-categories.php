<?php



class Webigo_Woo_Product_Categories {

    private $taxonomy = 'product_cat';

    private $orderby = 'name';

    private $query_params = array();

    /**
     * Array of product categories
     * 
     * Index of each category array is the index of the category
     */
    protected $categories_indexed = array();

    /**
     * Flat Array of product categories
     * 
     */
    protected $categories = array();

    /**
     * Flat Array of product categories objects
     * 
     *  @var Webigo_Woo_Product_Category[]
     * 
     */
    protected $categories_objects = array();

    
    /**
     * Array of product categories
     * 
     * Index of each category array is the index of the category
     * 
     * @var Webigo_Woo_Product_Category[]
     */
    protected $categories_objects_indexed = array();


    public function __construct( )
    {
        $this->load_dependencies();
        $this->load_query_params();
        $this->load_categories();
    }

    protected function load_dependencies()
    {

        require_once WEBIGO_PLUGIN_PATH . 'modules/core/includes/class-webigo-woo-product-category.php';
    }

    private function load_query_params()
    {
        $this->query_params = array(
            'taxonomy'     => $this->taxonomy,
            'orderby'      => $this->orderby,
            'show_count'   => false,
            'pad_counts'   => false,
            'hierarchical' => true,
            'title_li'     => '',
            'hide_empty'   => false
        );
    }

     
    private function load_categories( ) : void
    {

        $wc_categories = get_categories( $this->query_params );

        foreach ( $wc_categories as $wc_category ) {

            $wbg_category = new Webigo_Woo_Product_Category( $wc_category );

          

            // if ( $wbg_category->is_empty() === false ) {
            
                $category = array(
                    'id'          => $wbg_category->id(),
                    'name'        => $wbg_category->name(),
                    'description' => $wbg_category->description(),
                    'link'        => $wbg_category->link(),
                    'image_url'   => $wbg_category->image_url(),
                    'empty'       => $wbg_category->is_empty(),
                    'parent'      => $wbg_category->parent()
                );
               
                $this->categories_indexed[$category['id']] = $category ;
                
                array_push( $this->categories, $category );
    
                $this->categories_objects_indexed[$category['id']] = $wbg_category ;
    
                array_push( $this->categories_objects, $wbg_category );
            
            // }
        }
    }

    /**
     * Returns a flat array of product categories
     * 
     * @return array
     */
    public function all( string $output = 'array' ) : array
    {

        if ( $output === 'array' ) {
            return $this->categories;
        }

        if ( $output === 'array_indexed' ) {
            return $this->categories_indexed;
        }

        if ( $output === 'object' ) {
            return $this->categories_objects;
        }

        if ( $output === 'object_indexed' ) {
            return $this->categories_objects_indexed;
        }
    }

    /**
     * Return only the empties category
     * 
     * @return array
     */
    public function empties() : array
    {
        return array_filter( $this->categories,  function ( $category ) {
            $category->is_empty() === true;
        } );
    }


    /**
     * Return only the not empties category
     * 
     * @return array
     */
    public function not_empties() : array
    {
        return array_filter( $this->categories,  function ( $category ) {
            $category->is_empty() === false;
        } );
    }


    /**
     * Return a category object by ID
     * 
     * @param int|string id
     * @return Webigo_Woo_Product_Category
     */
    public function get_category( $id ) : object
    {

        if ( isset( $this->categories_objects_indexed[$id] )) {
            return $this->categories_objects_indexed[$id];
        }

        return new stdClass();
        
    }
    



}
