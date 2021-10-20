<?php


class Webigo_View_Myaccount_Page {


    /**
     * @var Webigo_View_Myaccount_Page_Header
     */
    private $view_header;

    /**
     * @var Webigo_View_Myaccount_Page_User_Profile
     */
    private $view_user_profile;

    /**
     * @var Webigo_View_Myaccount_Page_User_Profile
     */
    private $view_fala_conosco;


    /**
     * @var Webigo_View_Myaccount_Page_Nav
     */
    private $view_nav;


    public function __construct()
    {

        $this->load_dependencies();
    }


    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/views/class-webigo-view-myaccount-page-header.php';
        $this->view_header = new Webigo_View_Myaccount_Page_Header();

        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/views/class-webigo-view-myaccount-page-user-profile.php';
        $this->view_user_profile = new Webigo_View_Myaccount_Page_User_Profile();

        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/views/class-webigo-view-myaccount-page-fala-conosco.php';
        $this->view_fala_conosco = new Webigo_View_Myaccount_Page_Fala_Conosco();

        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/views/class-webigo-view-myaccount-page-nav.php';
        $this->view_nav = new Webigo_View_Myaccount_Page_Nav();
      
    }


    public function render() {
            
        $this->view_header->render();
        $this->view_user_profile->render();
        $this->view_fala_conosco->render();
        $this->view_nav->render();
    }

}