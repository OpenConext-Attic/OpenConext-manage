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
class Surfnet_Helper_ExportSetup extends Zend_Controller_Action_Helper_Abstract
{
    const EXPORT_CONFIG_APPLICATION_PATH = '/configs/export.ini';

    /**
     * @return Zend_Config
     */
    public function direct()
    {
        return $this->_getExportConfigForCurrentAction();
    }

    protected function _getExportConfigForCurrentAction()
    {
        $config = $this->_getExportConfigFile();

        $currentRequest = $this->getRequest();
        $module         = $currentRequest->getModuleName();
        $controller     = $currentRequest->getControllerName();
        $action         = $currentRequest->getActionName();

        if (!isset($config->$module)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get export options, unknown module: '$module'");
        }
        $config = $config->$module;

        if (!isset($config->$controller)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get export options, unknown controller: '$controller'");
        }
        $config = $config->$controller;

        if (!isset($config->$action)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get export options, unknown action: '$action'");
        }
        $config = $config->$action;

        return $this->_postProcessing($config);
    }

    /**
     * @return Zend_Config_Ini
     */
    protected function _getExportConfigFile()
    {
        return new Zend_Config_Ini(
            APPLICATION_PATH .
            self::EXPORT_CONFIG_APPLICATION_PATH,
            APPLICATION_ENV,
            true
        );
    }

    protected function _postProcessing($config)
    {
        $config->title = "Exporteer";
        return $config;
    }
}
