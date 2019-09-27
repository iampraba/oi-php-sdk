<?php

namespace officeintegrator\controllers;

DEFINE('DS', DIRECTORY_SEPARATOR); 

include_once __DIR__ . DS . '..' . DS .'APIHelper.php';
include_once __DIR__ . DS . '..' . DS .'configurations' . DS . 'AppConfiguration.php';

use officeintegrator\APIHelper;
use officeintegrator\configurations\AppConfiguration;

class SheetController {

    const SHEET_CREATE_END_POINT = AppConfiguration::SHEET_SERVER_URL . "sheet/officeapi/v1/spreadsheet";

    const SHEET_PREVIEW_END_POINT = AppConfiguration::SHEET_SERVER_URL . "sheet/officeapi/v1/spreadsheet/preview";


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
    public function createSheet($params) {
        $result = APIHelper::make_api_call('POST', self::SHEET_CREATE_END_POINT, array(), $params, array());
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function editSheet($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::SHEET_CREATE_END_POINT, array(), $params, $file_params);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function collabEditSheet($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::SHEET_CREATE_END_POINT, array(), $params, $file_params);
        return $result;
    }

    /**
    * Method to create new writer document
    */
    public function previewSheet($params, $file_params) {
        $result = APIHelper::make_api_call('POST', self::SHEET_PREVIEW_END_POINT, array(), $params, $file_params);
        return $result;
    }

}
?>
