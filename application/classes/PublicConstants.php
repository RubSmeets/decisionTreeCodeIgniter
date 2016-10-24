<?php

final class PublicConstants {
    const SUCCESS = 0;
    const FAILED = 1;
    const FOUND = 2;
    const STATE_APPROVED = 1;
    const STATE_AWAIT_APPROVAL = 0;
    const STATE_OUTDATED = 2;
    const STATE_DECLINED = 3;
    const USER_NOT_BLOCKED = 0;
    const USER_BLOCKED = 1;
    const APPROVE_TOOL = 1;
    const DECLINE_TOOL = 0;
    const VALIDATE_FRAMEWORK = 0;
    const DONT_VALIDATE_FRAMEWORK = 1;
    const FORMAT_FRAMEWORK_DATA = 2;
    const IMG_PATH = "img/logos/";
    const AWAIT_APPROVE_PREFIX = "awaitApprove_";
    const OUTDATED_PREFIX = "outdated_";
    const DEFAULT_LOGO_NAME = "notfound.png";

    // make this private so no one can make one
    private function __construct(){
        // throw an exception if someone can get in here
        throw new Exception("Can't get an instance of PublicConstants");
    }
}

?>