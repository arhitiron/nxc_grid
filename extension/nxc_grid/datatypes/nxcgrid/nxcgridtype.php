<?php
/**
 * @class   NXCGridUtils
 * @author  ant@nxc.no
 * @date    1 Nov 2013
 * @author ant
 */
class NXCGridType extends eZDataType
{
    const DEFAULT_NAME_VARIABLE = "_nxcgrid_default_name_";
    const DATA_TYPE_STRING = "nxcgrid";

    public function __construct()
    {
        $this->eZDataType(self::DATA_TYPE_STRING, "NXC Grid", array('serialize_supported' => true));
    }

    function validateObjectAttributeHTTPInput($http, $base, $contentObjectAttribute)
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function storeObjectAttribute($contentObjectAttribute)
    {
        $grid = $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute("data_text", $grid["gridString"]);
    }

    function objectAttributeContent($contentObjectAttribute)
    {
        $grid = new NXCGrid();
        $gridWidth = $contentObjectAttribute->attribute('data_int');

        return array(   "gridString"     => $contentObjectAttribute->attribute('data_text'),
                        "gridWidth"      => $gridWidth,
                        "gridMaxCols"    => $grid->getGridsterColls($gridWidth),
                        "gridDimensionX" => $grid->getGridsterDimensionX(),
                        "gridDimensionY" => $grid->getGridsterDimensionY(),
                        "gridMarginX"    => $grid->getGridsterMarginX(),
                        "gridMarginY"    => $grid->getGridsterMarginY());
    }

    function isIndexable()
    {
        return true;
    }


    function metaData($contentObjectAttribute)
    {
        return $contentObjectAttribute->attribute("data_text");
    }

    /*!
     Fetches the http post var integer input and s
    tores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput($http, $base, $contentObjectAttribute)
    {
        $grid = new NXCGrid();
        $contentObjectAttribute->setAttribute("data_int", $grid->getGridWidthFromHTTP($http));
        $contentObjectAttribute->setAttribute("data_text", $grid->getGridContentFromHTTP($http));

        return true;
    }

    function customObjectAttributeHTTPAction($http, $action, $contentObjectAttribute, $parameters)
    {
        $params = explode('-', $action);
        switch ($params[0]) {
            case 'custom_attribute_browse':
                $module = $parameters['module'];

                $redirectionURI = $redirectionURI = $parameters['current-redirection-uri'];
                eZContentBrowse::browse(array(
                    'action_name' => 'SelectObjectRelationListNode',
                    'browse_custom_action' => array('name' => 'CustomActionButton[' . $contentObjectAttribute->attribute('id') . '_custom_attribute-' . $params[1] . ']',
                    'value' => $contentObjectAttribute->attribute('id')),
                    'from_page' => $redirectionURI,
                    'cancel_page' => $redirectionURI,
                    'persistent_data' => array('HasObjectInput' => 0),), $module);
                break;
        }
    }

    function title($contentObjectAttribute, $name = "name")
    {
        $title = $contentObjectAttribute->content();
        return $title->attribute($name);
    }

    function initializeObjectAttribute($contentObjectAttribute, $currentVersion, $originalContentObjectAttribute)
    {
        if ($currentVersion == false) {
            $multioption = $contentObjectAttribute->content();
            if ($multioption) {
                $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
                $multioption->setName($contentClassAttribute->attribute('data_text1'));
                $contentObjectAttribute->setAttribute("data_text", $multioption->xmlString());
                $contentObjectAttribute->setContent($multioption);
            }
        } else {
            $dataText = $originalContentObjectAttribute->attribute("data_text");
            $contentObjectAttribute->setAttribute("data_text", $dataText);
        }
    }

    function fetchClassAttributeHTTPInput($http, $base, $classAttribute)
    {
        $classAttributeID = $classAttribute->attribute('id');
        if ($http->hasPostVariable('CustomActionButton')) {
        }
        if ($http->hasPostVariable('StoreButton') && $http->postVariable('StoreButton') == 'OK') {
        }
        return true;
    }

    function serializeContentObjectAttribute($package, $objectAttribute)
    {
        echo "serializeContentObjectAttribute-";
        $node = $this->createContentObjectAttributeDOMNode($objectAttribute);

        $dom = new DOMDocument('1.0', 'utf-8');
        $success = $dom->loadXML($objectAttribute->attribute('data_text'));

        $importedRoot = $node->ownerDocument->importNode($dom->documentElement, true);
        $node->appendChild($importedRoot);

        return $node;
    }

    function unserializeContentObjectAttribute($package, $objectAttribute, $attributeNode)
    {
        //echo "unserializeContentObjectAttribute-";
        $rootNode = $attributeNode->getElementsByTagName('nxcgrid')->item(0);
        $xmlString = $rootNode ? $rootNode->ownerDocument->saveXML($rootNode) : '';
        $objectAttribute->setAttribute('data_text', $xmlString);
    }


}

eZDataType::register(NXCGridType::DATA_TYPE_STRING, "NXCGridType");

?>