<?php


interface IWebigo_Hooks {

    /**
     * Add to the hooks collection (array) the registered hook.
     * This function can be called multiple times.
     * 
     * @param array $user_hook_data
     * @return void|false if all parameters are not passed
     * 
     * $user_hook_data = array( 
     *                  'hook'     => (string) hook name,
     *                  'callback' => (string|array) callback function to call
     *                  'priority' => (string) hook priority
     *              )                
     * 
     */
    public function register( array $user_hook_data );
}
