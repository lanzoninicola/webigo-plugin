<?php

interface IWebigo_Http_Request_Data {

    /**
     * Data sent by the client are sanitized and saved inside the request object
     * 
     * @return void
     */
    function sanitize_input();

    /**
     * Data sent must be trimmed first
     * 
     * @param string $value
     * @return string
     */
    function trimmer( $value );

    /**
     * Returns the data sanitized
     * 
     * @return array $data_sanitized
     */
    public function get();

}