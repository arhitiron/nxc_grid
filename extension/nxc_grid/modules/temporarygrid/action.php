<?php

try
{
    $temporaryGrid = new \classes\TemporaryGrid($_POST);
    $result = $temporaryGrid->init();
} catch ( Exception $e ) {
    echo "Error code: ". $e->getCode() . " Error message: " . $e->getMessage();
}

    $response = array(  'error_message' => !$result ? $errorList : '',
        'result' => ($result) ? $result : ezpI18n::tr( 'nxcGrid', 'There is some error' )  );

    echo json_encode($response);
    eZExecution::cleanExit();


?>