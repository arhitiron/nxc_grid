<?php
/**
 * @class   NCXGrid
 * @author  ant@nxc.no
 * @date    4 Nov 2013
 * @author ant
 */

namespace classes;

class NXCGrid
{

    private $objID;
    private $nodeID;
    private $ini;
    private $cellTypes;
    private $cellSize;

    public function __construct($nodeID = false)
    {
        if ($nodeID)
            $this->nodeID = $nodeID;
    }

    private function getObject()
    {
        $object = \eZContentObject::fetch($this->objID);
        return $object;
    }

    public function getNode()
    {
        $node = \eZContentObjectTreeNode::fetch($this->nodeID);
        return $node;
    }

    public function getObjectTemplate($className = "", $type = "")
    {
        $data = $this->getNode($this->objID);
        $tpl = \eZTemplate::factory();
        $tpl->setVariable("data", $data);
        $template = $this->getTemplateName($className, $type);
        $result = $tpl->fetch('design:'.$template);

        return $result;
    }

    public function getAdminObjectTemplate($key, $className = "", $type = "")
    {
        $data = $this->getNode($this->objID);
        $tpl = \eZTemplate::factory();
        $tpl->setVariable("data", $data);
        $tpl->setVariable("key", $key);
        $tpl->setVariable("celltype", $this->cellTypes);
        $tpl->setVariable("cellsize", $this->cellSize);

        $template = $this->getTemplateName($className, $type);
        $result = $tpl->fetch('design:content/datatype/edit/' . $template);

        return $result;
    }

    private function getTemplateName($className = "", $type = "")
    {
        $template = "cell";
        if ($className != "") {
            $template .= "_" . $className;
        }

        if ($type != "") {
            $template .= "_" . $type;
        }

        $template .= ".tpl";

        return $template;
    }

    public function fillingResult($selector)
    {
        $node = $this->getNode();
        setcookie("cell_selector", $selector, time() + 10);
        setcookie("cell_node_id", $this->nodeID, time() + 10);
        setcookie("cell_title", $node->Name, time() + 10);
    }

    // new version

    public static function fillingResultsFromJSON(&$dataArray) {
        foreach($dataArray as $key => $item) {
            if (array_key_exists("node_id", $item) && is_numeric($item['node_id']) && $item['node_id'] != 0 && $item['node_id'] != 1) {
                $node = \eZContentObjectTreeNode::fetch($item['node_id']);
                $dataArray[$key]["title"] = "$node->Name";
            }
            if (array_key_exists("node_id", $item) && $item['node_id'] == "undefined") {
                $dataArray[$key]['node_id'] = 0;
            }
        }
    }

    public function getTemplateByData($data = array()){
        if (!empty($data)) {
            $node = \eZContentObjectTreeNode::fetch($data['node_id']);
            $tpl = \eZTemplate::factory();
            $tpl->setVariable("node", $node);
            $tpl->setVariable("class", $node->ClassIdentifier);
            $tpl->setVariable("data", $data);
            $sizeX = $data['size_x'];
            $sizeY = $data['size_y'];
            $template = $this->getTemplateNameByTypeDimension($node->ClassIdentifier, array($sizeX, $sizeY) );
            $extraParameters = false;
            $checkUri = $tpl->loadURIRoot('design:content/grid/'.$template, false, $extraParameters);
            if (!empty($checkUri)) {
                $result = $tpl->fetch('design:content/grid/'.$template, false, true);
                return $result["result_text"];
            }
            $result = $tpl->fetch('design:content/grid/cell_default.tpl', false, true);
            return $result["result_text"];
        }

    }

    private function getTemplateNameByTypeDimension($type = "default", $dimension = array(1, 1)) {
        $template = "cell_" . $type . "_" . $dimension[0] . "x" . $dimension[1];

        $template .= ".tpl";

        return $template;
    }
}