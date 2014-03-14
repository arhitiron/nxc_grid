<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array(
                                    'script' => 'extension/nxc_grid/classes/nxcgridutils.php',
                                    'class' => 'NXCGridUtils',
                                    'operator_names' => array( 'rendergrid','renderadmingrid', 'renderadmingridlyoptions', 'getgridcolumns', 'getgrid' , 'renderfrontendgrid' )
                                );
?>