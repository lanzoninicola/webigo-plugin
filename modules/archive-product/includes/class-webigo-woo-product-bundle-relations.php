<?php

/**
 *  Utility class to register the relation between products and product bundles
 */

class Webigo_Woo_Product_Bundle_Relations
{

    /**
     * This property describes relation
     * 
     * 1 parent to MANY children
     * 
     * @var array
     */
    private $parents;

    /**
     * This property describes relation
     * 
     * 1 child to MANY parents
     * 
     * @var array
     */
    private $childs;


    public function __construct() {

        $this->parents = array();
        $this->childs = array();
    }

    /**
     * 
     * @param Webigo_Woo_Product_Bundle
     */

    public function load_relations( $bundle_object )
    {

        if( ! isset( $bundle_object )) {
            return;
        }

        if ( $bundle_object->type() === 'bundle' ) {
            
            $parent_id = $bundle_object->get_id();

            $childs = $bundle_object->get_bundled_items();

            foreach ($childs as $child) {

                $child_id = $child->item_id;

                $this->parents[$parent_id] = array();
                
                array_push( $this->parents[$parent_id], $child_id );

                $this->childs[$child_id] = array();

                array_push( $this->childs[$child_id], $parent_id );
            }
        }

    }


    /**
     * 
     * Check if the product is part of bundle
     * 
     * @param  int $product_id
     * @return bool
     */

    public function is_part_of_bundle( $product_id ) {

        if ( ! empty( $this->childs[$product_id] ) ) {
            return true;
        }

        return false;

    }

    /**
     * 
     * Returns an array of ids of parents bundle product
     * 
     * @param  int $product_id
     * @return array of $product_id 
     */

    public function get_parents( $product_id ) {

        return $this->childs[$product_id];

    }


}
