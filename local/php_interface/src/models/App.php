<?php
/**
 * Created by PhpStorm.
 * User: Di Melnikov
 * Date: 11.05.2021
 * Time: 18:38
 */

namespace src\models;

class App
{
    public function __construct()
    {

    }

     static public function getUrl($request = null){
        if(isset($request) && !empty($request)){
            $ch = curl_init($request);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);

            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch);

            return $output;
        }
    }

}