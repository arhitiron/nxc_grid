<?php
/**
 * @class   NXCGrid
 * @author  ant@nxc.no
 * @date    1 Nov 2013
 * @author ant
 */
class NXCGrid
{

    private $ini;
    private $cellTypes;
    private $cellSize;
    private $gridsterParams;

    public function __construct() {
        $this->ini = eZINI::instance( 'grid.ini' );
        $this->cellTypes = $this->ini->variableArray("CellType", "Type");
        $this->cellSize = $this->ini->variableArray("CellSize", "Size");
        $this->gridsterParams = $this->ini->variableArray("Gridster", "Params");
    }

    public function getGridContentFromHTTP($http) {
        $post = $http->attribute('post');
        return $post['grid'];
    }

    public function getGridWidthFromHTTP($http)
    {
        $post = $http->attribute('post');
        $gridWidth = $post['gridwidth'];
        if (is_numeric($gridWidth)) {
            return $gridWidth;
        }
        preg_match_all('!\d+!', $gridWidth, $gridWidthWithoutString);
        return $gridWidthWithoutString[0][0];
    }

    public function getGridsterColls($width = false){
        $cols = $this->gridsterParams['DefaultCols'][0];
        if ($width) {
            $cols = $width / $this->gridsterParams['DimensionX'][0];
            $cols = round($cols, 0, PHP_ROUND_HALF_DOWN);
        }
        return $cols;
    }

    public function getGridsterDimensionX() {
        $dimensionX = $this->gridsterParams['DimensionX'][0];
        return $dimensionX;
    }

    public function getGridsterDimensionY() {
        $dimensionY = $this->gridsterParams['DimensionY'][0];
        return $dimensionY;
    }

    public function getGridsterMarginX() {
        $marginX = $this->gridsterParams['MarginX'][0];
        return $marginX;
    }

    public function getGridsterMarginY() {
        $marginY = $this->gridsterParams['MarginY'][0];
        return $marginY;
    }

    private function convertCellTypes() {
        foreach($this->cellTypes as &$type) {
            $type = $type[0];
        }
    }

    private function convertCellSizes() {
        foreach($this->cellSize as &$size) {
            $size = $size[0];
        }
    }

    public function getCellTypes() {
        $this->convertCellTypes();
        return $this->cellTypes;
    }
    public function getCellSizes() {
        $this->convertCellSizes();
        return $this->cellSize;
    }



}
?>