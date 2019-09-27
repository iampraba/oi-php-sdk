<?php

namespace officeintegrator\demo;

DEFINE('DS', DIRECTORY_SEPARATOR); 

include_once __DIR__ . DS . '..' . DS .'controllers' . DS . 'WriterController.php';

$demo_resource_dir = __DIR__ . DS . 'files' . DS;

$demo_file = $demo_resource_dir . 'ZohoWriter.docx';

$compare_demo_file1 = $demo_resource_dir . 'CompareDocument1.docx';

$compare_demo_file2 = $demo_resource_dir . 'CompareDocument2.docx';

$coverted_output_file = $demo_resource_dir . 'ZohoWriterConverted.pdf';

use officeintegrator\controllers\WriterController;

$controller = WriterController::getInstance();

try {
    $document_info = '{ "document_name":"New Document", "document_id": "' . uniqid() . '" }';
    $permissions = '{ "document.export":true, "document.print":true, "document.edit":true, "review.changes.resolve": false, "review.comment":true, "collab.chat":true }';
    $editor_settings = '{ "unit":"in", "language":"en", "view":"pageview" }';
    $document_defaults = '{ "orientation":"portrait", "paper_size":"Letter", "font_name":"Lato", "font_size":14, "track_changes": "disabled", "margin": {"left":"1.5in","right":"1.5in","top":"0.25in","bottom":"0.25in"} }';
    $user_info = '{ "user_id":"1000123", "display_name":"PHP Guest User" }';
    $callback_settings = '{ "save_format":"docx", "save_url":"https://domain.com/save.php", "context_info":"additional doc / user info" }';

    $params = array(
        "user_info" => $user_info,
        "permissions" => $permissions,
        "document_info" => $document_info,
        "editor_settings" => $editor_settings,
        "document_defaults" => $document_defaults,
        "callback_settings" => $callback_settings
    );

    $createResponse = $controller->createDocument($params);

    $params[ "document_info"] = '{ "document_name":"New Document", "document_id": "' . uniqid() . '" }';

    $file_params = array(
        "document" => $demo_file
    );

    $editResponse = $controller->editDocument($params, $file_params);

    $collabEditResponse = $controller->collabEditDocument($params, $file_params);

    $previewResponse = $controller->previewDocument(array(), $file_params);

    $conversionParams = array(
        "format" => "pdf"
    );

    $convertedFileBytes = $controller->convertDocument($conversionParams, $file_params);

    file_put_contents($coverted_output_file, $convertedFileBytes);

    $compareFilesParams = array(
        "document1" => $compare_demo_file1,
        "document2" => $compare_demo_file2
    );

    $compareResponse = $controller->compareDocument(array(), $compareFilesParams);

    $deleteSessionResponse = $controller->deleteDocumentSession($createResponse["session_id"]);

    $deleteDocumentResponse = $controller->deleteDocument($editResponse["document_id"]);

} catch(Exception $e) {
    print_r( $e->getMessage() );
}

?>
