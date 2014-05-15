<?php
/**
 * @class   NXCGridUtils
 * @author  ant@nxc.no
 * @date    1 Nov 2013
 * @author ant
 */

class NXCGridUtils {
    private $Operators;

    public function __construct()
    {
        $this->Operators = array('getgrid', 'renderfrontendgrid');
    }

    function &operatorList() { return $this->Operators; }
    function namedParameterPerOperator() { return true; }

    function namedParameterList()
    {
        return array (
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
            case "getgrid":
                $operatorValue = $this->getTemplateFromJSON( $namedParameters['data']);
                break;
            case "renderfrontendgrid":
                $operatorValue = $this->getFrontendTemplateFromJSON( $namedParameters['data']);
                break;
        }
    }

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
            NXCGridHelper::fillingResultsFromJSON($dataArray);
            return json_encode($dataArray);
        }


        $dataFromJSON = json_decode($json, true);
        NXCGridHelper::fillingResultsFromJSON($dataFromJSON);
        return json_encode($dataFromJSON);
    }

    private function getFrontendTemplateFromJSON($json) {
        $result = "";
        $dataArray = json_decode($json, true);
        foreach ($dataArray as $item) {
            $result .= NXCGridHelper::getTemplateByData($item);
        }
        return $result;
    }


}

?>