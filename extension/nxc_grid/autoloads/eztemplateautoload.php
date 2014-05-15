<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array(
                                    'script' => 'extension/nxc_grid/classes/nxcgridutils.php',
                                    'class' => 'NXCGridUtils',
                                    'operator_names' => array( 'getgrid' , 'renderfrontendgrid' )
                                );
?>