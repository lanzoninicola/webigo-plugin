<?php



class Webigo_View_Category_Attributes {

    /**
     * 
     * @var Webigo_Woo_Category
     */
    private $category;

    /**
     * Dependency
     * 
     * @var Webigo_Pod_Custom_Fields
     */
    private $pod_custom_fields;

    
    public function __construct( object $category )
    {
        $this->category = $category;

        $this->load_dependencies();
    }


    private function load_dependencies() : void
    {

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-pod-custom-fields.php';
        $this->pod_custom_fields = new Webigo_Pod_Custom_Fields( 'product_cat', $this->category, 'term' );

    }

    public function render() : string
    {

        $output = '<div class="wbg-category-attributes" data-category-id="' . esc_attr( $this->category->id() ) . '">';
        
        $output .= $this->render_description();
        
        $output .= $this->render_attributes();

        $output .= '</div>';

        return $output;
    }

    private function render_description() : string
    {

        $output = '<div class="wbg-category-attributes-descriptions" data-visibility="hidden">';
        foreach ( $this->pod_custom_fields->get() as $custom_field_values ) {

            if( ! empty( $custom_field_values['value'] ) ) {
                $output .= '<p class="wbg-category-attribute-description">' . esc_html($custom_field_values['description']) . '</p>';
            }
        }

        $output .= '</div>';

        return $output;

    }


    private function render_attributes() : string
    {

        $output = '<div class="wbg-category-attributes-values">';
        foreach ( $this->pod_custom_fields->get() as $custom_field_values ) {

            $image = (array) Webigo_Archive_Product_Settings::images( $custom_field_values['name'] );

            if( ! empty( $custom_field_values['value'] ) ) {
                // $output .= '<div class="wbg-category-attribute-value">' . esc_html($custom_field_values['label']) . ' ' . esc_html($custom_field_values['value']) . '</div>';
                $output .= '<div class="wbg-category-attribute-wrapper">';
                
                $output .= '<div class="wbg-category-attribute-image">';
                $output .= '<img width="13px" src="' . esc_url( $image['src'] ) . '" >';
                $output .= '</div>';
                
                $output .= '<div class="wbg-category-attribute-value">';
                $output .= '<strong>' . esc_html($custom_field_values['label']) . ': </strong>' . esc_html($custom_field_values['value']);
                $output .= '</div>';
                
                $output .= '</div>';
            }
        }
        $output .= '</div>';

        return $output;

    }
}
