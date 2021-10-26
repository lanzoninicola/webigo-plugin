<?php

class Webigo_User {

    /**
     * @var WP_User - Current WP_User instance
     */
    private $current_user_logged = 0;

    public function __construct()
    {
        $this->current_user_logged = wp_get_current_user();
    }

    /**
     * @return string
     */
    public function firstname() : string
    {
        return $this->current_user_logged->first_name;
    }

    /**
     * @return string
     */
    public function lastname() : string
    {
        return $this->current_user_logged->first_name;
    }


    /**
     * @return bool
     */
    public function is_logged() : bool
    {
        return $this->current_user_logged === 0 ? false : true;
    }

    /**
     * Determine whether the user exists in the database
     * 
     * @return bool
     */
    public function exists() : bool
    {
        return $this->current_user_logged->exists();
    }

    /**
     * Determine whether the user is logged and exists
     * 
     * @return bool
     */
    public function is_valid() : bool
    {
        if ( $this->is_logged() && $this->exists() ) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function role() : string
    {
        if ( $this->is_valid() ) {
            return $this->current_user_logged->roles[0];
        }

        return '';
    }

    /**
     * 
     * @return bool
     */
    public function is_admin() : bool
    {
        if ( $this->is_valid() ) {
            
            $roles = (array) $this->current_user_logged->roles;

            return $roles[0] === 'administrator' ? true : false ;
        }

        return false;
    }

}

