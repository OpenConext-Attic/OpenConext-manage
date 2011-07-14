<?php
/**
 * SURFconext Manage
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
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

require_once 'Surfnet/Filter/InArray.php';

/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_FilterLoader extends Zend_Controller_Action_Helper_Abstract
{
    const GRID_CONFIG_APPLICATION_PATH = '/configs/grid.ini';

    // grid config identifier
    protected $_configId;
    
    /**
     *
     * @return Zend_Filter_Input
     */
    public function direct()
    {
        if (func_num_args() > 0) $this->_configId = (string) func_get_arg(0);
        return $this->_getFilterForCurrentAction();
    }

    /**
     * Load the appropriate filter.
     *
     * @return Zend_Filter_Input
     */
    protected function _getFilterForCurrentAction()
    {
        $sortOptions = $this->_getSortOptions();

        /**
         * Input filtering/validation.
         */
        $filters = array(
            'results'    => array('Int'),
            'startIndex' => array('Int'),
            'dir' =>array(
                new Surfnet_Filter_InArray(
                    array('asc', 'desc'),
                    (isset($sortOptions['defaultDir'])?$sortOptions['defaultDir']:'asc')
                )
            ),
            'sort' => array(
                new Surfnet_Filter_InArray(
                    $sortOptions['fields'],
                    $sortOptions['defaultField']
                )
            ),
        );

        $validators = null;
        $options = array(
            'filterNamespace'   => 'Surfnet_Filter',
            'allowEmpty'        => true
        );
        $requestParams = array_merge(array_flip(array_keys($filters)), $this->getRequest()->getParams());

        return new Zend_Filter_Input(
            $filters,
            $validators,
            $requestParams,
            $options
        );
    }

    /**
     *
     * @param String $controller Front controller
     * @param String $action     Action
     */
    protected function _getSortOptions()
    {
        $config = $this->_getGridConfig();

        $currentRequest = $this->getRequest();
        $module         = $currentRequest->getModuleName();
        $controller     = $currentRequest->getControllerName();
        $action         = $currentRequest->getActionName();
        $id             = $this->_configId;

        if (!isset($config->$module)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown module: '$module'");
        }
        $config = $config->$module;

        if (!isset($config->$controller)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown controller: '$controller'");
        }
        $config = $config->$controller;

        if (!isset($config->$action)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown action: '$action'");
        }
        $config = $config->$action;

        if (!empty($id)) {
            if (!isset($config->$id)) {
                throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown id: '$id'");
            }
            $config = $config->$id;
        }

        $options = array(
            'defaultField'  => $config->defaultSortField,
            'fields'        => $this->_getGridSortFields($config),
        );

        if (isset($config->defaultSortDir)) {
            $options['defaultDir'] = $config->defaultSortDir;
        }

        return $options;
    }

    /**
     * @return Zend_Config_Ini
     */
    protected function _getGridConfig()
    {
        return new Zend_Config_Ini(
            APPLICATION_PATH .
            self::GRID_CONFIG_APPLICATION_PATH,
            APPLICATION_ENV,
            true
        );
    }

    protected function _getGridSortFields(Zend_Config $gridSortConfig)
    {
        $sortFields = array();
        foreach ($gridSortConfig->columns->toArray() as $name => $gridSortConfig) {
            if (isset($gridSortConfig['sortable']) && $gridSortConfig['sortable']) {
                $sortFields[] = $name;
            }
        }
        return $sortFields;
    }
}
