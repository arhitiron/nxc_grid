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

    public static $instance;

    public static function getInstance() {

        if (self::$instance != null)
            return self::$instance;

        self::$instance = new NXCGrid();
        return self::$instance;
    }

    private function __construct() {
        $this->ini = eZINI::instance( 'grid.ini' );
        $this->cellTypes = $this->ini->variableArray('CellType', 'Type');
        $this->cellSize = $this->ini->variableArray('CellSize', 'Size');
        $this->gridsterParams = $this->ini->variableArray('Gridster', 'Params');
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

    public function getGridsterParam($name) {
        return $this->gridsterParams["$name"][0];
    }
}
?>