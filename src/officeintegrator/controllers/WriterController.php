<?php

namespace officeintegrator\controllers;

DEFINE('DS', DIRECTORY_SEPARATOR); 

include_once __DIR__ . DS . '..' . DS .'APIHelper.php';
include_once __DIR__ . DS . '..' . DS .'configurations' . DS . 'AppConfiguration.php';

use officeintegrator\APIHelper;
use officeintegrator\configurations\AppConfiguration;

class WriterController {

    const DOCUMENT_CREATE_END_POINT = AppConfiguration::WRITER_SERVER_URL . "writer/v1/officeapi/document";

    const DOCUMENT_PREVIEW_END_POINT = AppConfiguration::WRITER_SERVER_URL . "writer/v1/officeapi/document/preview";

    const DOCUMENT_CONVERT_END_POINT = AppConfiguration::WRITER_SERVER_URL . "writer/v1/officeapi/document/convert";

    const DOCUMENT_COMPARE_END_POINT = AppConfiguration::WRITER_SERVER_URL . "writer/v1/officeapi/document/compare";

    const DOCUMENT_DELETE_END_POINT = AppConfiguration::WRITER_SERVER_URL . "writer/v1/officeapi/document/{document_id}";

    const DOCUMENT_SESSION_DELETE_END_POINT = AppConfiguration::WRITER_SERVER_URL . "writer/v1/officeapi/session/{session_id}";

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

    /**
    * Method to create new writer document
    */
    public function createDocument($params) {
        $result = APIHelper::make_api_call('POST', self::DOCUMENT_CREATE_END_POINT, array(), $params, array());
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function editDocument($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::DOCUMENT_CREATE_END_POINT, array(), $params, $file_params);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function collabEditDocument($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::DOCUMENT_CREATE_END_POINT, array(), $params, $file_params);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function previewDocument($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::DOCUMENT_PREVIEW_END_POINT, array(), $params, $file_params);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function convertDocument($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::DOCUMENT_CONVERT_END_POINT, array(), $params, $file_params);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function compareDocument($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::DOCUMENT_COMPARE_END_POINT, array(), $params, $file_params);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function deleteDocumentSession($session_id) {
        $request_url = APIHelper::appendUrlWithTemplateParameters(self::DOCUMENT_SESSION_DELETE_END_POINT, array('session_id' => $session_id));
        $result = APIHelper::make_api_call('DELETE', $request_url, array(), array(), null);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function deleteDocument($document_id) {
        $request_url = APIHelper::appendUrlWithTemplateParameters(self::DOCUMENT_DELETE_END_POINT, array('document_id' => $document_id));
        $result = APIHelper::make_api_call('DELETE', $request_url, array(), array(), null);
        return $result;
    }

}
?>
