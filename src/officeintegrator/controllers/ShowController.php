<?php

namespace officeintegrator\controllers;

DEFINE('DS', DIRECTORY_SEPARATOR); 

include_once __DIR__ . DS . '..' . DS .'APIHelper.php';
include_once __DIR__ . DS . '..' . DS .'configurations' . DS . 'AppConfiguration.php';

use officeintegrator\APIHelper;
use officeintegrator\configurations\AppConfiguration;

class ShowController {

    const PRESENTATION_CREATE_END_POINT = AppConfiguration::SHOW_SERVER_URL . "show/officeapi/v1/presentation";

    const PRESENTATION_PREVIEW_END_POINT = AppConfiguration::SHOW_SERVER_URL . "show/officeapi/v1/presentation/preview";

    const PRESENTATION_CONVERT_END_POINT = AppConfiguration::SHOW_SERVER_URL . "show/officeapi/v1/presentation/convert";

    const PRESENTATION_DELETE_END_POINT = AppConfiguration::SHOW_SERVER_URL . "show/officeapi/v1/presentation/{presentation_id}";

    const PRESENTATION_SESSION_DELETE_END_POINT = AppConfiguration::SHOW_SERVER_URL . "show/officeapi/v1/session/{session_id}";

    /**
     * @var SheetController The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     * @return SheetController The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }


    public function createPresentation($params) {
        $result = APIHelper::make_api_call('POST', self::PRESENTATION_CREATE_END_POINT, array(), $params, array());
        return $result;
    }

    public function editPresentation($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::PRESENTATION_CREATE_END_POINT, array(), $params, $file_params);
        return $result;
    }

    public function collabEditPresentation($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::PRESENTATION_CREATE_END_POINT, array(), $params, $file_params);
        return $result;
    }

    public function previewPresentation($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::PRESENTATION_PREVIEW_END_POINT, array(), $params, $file_params);
        return $result;
    }

    public function convertPresentation($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::PRESENTATION_CONVERT_END_POINT, array(), $params, $file_params);
        return $result;
    }

    public function deletePresentationSession($session_id) {
        $request_url = APIHelper::appendUrlWithTemplateParameters(self::PRESENTATION_SESSION_DELETE_END_POINT, array('session_id' => $session_id));
        $result = APIHelper::make_api_call('DELETE', $request_url, array(), array(), null);
        return $result;
    }

    public function deletePresentation($presentation_id) {
        $request_url = APIHelper::appendUrlWithTemplateParameters(self::PRESENTATION_DELETE_END_POINT, array('presentation_id' => $presentation_id));
        $result = APIHelper::make_api_call('DELETE', $request_url, array(), array(), null);
        return $result;
    }

}
?>
