<?php


class Webigo_Globals {

    public static function set( string $key, $value) : void
    {
        $GLOBALS[$key] = $value;
    }

    public static function get( string $key)
    {
        return ( isset($GLOBALS[$key] ) ? $GLOBALS[$key] : null );
    }

    public static function forget( string $key) : void
    {
        unset( $GLOBALS[$key] );
    }
}