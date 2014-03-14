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

        $this->eZDataType(self::DATA_TYPE_STRING, ezpI18n::tr('kernel/classes/datatypes', "NXC Grid", 'Datatype name'),
            array('serialize_supported' => true));

    }

    function validateObjectAttributeHTTPInput($http, $base, $contentObjectAttribute)
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function storeObjectAttribute($contentObjectAttribute)
    {
        $grid = $contentObjectAttribute->content();
        $gridString = $grid["gridString"];
        $contentObjectAttribute->setAttribute("data_text", $gridString);

    }


    function objectAttributeContent($contentObjectAttribute)
    {
        $grid = new \NXCGrid();
        $gridWidth = $contentObjectAttribute->attribute('data_int');
        $gridString = $contentObjectAttribute->attribute('data_text');
        $cellTypes = $grid->getCellTypes();
        $cellSizes = $grid->getCellSizes();
        $gridMaxCols = $grid->getGridsterColls($gridWidth);
        $gridDimensionX = $grid->getGridsterDimensionX();
        $gridDimensionY = $grid->getGridsterDimensionY();
        $data =array(   "gridString"     => $gridString,
                        "gridWidth"      => $gridWidth,
                        "gridMaxCols"    => $gridMaxCols,
                        "gridDimensionX" => $gridDimensionX,
                        "gridDimensionY" => $gridDimensionY,
                        "cellTypes"      => $cellTypes,
                        "cellSizes"      => $cellSizes);
        return $data;
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
        $grid = new \NXCGrid();
        $gridWidth = $grid->getGridWidthFromHTTP($http);
        $gridContent = $grid->getGridContentFromHTTP($http);
        $contentObjectAttribute->setAttribute("data_int", $gridWidth);
        $contentObjectAttribute->setAttribute("data_text", $gridContent);

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
        $multioption = $contentObjectAttribute->content();
        return $multioption->attribute($name);
    }

    function hasObjectAttributeContent($contentObjectAttribute)
    {
        //echo "hasObjectAttributeContent-";
        return 1;
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

    function classAttributeContent($classAttribute)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $xmlString = $classAttribute->attribute('data_text5');
        $grid = array();
        if ($xmlString != '') {
            $success = $dom->loadXML($xmlString);
            if ($success) {
                $root = $dom->documentElement;
                // set the name of the node
                //$name = $root->getElementsByTagName( "name" )->item( 0 )->textContent;
                //$optionCounter = $root->getAttribute( "option_counter" );
                $gridNode = $root->getElementsByTagName("grid")->item(0);
                $gridList = $gridNode->getElementsByTagName("multioption");
                //Loop for grid

//                foreach ($gridList as $multioptiondata) {
//                    $multioption = array();
//                    $multioption['id'] = $multioptiondata->getAttribute("id");
//                    $multioption['name'] = $multioptiondata->getAttribute("name");
//                    $multioption['priority'] = $multioptiondata->getAttribute("priority");
//                    //$multioption['default_option_id'] = $multioptiondata->getAttribute( "default_option_id" );
//                    $multioption['options'] = array();
//                    $optionNode = $multioptiondata->getElementsByTagName("option");
//                    foreach ($optionNode as $optiondata) {
//                        $option = array();
//                        $option['id'] = $optiondata->getAttribute("id");
//                        $option['option_id'] = $optiondata->getAttribute("option_id");
//                        $option['value'] = $optiondata->getAttribute("value");
//                        //$option['additional_price'] = $optiondata->getAttribute( "additional_price" );
//                        $multioption['options'][] = $option;
//                    }
//                    $grid[] = $multioption;
//                }

            }
        }
        return array('multioption_list' => $grid);
    }

    function toString($contentObjectAttribute)
    {
        //echo "toString-";
        $content = $contentObjectAttribute->attribute('content');

        $multioptionArray = array();

        $setName = $content->attribute('name');
        $multioptionArray[] = $setName;

        $multioptionList = $content->attribute('multioption_list');

        foreach ($multioptionList as $key => $option) {
            $optionArray = array();
            $optionArray[] = $option['name'];
            //$optionArray[] = $option['default_option_id'];
            foreach ($option['optionlist'] as $key => $value) {
                $optionArray[] = $value['value'];
            }
            $multioptionArray[] = eZStringUtils::implodeStr($optionArray, '|');
        }
        return eZStringUtils::implodeStr($multioptionArray, "&");
    }


    function fromString($contentObjectAttribute, $string)
    {
        //echo "fromString-";
        if ($string == '')
            return true;

        $multioptionArray = eZStringUtils::explodeStr($string, '&');

        $multioption = new NXCGrid();

        $multioption->OptionCounter = 0;
        $multioption->Options = array();
        $multioption->Name = array_shift($multioptionArray);
        $priority = 1;
        foreach ($multioptionArray as $gridtr) {
            $optionArray = eZStringUtils::explodeStr($gridtr, '|');


            $newID = $multioption->addMultiOption(array_shift($optionArray),
                $priority/*,
                                            array_shift( $optionArray )*/);
            $optionID = 0;
            $count = count($optionArray);
            for ($i = 0; $i < $count; $i += 2) {
                $multioption->addOption($newID, $optionID, array_shift($optionArray) /*, array_shift( $optionArray )*/);
                $optionID++;
            }
            $priority++;
        }

        $contentObjectAttribute->setAttribute("data_text", $multioption->xmlString());

        return $multioption;

    }

    function serializeContentClassAttribute($classAttribute, $attributeNode, $attributeParametersNode)
    {
        //echo "serializeContentClassAttribute-";
        $defaultValue = $classAttribute->attribute('data_text5');
        $dom = $attributeParametersNode->ownerDocument;
        $defaultValueNode = $dom->createElement('default-value');
        $defaultValueNode->appendChild($dom->createTextNode($defaultValue));
        $attributeParametersNode->appendChild($defaultValueNode);
    }

    function unserializeContentClassAttribute($classAttribute, $attributeNode, $attributeParametersNode)
    {
        //echo "unserializeContentClassAttribute-";
        $defaultValue = $attributeParametersNode->getElementsByTagName('default-value')->item(0)->textContent;
        $classAttribute->setAttribute('data_text5', $defaultValue);
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