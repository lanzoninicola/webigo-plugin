<?php


class Webigo_View_Add_To_Cart
{

    private $product;

    private $product_addtocart;

    public function __construct()
    {

        $this->load_dependencies();
    }

    private function load_dependencies()
    {
    }

    public function render($product)
    {
        $this->product = $product;

        echo  '<div class="webigo-add-to-cart-container" data-visibility="hidden" data-product-id="' . $this->product->id() . '">';

        $this->render_add_to_cart_button();

        echo  '</div>';

        $this->render_notification();
    }

    private function render_add_to_cart_button() {


        echo  '<div class="webigo-add-to-cart-button-wrapper">';
        
        $button_label = 'Adicionar ao carrinho';
        
        $html = sprintf(
            '<button class="webigo-add-to-cart-button" data-product-id=%s>%s</button>',
            esc_html( $this->product->id() ),
            esc_html( $button_label )
            
        );
        echo wp_kses_post( $html );

        echo '</div>';

    }


    private function render_notification() {
        echo  '<div class="added-to-cart-notification" data-visibility="hidden" data-product-id="' . esc_html( $this->product->id() ) . '"></div>';
    }
}
