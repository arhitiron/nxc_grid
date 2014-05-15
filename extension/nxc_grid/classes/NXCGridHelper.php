<?php
/**
 * @class   NCXGrid
 * @author  ant@nxc.no
 * @date    4 Nov 2013
 * @author ant
 */


class NXCGridHelper
{
    /**
     * @param link to array $dataArray
     */
    public static function fillingResultsFromJSON(&$dataArray) {
        foreach($dataArray as $key => $item) {
            if (array_key_exists("node_id", $item) && is_numeric($item['node_id']) && $item['node_id'] != 0 && $item['node_id'] != 1) {
                $node = eZContentObjectTreeNode::fetch($item['node_id']);
                $dataArray[$key]["title"] = "$node->Name";
            }
            if (array_key_exists("node_id", $item) && $item['node_id'] == "undefined") {
                $dataArray[$key]['node_id'] = 0;
            }
        }
    }

    /**
     * @param array $data
     * @return the result of the template file
     */

    public static function getTemplateByData($data = array()){
        if (!empty($data)) {
            $node = eZContentObjectTreeNode::fetch($data['node_id']);
            $tpl = eZTemplate::factory();
            $tpl->setVariable("cellNode", $node);
            $tpl->setVariable("class", $node->ClassIdentifier);
            $tpl->setVariable("data", $data);
            $template = self::getTemplateNameByTypeDimension($node->ClassIdentifier, array($data['size_x'], $data['size_y']) );
            $extraParameters = false;
            $checkUri = $tpl->loadURIRoot('design:content/grid/'.$template, false, $extraParameters);
            if (!empty($checkUri)) {
                $result = $tpl->fetch('design:content/grid/'.$template, false, true);
                return $result['result_text'];
            }
            $result = $tpl->fetch('design:content/grid/cell_default.tpl', false, true);
            return $result["result_text"];
        }

    }

    private static function getTemplateNameByTypeDimension($type = "default", $dimension = array(1, 1)) {
        return "cell_" . $type . "_" . $dimension[0] . "x" . $dimension[1]. ".tpl";
    }
}