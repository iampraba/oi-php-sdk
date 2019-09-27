<?php

namespace officeintegrator\demo;

DEFINE('DS', DIRECTORY_SEPARATOR); 

include_once __DIR__ . DS . '..' . DS . 'controllers'. DS. 'SheetController.php';

$demo_file = __DIR__ . DS . 'files' . DS . 'ZohoSheet.xlsx';

use officeintegrator\controllers\SheetController;

$controller = SheetController::getInstance();

try {

    $editor_settings = '{ "language":"en" }';
    $user_info = '{ "user_id":"1000123", "display_name":"PHPGuestUser" }';
    $document_info = '{ "document_name":"New Presentation", "document_id": "' . hexdec( uniqid() ) . '" }';
    $permissions = '{ "document.export":true, "document.print":true, "document.edit":true }';
    $callback_settings = '{ "save_format":"xlsx", "save_url":"https://domain.com/save.php", "context_info":"additional docuser info" }';

    $createSheetParams = array(
        "user_info" => $user_info,
        "permissions" => $permissions,
        "document_info" => $document_info,
        "editor_settings" => $editor_settings,
        "callback_settings" => $callback_settings
    );

    $createSheetResponse = $controller->createSheet($createSheetParams);

    $createSheetParams[ "document_info"] = '{ "document_name":"New Spread Sheet", "document_id": "' . hexdec( uniqid() ) . '" }';

    $file_params = array(
        "document" => $demo_file
    );

    $createSheetResponse[ "document_info"] = '{ "document_name":"New Document", "document_id": "' . uniqid() . '" }';

    $editResponse = $controller->editSheet($createSheetParams, $file_params);

    $collabEditResponse = $controller->collabEditSheet($createSheetParams, $file_params);

    $previewResponse = $controller->previewSheet(array(), $file_params);

} catch(Exception $e) {
    print_r( $e->getMessage() );
}

?>