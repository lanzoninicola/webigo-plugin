<?php



class Webigo_View_Product_Whatsapp {

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * Dependency
     * 
     * @var Webigo_Pod_Custom_Fields
     */
    private $pod_custom_fields;

    /**
     * Dependency
     * 
     * @var Webigo_Pod_Custom_Settings_Page
     */
    private $pod_biz_info_settings;


    public function __construct( object $product )
    {
        $this->product = $product;
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {
      
        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-pod-custom-fields.php';
        $this->pod_custom_fields = new Webigo_Pod_Custom_Fields( 'product', $this->product, 'post' );
        
        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-pod-custom-settings-page.php';
        $this->pod_biz_info_settings = new Webigo_Pod_Custom_Settings_Page('informacoes_comerciais');
    }

     /**
     * Returns the value of Whatsapp option inside the product
     * 
     * @return bool true|false
     */
    public function is_whatsapp_required() : bool
    {
        
        $product_wa_pod_field = Webigo_Archive_Product_Settings::PODS_PRODUCT_WA_YESNO_FIELD;

        if ( $this->pod_custom_fields->is_field_exists( $product_wa_pod_field )) {
            return $this->pod_custom_fields->value_of( $product_wa_pod_field );
        }
        
        return false;
    }


    public function render() {

        $output = '<div class="wbg-product-whatsapp-contact">';
        
        $output .= '<p class="wbg-product-wa-label text-small">' . esc_html( $this->whatsapp_product_text() ) . '</p>'; 

        $output .= '<a href="' . esc_url( $this->build_wa_url() ) . '">';
        
        $output .= '<div class="wbg-product-wa-button">';
        $output .= '<div class="action-buttons">';
        $output .= '<svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0.0759277 32L2.32526 23.7827C0.937261 21.3773 0.207928 18.6507 0.209261 15.8547C0.213261 7.11333 7.32659 0 16.0666 0C20.3079 0.00133333 24.2893 1.65333 27.2839 4.65067C30.2773 7.648 31.9253 11.632 31.9239 15.8693C31.9199 24.612 24.8066 31.7253 16.0666 31.7253C13.4133 31.724 10.7986 31.0587 8.48259 29.7947L0.0759277 32V32ZM8.87193 26.924C11.1066 28.2507 13.2399 29.0453 16.0613 29.0467C23.3253 29.0467 29.2426 23.1347 29.2466 15.8667C29.2493 8.584 23.3599 2.68 16.0719 2.67733C8.80259 2.67733 2.88926 8.58933 2.88659 15.856C2.88526 18.8227 3.75459 21.044 5.21459 23.368L3.88259 28.232L8.87193 26.924V26.924ZM24.0546 19.6387C23.9559 19.4733 23.6919 19.3747 23.2946 19.176C22.8986 18.9773 20.9506 18.0187 20.5866 17.8867C20.2239 17.7547 19.9599 17.688 19.6946 18.0853C19.4306 18.4813 18.6706 19.3747 18.4399 19.6387C18.2093 19.9027 17.9773 19.936 17.5813 19.7373C17.1853 19.5387 15.9079 19.1213 14.3946 17.7707C13.2173 16.72 12.4213 15.4227 12.1906 15.0253C11.9599 14.6293 12.1666 14.4147 12.3639 14.2173C12.5426 14.04 12.7599 13.7547 12.9586 13.5227C13.1599 13.2933 13.2253 13.128 13.3586 12.8627C13.4906 12.5987 13.4253 12.3667 13.3253 12.168C13.2253 11.9707 12.4333 10.02 12.1039 9.22667C11.7813 8.45467 11.4546 8.55867 11.2119 8.54667L10.4519 8.53333C10.1879 8.53333 9.75859 8.632 9.39593 9.02933C9.03326 9.42667 8.00926 10.384 8.00926 12.3347C8.00926 14.2853 9.42926 16.1693 9.62659 16.4333C9.82526 16.6973 12.4199 20.7 16.3946 22.416C17.3399 22.824 18.0786 23.068 18.6533 23.2507C19.6026 23.552 20.4666 23.5093 21.1493 23.408C21.9106 23.2947 23.4933 22.4493 23.8239 21.524C24.1546 20.5973 24.1546 19.804 24.0546 19.6387V19.6387Z" fill="#005930"/>
        </svg>
        ';
        $output .= '</div>';
        $output .= '</div>';

        $output .= '</a>';
            
        $output .= '</div>';  

        return $output;
        
    }

    /**
     * Returns the whatsapp number inside the Custom Settings Page
     * 
     * @return string 
     */
    private function whatsapp_number() : string
    {
        $whatsapp_number_field = Webigo_Archive_Product_Settings::PODS_PRODUCT_WA_NUMBER_FIELD;

        if ( $this->pod_biz_info_settings->is_field_exists( $whatsapp_number_field  )) {
            return $this->pod_biz_info_settings->value_of( $whatsapp_number_field );
        }
        
        return '';
    }


     /**
     * Returns the text to render inside the product for whatsapp contact
     * 
     * @return string 
     */
    private function whatsapp_product_text() : string
    {
        $product_wa_text_field = Webigo_Archive_Product_Settings::PODS_PRODUCT_WA_TEXT_FIELD;

        $product_wa_text_fallback = Webigo_Archive_Product_Settings::PODS_PRODUCT_WA_TEXT_FALLBACK;

        if ( $this->pod_biz_info_settings->is_field_exists( $product_wa_text_field  )) {
            $_product_wa_text_field =  $this->pod_biz_info_settings->value_of( $product_wa_text_field );

            if ( $_product_wa_text_field === '' ) {
                return $product_wa_text_fallback;
            }

            return $_product_wa_text_field;
        }
        
        
    }

    private function build_wa_url() : string
    {
        $whatsapp_number = $this->whatsapp_number();
        $message = Webigo_Archive_Product_Settings::PRODUCT_WA_PREFIX_CUSTOMER_MSG;
        $product_description = $this->product->name();
        
        return "https://api.whatsapp.com/send?phone=$whatsapp_number&text=$message $product_description";
    }

 
}