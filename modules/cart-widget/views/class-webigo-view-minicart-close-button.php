<?php



class Webigo_View_Minicart_Close_Button {



    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies()
    {

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-buttons.php';
    }


    public function render() : void 
    {

        ?>

        <div class="wbg-minicart-close-button">
            <?php Webigo_View_Buttons::render("Voltar", "secondary") ?>
        </div>

        <script type="text/javascript">
            (function(){
                    const backToProductListButton = document.querySelectorAll(".mini-cart-wrapper .wbg-minicart-close-button")[0];
                    backToProductListButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        const miniCartDetail = document.querySelectorAll(".mini-cart-wrapper .cart-detail")[0];

                        if (miniCartDetail) {
                            if (miniCartDetail.classList.contains("active")) {
                                miniCartDetail.classList.remove("active");
                            }
                        }
                        e.stopPropagation();
                    });
            })();
        </script>

        <?php

        
    }

}
