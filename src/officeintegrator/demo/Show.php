<?php

namespace officeintegrator\demo;

DEFINE('DS', DIRECTORY_SEPARATOR); 

include_once __DIR__ . DS . '..' . DS .'controllers' . DS . 'ShowController.php';

$demo_resource_dir = __DIR__ . DS . 'files' . DS;

$demo_file = $demo_resource_dir . 'ZohoShow.pptx';

$coverted_output_file = $demo_resource_dir . 'ZohoShowConverted.pdf';

use officeintegrator\controllers\ShowController;

$controller = ShowController::getInstance();

try {
    $editor_settings = '{ "language":"en" }';
    $user_info = '{ "user_id":"1000123", "display_name":"PHP Guest User" }';
    $document_info = '{ "document_name":"New Presentation", "document_id": "' . hexdec( uniqid() ) . '" }';
    $permissions = '{ "document.export":true, "document.print":true, "document.edit":true }';
    $callback_settings = '{ "save_format":"pptx", "save_url":"https://domain.com/save.php", "context_info":"additional docuser info" }';

    $createPresentationParams = array(
        "user_info" => $user_info,
        "permissions" => $permissions,
        "document_info" => $document_info,
        "editor_settings" => $editor_settings,
        "callback_settings" => $callback_settings
    );

    $createResponse = $controller->createPresentation($createPresentationParams);

    $createPresentationParams[ "document_info"] = '{ "document_name":"New Presentation", "document_id": "' . uniqid() . '" }';

    $file_params = array(
        "document" => $demo_file
    );

    $editResponse = $controller->editPresentation($createPresentationParams, $file_params);

    $collabEditResponse = $controller->collabEditPresentation($createPresentationParams, $file_params);

    $previewResponse = $controller->previewPresentation(array(), $file_params);

    $conversionParams = array(
        "format" => "pdf"
    );

    $convertedFileBytes = $controller->convertPresentation($conversionParams, $file_params);

    file_put_contents($coverted_output_file, $convertedFileBytes);

    $deleteSessionResponse = $controller->deletePresentationSession($createResponse["session_id"]);

    $deleteDocumentResponse = $controller->deletePresentation($editResponse["document_id"]);

} catch(Exception $e) {
    print_r( $e->getMessage() );
}

?>