<?php



class Webigo_View_Add_To_Cart_Notifications {


    public function render() : void 
    {
        if ( ! is_archive() ) {
            return;
        }
        
        Webigo_View_Notifications::success( 'Produtos adicionados ao carrinho!' ); 
        Webigo_View_Notifications::failed( 'Occoreu um erro! Ritente por favor.' );
    }
   
}