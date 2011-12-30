<?php
/**
 * SURFconext EngineBlock
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext EngineBlock
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

/**
 * A custom ContextSwitch which knows how to export JSON and CSV files.
 */
class Surfnet_Zend_Helper_ContextSwitch extends Zend_Controller_Action_Helper_ContextSwitch
{
    protected $_gridConfig;

    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $this->setConfig($options);
        } elseif (is_array($options)) {
            $this->setOptions($options);
        }

        if (empty($this->_contexts)) {
            $this->addContexts(array(
                'json' => array(
                    'suffix'    => 'json',
                    'headers'   => array('Content-Type' => 'application/json'),
                    'callbacks' => array(
                        'init' => 'initJsonContext',
                        'post' => 'postJsonContext'
                    )
                ),
                'json-export' => array(
                    'suffix'    => 'json',
                    'headers'   => array('Content-Type' => 'application/json'),
                    'callbacks' => array(
                        'init' => 'initExportContext',
                        'post' => 'postJsonExportContext'
                    )
                ),
                'csv-export' => array(
                    'suffix' => 'csv',
                    'headers'   => array('Content-Type' => 'application/csv'),
                    'callbacks' => array(
                        'init' => 'initExportContext',
                        'post' => 'postCsvExportContext'
                    )
                ),
                'xml'  => array(
                    'suffix'    => 'xml',
                    'headers'   => array('Content-Type' => 'application/xml'),
                )
            ));
        }

        $this->init();
    }

    public function setGridConfig(Zend_Config $config)
    {
        $this->_gridConfig = $config;
    }

    /**
     * Turns off viewRenderer auto-rendering
     *
     * @return void
     */
    public function initExportContext()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        if ($view instanceof Zend_View_Interface) {
            $viewRenderer->setNoRender(true);
        }
    }

    /**
     * JSON exporting processing
     *
     * @return void
     */
    public function postJsonExportContext()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        if ($view instanceof Zend_View_Interface) {
            /**
             * @see Zend_Json
             */
            if(method_exists($view, 'getVars')) {
                require_once 'Zend/Json.php';
                $json = Zend_Json::encode($view->ResultSet);
                $this->_exportData($json, 'json');
            } else {
                require_once 'Zend/Controller/Action/Exception.php';
                throw new Zend_Controller_Action_Exception('View does not implement the getVars() method needed to encode the view into JSON');
            }
        }
    }

    /**
     * CSV Exporting post processing
     *
     * @return void
     */
    public function postCsvExportContext()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        if ($view instanceof Zend_View_Interface) {
            /**
             * @see Zend_Json
             */
            if(method_exists($view, 'getVars')) {
                require_once 'Zend/Json.php';
                $data = $this->_convertArrayToCsv($view->ResultSet);
                $this->_exportData($data, 'csv');
            } else {
                require_once 'Zend/Controller/Action/Exception.php';
                throw new Zend_Controller_Action_Exception('View does not implement the getVars() method needed to encode the view into JSON');
            }
        }
    }

    protected function _exportData($data, $extension)
    {
        $request = $this->getRequest();
        $module     = $request->getModuleName();
        $controller = $request->getControllerName();
        $action     = $request->getActionName();

        $filename = "$module.$controller.$action.$extension";
        $response = $this->getResponse();
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"', true);
        $response->setHeader('Content-Length', strlen($data), true);
        $response->setBody($data);
    }

    protected function _convertArrayToCsv(array $records)
    {
        if (empty($records)) {
            return "";
        }

        $csv = $this->_getCsvHeadersFromFirstRow($records[0]);
        foreach ($records as $record) {
            $csv .= $this->_convertRecordToCsvLine($record);
        }
        
        return $csv;
    }

    /**
     * Get the array keys from the first row and try to translate using a grid config (if given).
     *
     * @param $row
     * @return string
     */
    protected function _getCsvHeadersFromFirstRow($row)
    {
        $record = array();
        $columnNames = array_keys($row);
        foreach ($columnNames as $columnName) {
            if (!isset($this->_gridConfig)) {
                $record[] = $columnName;
                continue;
            }

            if (!isset($this->_gridConfig->columns->$columnName)) {
                $record[] = $columnName;
                continue;
            }

            $record[] = $this->_gridConfig->columns->{$columnName}->label;
        }
        return $this->_convertRecordToCsvLine($record);
    }

    protected function _convertRecordToCsvLine($record)
    {
        $outStream = fopen("php://temp", 'a');

        fputcsv($outStream, $record, ',', '"');

        rewind($outStream);
        $csv = fgets($outStream);

        fclose($outStream);

        return $csv;
    }
}