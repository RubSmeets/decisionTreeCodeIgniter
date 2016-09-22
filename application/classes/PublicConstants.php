<?php

final class PublicConstants {
    const SUCCESS = 0;
    const FAILED = 1;
    const FOUND = 2;
    const IMG_PATH = "../img/logos/";

    // make this private so no one can make one
    private function __construct(){
        // throw an exception if someone can get in here
        throw new Exception("Can't get an instance of PublicConstants");
    }
}

?>