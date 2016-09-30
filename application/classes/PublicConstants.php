<?php

final class PublicConstants {
    const SUCCESS = 0;
    const FAILED = 1;
    const FOUND = 2;
    const STATE_APPROVED = 1;
    const STATE_AWAIT_APPROVAL = 0;
    const STATE_OUTDATED = 2;
    const USER_NOT_BLOCKED = 0;
    const USER_BLOCKED = 1;
    const IMG_PATH = "../img/logos/";

    // make this private so no one can make one
    private function __construct(){
        // throw an exception if someone can get in here
        throw new Exception("Can't get an instance of PublicConstants");
    }
}

?>