<?php


class Webigo_View_Woo_Product_Price {


    /**
     * @var Webigo_Woo_Product
     */
    private $product;


    public function __construct( object $product ) {
        $this->product = $product;
    }

    public function render_price() : void
    {

        // var_dump( $this->product->price()  ); die;

        ?>

        <div class="wbg-product-price-wrapper">

        <?php if ( $this->product->is_sale() ) : ?>
            <div class="wbg-product-full-price">
                <span class="wbg-price-symbol wbg-full-price">R$</span>
                <span class="wbg-price wbg-full-price"><?php echo esc_html( number_format((float)$this->product->price(), 2, ',', '') ) ?></span>
            </div>
            <span> - </span>
        <?php endif; ?>

        <div class="wbg-product-sale-price">
            <span class="wbg-price-symbol wbg-final-price">R$</span>
            <span class="wbg-price wbg-final-price"><?php echo esc_html( number_format((float)$this->product->final_price(), 2, ',', '') ) ?>
        </div>

        </div>

        <?php
    }

    public function render_discount_percentage() : void 
    {
    
        // var_dump( $this->product->price()  ); die;

        if ( $this->product->is_sale() && $this->product->price() > 0 ) {
            $discount_percentage = 100 - ( $this->product->final_price() / (float)$this->product->price() ) * 100;
        
        ?>
            <div class="wbg-product-discount-wrapper">
                <div class="wbg-product-discount">
                    <span><?php echo esc_html( number_format((float)$discount_percentage, 2, ',', '')) . '% OFF' ?></span>
                </div>
            </div>
        
        <?php } ?>

        <?php
    }


}