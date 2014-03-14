<?php
/**
 * User: ant
 * Date: 2/27/14
 * Time: 5:28 PM
 */

namespace classes;

class TemporaryGrid {

    private $httpRequest;

    function __construct($http)
    {
        $this->httpRequest = $http;
    }

    public function init()
    {
        $action = $this->httpRequest["action"];
        $class = new \ReflectionClass(__CLASS__);
        $methods = $class->getMethods();
        foreach($methods as $method) {
            $methodName = $method->name;
            $methodLowerCase = mb_strtolower($methodName);
            if (strpos($methodLowerCase, $action) > -1) {
                return $this->$methodName();
            }
        }
    }

    private function setTemporaryGridToSession()
    {
        if (isset($this->httpRequest["grid"])) {
            $grid = $this->httpRequest["grid"];
            $_SESSION["NXCGrid"] = $grid;
        }
        return $_SESSION["NXCGrid"];
    }

    private function getGridByData()
    {
        $ini = \eZINI::instance("grid.ini");
        $gridsterParams = $ini->variableArray("Gridster", "Params");
        $xCellDimension =  $gridsterParams['DimensionX'];
        $gridWidth = $this->httpRequest['gridWidth'];
        $gridMaxCols = $gridWidth / $xCellDimension[0];
        $gridMaxCols = round($gridMaxCols, 0, PHP_ROUND_HALF_DOWN);
        $attributeId = $this->httpRequest['attributeId'];
        $gridData = $this->httpRequest['grid'];

        $nxcGrid = new \NXCGrid();
        $gridDimensionX = $nxcGrid->getGridsterDimensionX();
        $gridDimensionY = $nxcGrid->getGridsterDimensionY();

        if (isset($_SESSION["NXCGrid"]) && $_SESSION["NXCGrid"] != "")
        {
            $gridData = $_SESSION["NXCGrid"];
            unset($_SESSION["NXCGrid"]);
        }
        $tpl = \eZTemplate::factory();
        $tpl->setVariable('gridWidth', $gridWidth);
        $tpl->setVariable('gridMaxCols', $gridMaxCols);
        $tpl->setVariable('gridDimensionX', $gridDimensionX);
        $tpl->setVariable('gridDimensionY', $gridDimensionY);
        $tpl->setVariable('attributeId', $attributeId);
        $tpl->setVariable('gridData', $gridData);
        $result = $tpl->fetch( 'design:content/datatype/edit/gridarea.tpl' );
        return array('grid'=>$result, "jsonGrid"=>$_SESSION["NXCGrid"]);
    }
}