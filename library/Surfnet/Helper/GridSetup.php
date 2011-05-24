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

/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_GridSetup extends Zend_Controller_Action_Helper_Abstract
{
    const GRID_CONFIG_APPLICATION_PATH = '/configs/grid.ini';

    /**
     *
     * @param  string $name
     * @return Zend_Config
     */
    public function direct($name)
    {
        return $this->_getGridConfigForAction($name);
    }

    protected function _getGridConfigForAction($input)
    {
        $config = $this->_getGridConfig();

        $currentRequest = $this->getRequest();
        $module         = $currentRequest->getModuleName();
        $controller     = $currentRequest->getControllerName();
        $action         = $currentRequest->getActionName();

        if (!isset($config->$module)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown module: '$module'");
        }
        $gridConfig = $config->$module;

        if (!isset($gridConfig->$controller)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown controller: '$controller'");
        }
        $gridConfig = $gridConfig->$controller;

        if (!isset($gridConfig->$action)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown action: '$action'");
        }
        $gridConfig = $gridConfig->$action;

        $gridConfig->dir        = $input->dir;
        $gridConfig->startIndex = $input->startIndex;
        $gridConfig->pageSize   = $config->pageSize;

        return $gridConfig;
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
}
