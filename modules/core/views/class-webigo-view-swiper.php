<?php




class Webigo_View_Swiper
{

    public static function render_navigation( string $template_id = "1" )
    {

?>
        <?php if ( $template_id === "1" ) : ?>
            <div class="wbg-swiper-navigation" data-template-id="1">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

        <?php endif; ?>

        <?php if ( $template_id === "2" ) : ?>
  
            <div class="wbg-swiper-navigation" data-template-id="2">
                <div class="wbg-swiper-button wbg-swiper-button-prev">
                    <i class="ion-ios-arrow-back wbg-swiper-arrow"></i>
                </div>
                <div class="wbg-swiper-button wbg-swiper-button-next">
                    <i class="ion-ios-arrow-forward wbg-swiper-arrow"></i>
                </div>
            </div>

        <?php endif; ?>

    <?php
    }

    public static function render_pagination()
    {
    ?>

        <div class="wbg-swiper-pagination">
            <div class="swiper-pagination"></div>
        </div>
<?php
    }
}
