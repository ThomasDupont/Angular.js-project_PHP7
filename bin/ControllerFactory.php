<?php

namespace bin;

/***********************************************************************************************
 * Angular->php standard REST API  - Full native php REST API Angular friendly
 *   ControllerFactory.php Factory of controller for the MVC Pattern
 * Copyright 2016 Thomas DUPONT
 * MIT License
 ************************************************************************************************/

use bin\http\Http;
use bin\controllers\AjaxController;
use bin\log\Log;
use bin\models\mysql\SessionManager;

/**
* @pattern Factory
* All controller call must be secure with the CSRF token validation
*/
final class ControllerFactory {

    /**
    * @param Object Http()
    */
    public static function load(Http $http)
    : string
    {
        $type = $http->getHttp()->controller ?? "";
        switch($type) {
            case "Ajax":
                if(!self::_checkCSRF($http)) return json_encode(['success' => false]);
                $exec = new AjaxController($http);
                break;
            case "Upload":
                if(!self::_checkCSRF($http)) return json_encode(['success' => false]);
                $exec = new UploadController($http);
                break;
            case "CSRF":
                return self::_CSRFToken();
                break;
            default:
                return json_encode([
                    'success' => false,
                    'message' => Log::debug("Controller exception : Pas de controlleur {class} trouvé", ['class' => $type])
                ]);
        }
        return $exec->execute();
    }

    private static function _CSRFToken (string $test = "false")
    : string
    {
        if($test != "false") {
            return SessionManager::getCSRFToken() == $test;
        }
        $token = crypt(uniqid(), uniqid()."csrftoken");
        SessionManager::setCSRFToken($token);
        return $token;
    }

    private static function _checkCSRF(Http $http)
    : bool
    {
        $csrf = $http->getHttp()->csrf ?? false;
        return !( CSRFENABLE  && (( !isset($csrf) || $csrf == "" || !$csrf ) || !self::_CSRFToken($csrf) ) ) ;
    }
}
