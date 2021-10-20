<?php




class Webigo_View_Myaccount_Template_Form_Login{


    /**
     * @var Webigo_View_Myaccount_Login_Form
     */
    private $view_myaccount_login_form;

    /**
     * @var Webigo_View_Myaccount_Registration_Form
     */
    private $view_myaccount_registration_form;

    public function __construct() 
    {

        $this->load_dependencies();

    }

    private function load_dependencies()
    {
        
        require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-view-myaccount-form-login-home.php';
        $this->view_form_login_home = new Webigo_View_Form_Login_Home();

        require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-view-myaccount-form-login.php';
        $this->view_myaccount_login_form = new Webigo_View_Myaccount_Form_Login();

        require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-view-myaccount-registration-form.php';
        $this->view_myaccount_registration_form = new Webigo_View_Myaccount_Registration_Form();
    }


    public function home()
    {
        
        $this->view_form_login_home->render();
    }

    public function login_form()
    {
        
        $this->view_myaccount_login_form->render();
    }

    public function registration_form()
    {
        $this->view_myaccount_registration_form->render();
    }

}