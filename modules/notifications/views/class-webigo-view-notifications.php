<?php



class Webigo_View_Notifications {



    public static function render()
    {
        ?>

        <div class="wbg-notifications-container">
            <div class="wbg-notifications-wrapper">
                <?php do_action( 'webigo_new_notifications' ); ?>
            </div>
        </div>

<?php
    }

    public static function success( string $message ) : void
    {
        ?>

        <div class="wbg-notification-item wbg-notification-success" data-visibility="hidden">
            <img class="wbg-success-icon" src="<?php echo esc_url( Webigo_Notifications_Settings::images('success')['src'] ) ?>"></img>
            <span class="text-small wbg-notification-message"><?php echo esc_html( $message ) ?></span>
        </div>

      <?php
    }

    public static function failed( string $message ) : void
    {

        ?>

        <div class="wbg-notification-item wbg-notification-failed" data-visibility="hidden">
            <img class="wbg-success-icon" src="<?php echo esc_url( Webigo_Notifications_Settings::images('failed')['src'] ) ?>"></img>
            <span class="text-small wbg-notification-message"><?php echo esc_html( $message ) ?></span>
        </div>

        <?php 
    }

    
}