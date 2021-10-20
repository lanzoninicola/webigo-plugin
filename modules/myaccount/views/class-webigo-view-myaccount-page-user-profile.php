<?php


class Webigo_View_Myaccount_Page_User_Profile {


    /**
     * @var Webigo_Customer
     */
    private $customer;

    public function __construct()
    {

        $this->load_dependencies();
    }


    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/includes/class-webigo-customer.php';
        $this->customer = new Webigo_Customer();

    }


    public function render() {
        ?>
            <div class="wbg-myaccount-profile-container">
                <div class="wbg-myaccount-profile-head">
                    <div class="wbg-myaccount-profile">
                        <?php echo esc_html( $this->avatar() ) ?>
                        <?php echo esc_html( $this->account_name() ) ?>
                    </div>
                    <?php echo esc_html( $this->joined() ) ?>
                </div>
            </div>  
        <?php
    }

    private function avatar()
    {
        ?>

        <div class="wbg-myaccount-profile-image">
            <img src="<?php echo esc_url( Webigo_Myaccount_Settings::images('avatar')['src'] ); ?>"/>
        </div>

<?php
    }

    private function account_name() 
    {
        ?>

        <div class="wbg-myaccount-profile-name">
            <div class="wbg-myaccount-profile-firstname">
                <?php echo esc_html( $this->customer->name() ) ?>
            </div>
            <div class="wbg-myaccount-profile-lastname">
                <?php echo esc_html( $this->customer->lastname() ) ?>
            </div>
        </div>

<?php
    }

    private function joined()
    {

        $joined_date = $this->customer->joined();

        ?>

        <div class="wbg-myaccount-profile-joined">
            <span>Desde</span>
            <span><?php echo esc_html( $joined_date->date("d-m-Y") ) ?></span>
        </div>
        

<?php
    }




}