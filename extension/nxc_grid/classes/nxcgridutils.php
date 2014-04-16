<?php
/**
 * @class   NXCGridUtils
 * @author  ant@nxc.no
 * @date    1 Nov 2013
 * @author ant
 */
use \classes;

class NXCGridUtils {
    private $Operators;

    public function __construct()
    {
        $this->Operators = array('rendergrid', 'renderadmingrid', 'renderadmingridlyoptions', 'getgridcolumns', 'getgrid', 'renderfrontendgrid');
    }

    function &operatorList() { return $this->Operators; }
    function namedParameterPerOperator() { return true; }

    function namedParameterList()
    {
        return array (
            'rendergrid' => array(
                'data' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
            ),
            'renderadmingrid' => array(
                'data' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
            ),
            'renderadmingridlyoptions' => array(
                'data' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
            ),
            'getgridcolumns' => array(
                'data' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
            ),
            'getgrid' => array(
                'data' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
            ),
            'renderfrontendgrid' => array(
                'data' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
            ),
        );
    }

    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
//            case "rendergrid":
//                $operatorValue = $this->renderGrid( $namedParameters['data']);
//                break;
//            case "renderadmingrid":
//                $operatorValue = $this->renderAdminGrid( $namedParameters['data']);
//                break;
//            case "renderadmingridlyoptions":
//                $operatorValue = $this->getGridlyOptionsTemplate( $namedParameters['data']);
//                break;
//            case "getgridcolumns":
//                $operatorValue = $this->getGridlyColumnsValue( $namedParameters['data']);
//                break;
            case "getgrid":
                $operatorValue = $this->getTemplateFromJSON( $namedParameters['data']);
//                echo $operatorValue;
                break;
            case "renderfrontendgrid":
                $operatorValue = $this->getFrontendTemplateFromJSON( $namedParameters['data']);
//                echo $operatorValue;
                break;
        }
    }

//    private function renderGrid($str) {
//        $result = $this->getTemplate($str, true);
//        return $result;
//    }
//    private function renderAdminGrid($str) {
//        $result = $this->getTemplate($str);
//        return $result;
//    }

//    private function getGridlyColumnsValue($str) {
//        $dataArray = $this->getDataArrayFromString($str);
//        $gridlyOptions = $dataArray['gridlyOptions'];
//        $columns = $gridlyOptions['columns'];
//        return $columns;
//    }
//
//    private function getTemplate($str, $showData = false) {
//        $dataArray = $this->getDataArrayFromString($str);
////        echo "<pre>";
////        print_r($dataArray);
//        $result = "";
//        $dataInCellTpl = false;
//        foreach($dataArray as $key => $dataItem) {
//            if (is_numeric($key)){
//                $dataInCell = new classes\NCXGrid($dataItem[3]);
//                if ($showData) {
//                    $dataInCellTpl = (string)$dataInCell->getObjectTemplate();
//                } else {
//                    $dataInCellTpl = (string)$dataInCell->getAdminObjectTemplate($key);
//                }
//                $width = $this->getSize($dataItem[0][0], $dataItem[0][1], $dataItem[0][4]);
//                $height = $this->getSize($dataItem[0][0], $dataItem[0][2], $dataItem[0][4]);
//                $result .= '<div class="brick '.$dataItem[0][3].'" style = "background:#ABC6DD; position: absolute; left: '.$dataItem[1].'; top: '.$dataItem[2].'; width:' . $width . 'px; height:'. $height .'px;">';
//
//                if (!$showData) {
//                    $result .= '<div class="delete">&times;</div>';
//                }
//
//                if ($dataInCellTpl){
//                    $result .= $dataInCellTpl;
//                }
//                $result .= '</div>';
//            }
//        }
//
//        $http = eZHTTPTool::instance();
//        if (isset($_SESSION["NXCGrid"]) && $_SESSION["NXCGrid"] != "")
//        {
//            if ($http->hasPostVariable( 'SelectedNodeIDArray' )) {
//                $SelectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
//                $NodeId = $SelectedNodeIDArray[0];
//                $post = $http->attribute('post');
//                foreach($post['CustomActionButton'] as $key => $value) {
//                    if (strpos($key, "_custom_attribute") > 0) {
//                        $viewSelector = $key;
//                        break;
//                    }
//                }
//                $dataInCell = new classes\NCXGrid($NodeId);
//                $result = $_SESSION["NXCGrid"];
//                $dataInCell->fillingResult($viewSelector);
//            }
//        }
//        return $result;
//    }

//    private function getGridlyOptionsTemplate($str) {
//        $dataArray = $this->getDataArrayFromString($str);
//        $gridlyOptions = $dataArray['gridlyOptions'];
//        $base = isset($gridlyOptions['base']) ? $gridlyOptions['base'] : 60;
//        $gutter = isset($gridlyOptions['gutter']) ? $gridlyOptions['gutter'] : 20;
//        $columns = isset($gridlyOptions['columns']) ? $gridlyOptions['columns'] : 26;
//
//        $result = "<script>";
//        $result .= "$('.gridly').gridly({ base: ".$base.", gutter: ".$gutter.", columns: ".$columns." }); $('.gridly').attr('gutter', ".$gutter.");";
//        $result .= "</script>";
//        $result .= "<div style='display: none;' id='gridlyhttpdata' data-base='$base' data-gutter='$gutter' data-columns='$columns' ></div>";
//
//        return $result;
//    }
//
//    private function getSize($measure, $multiplier, $gutter){
//        $dimension = $multiplier * $measure + $gutter * ($multiplier - 1);
//        return $dimension;
//    }


//    private function getDataArrayFromString($str) {
//        $cellsData = explode("], ", $str);
//        $cellsArray = array();
//        foreach($cellsData as $cellData) {
//            if (strlen($cellData) > 5 && strpos($cellData, "x")){
//                $cellData = str_replace("[", "", $cellData);
//                $cellDataArray = explode(",", $cellData);
//                $cellFieldArray = array();
//                foreach ($cellDataArray as $cellDataKey=> $item) {
//
//                    if ($cellDataKey == 0) {
//                        $itemArray = explode("_", $item);
//                        $multipliers = explode("x", $itemArray[0]);
////			            $gridlyOptions = $this->getGridlyOptions($cellsData[count($cellsData)-1]);
////                        echo "<pre>";
////                        print_r($cellsData);
////                        print_r( $cellsData[count($cellsData)-1]);
//                        $gridlyOptions = $this->getGridlyOptions($cellsData[count($cellsData)-1]);
////                        $item = array($itemArray[1], $multipliers[0], $multipliers[1], $item, $gridlyOptions["gutter"]); //$this->getGridlyOptions($cellsData[count($cellsData)-1])["gutter"]);
//                        $item = array($itemArray[1], $multipliers[0], $multipliers[1], $item, $gridlyOptions["gutter"]);
//                    }
//
//                    array_push($cellFieldArray, $item);
//                }
//                $cellsArray[] = $cellFieldArray;
//            }
//        }
//
//        $cellsArray["gridlyOptions"] = $this->getGridlyOptions($cellsData[count($cellsData)-1]);
////        echo "<pre>";
////        print_r($cellsArray["gridlyOptions"]);
//        return $cellsArray;
//    }

//    private function getGridlyOptions($str) {
//        $optionsData = explode(",", $str);
//        if (count($optionsData) == 0)
//            return array();
//
//        $options = array(   "base"    => $optionsData[0],
//                            "gutter"  => $optionsData[1],
//                            "columns" => $optionsData[2]);
//        return $options;
//    }





    //// new variant

    private function getTemplateFromJSON($json) {
        $http = eZHTTPTool::instance();
        $viewSelector = false;

        if (isset($_SESSION["NXCGrid"]) && $_SESSION["NXCGrid"] != "")
        {
            $dataJSONFromSession = $_SESSION["NXCGrid"];
            $dataArray = json_decode($dataJSONFromSession, true);
            unset($_SESSION["NXCGrid"]);
        } else {
            $dataArray = json_decode($json, true);
        }

        if ($http->hasPostVariable( 'SelectedNodeIDArray' )) {
            $SelectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
            $NodeId = $SelectedNodeIDArray[0];
            $post = $http->attribute('post');
            foreach($post['CustomActionButton'] as $key => $value) {
                if (strpos($key, "_custom_attribute") > 0) {
                    $viewSelector = $key;
                    break;
                }
            }
            if ($viewSelector){
                $viewSelector = explode("-", $viewSelector);
                $viewSelector = $viewSelector[count($viewSelector)-1];
                $dataArray[$viewSelector]['node_id'] = $NodeId;
            }
            classes\NXCGrid::fillingResultsFromJSON($dataArray);
            $newJSON = json_encode($dataArray);
            return $newJSON;
        }


        $dataFromJSON = json_decode($json, true);
        classes\NXCGrid::fillingResultsFromJSON($dataFromJSON);
        $jsonData = json_encode($dataFromJSON);



        return $jsonData;
    }

    private function getFrontendTemplateFromJSON($json) {
        $result = "";
        $dataArray = json_decode($json, true);
        $grid = new classes\NXCGrid();
        foreach ($dataArray as $item) {
            $result .= $grid->getTemplateByData($item);
        }
        return $result;
    }


}

?>