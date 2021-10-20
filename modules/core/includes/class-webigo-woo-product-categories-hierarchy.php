<?php

require_once WEBIGO_PLUGIN_PATH . 'modules/core/includes/class-webigo-woo-product-categories.php';

class Webigo_Woo_Product_Categories_Hierarchy extends Webigo_Woo_Product_Categories {


    private $hierarchy_by_parent_id;

     /**
     * 
     * 'level' => array( 'category_id' => Webigo_Woo_Product_Category )
     * 
     * @var array
     */
    private $hierarchy;


    public function __construct( )
    {
        parent::__construct();

        $this->load_hierarchy_by_parent_id();
    }

    /**
     * 
     * Internal utility function
     * 
     * Returns List of product categories organized by parent ID
     * 
     *    'parent_id' => array( ...childs )
     * 
     * @return array
     */
    private function load_hierarchy_by_parent_id( ) : void
    {

        foreach ( $this->categories_objects as $category ) {
            $parent_id = $category->parent(); 
            if ( ! isset( $this->categories_objects[$parent_id] ) ) {
                $this->hierarchy_by_parent_id[$parent_id] = array();
            }

            $this->hierarchy_by_parent_id[$parent_id][$category->id()] = $category;
        }
    }


    /**
     * @param Webigo_Woo_Product_Categories = Webigo_Woo_Product_Category[] - Starting Point
     * @param int 
     */
    private function load_hierarchy( array $categories, int $level, string $output = 'flat' ) : void
    {

        foreach ( $categories as $category ) {
            $has_children = $this->has_children( $category->id() );
            $children     = $this->children( $category->id() );

            $category->set_children( $children );
            $category->set_hierarchy_level( $level );

            if ( $output === 'flat' ) {
                $this->hierarchy[$category->id()] = $category;
            }

            if ( $output === 'level' ) {
                $this->hierarchy[$level][$category->id()] = $category;
            }

            if ( $has_children === true ) {
                $next_level = $level + 1;
                $this->load_hierarchy( $children, $next_level, $output );
            }
        }
    }

    /**
     * 
     * 'level' => array( 'category_id' => Webigo_Woo_Product_Category )
     * 
     * @return array
     */
    public function load( string $output = 'flat' ) : array
    {

        if ( $output === 'flat' ) {
            $this->load_hierarchy( $this->categories_objects, 0 , 'flat');
        }

        if ( $output === 'level' ) {
            $this->load_hierarchy( $this->categories_objects, 0 , 'level');
        }

        return $this->hierarchy;
    }

    /**
     * Returns true if category is in the first level of hierarchy.
     * 
     * @param int|string
     * @return bool
     */
    public function is_first_level( $category_id ) : bool
    {

        if ( isset( $this->hierarchy_by_parent_id[0][$category_id] ) ) {
            return true;
        }

        return false;
    }

    /**
     * Returns the list of first level categories
     * 
     * @return Webigo_Woo_Product_Category[]
     */
    public function first_level() : array
    {

        if ( isset( $this->hierarchy_by_parent_id[0] ) ) {
            return $this->hierarchy_by_parent_id[0];
        }

        return [];
    }

    /**
     * Check if the category has children
     * 
     * @param int $parent_category_id
     * @return bool true|false
     */
    public function has_children( int $parent_category_id ) : bool
    {

        if ( isset( $this->hierarchy_by_parent_id[$parent_category_id] ) ) { 
            return count( $this->hierarchy_by_parent_id[$parent_category_id] ) > 0 ? true : false;
        }

        return false;
    }


    /**
     * Returns the list of children of a category
     * 
     * @return Webigo_Woo_Product_Category[]
     */
    public function children( int $parent_category_id ) : array
    {

        if ( isset( $this->hierarchy_by_parent_id[$parent_category_id] ) ) {
            return $this->hierarchy_by_parent_id[$parent_category_id];
        }
        
        return [];
        
    }





}
