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
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

require 'Zend/Application.php';

/**
 * An override of Zend_Application to allow local config overrides in /etc/surfconext/manage.ini
 */
class Surfnet_Application extends Zend_Application
{
    const ENV_CONFIG_OVERRIDE_PATH = '/etc/surfconext/manage.ini';

    protected $_config;

    protected function _loadConfig($file)
    {
        $environment = $this->getEnvironment();


        if (file_exists(self::ENV_CONFIG_OVERRIDE_PATH)) {
            $configContent = file_get_contents($file) . file_get_contents(self::ENV_CONFIG_OVERRIDE_PATH);
            $tmpConfigFile = '/tmp/surfconext.manage.' . $environment . '.ini';
            file_put_contents($tmpConfigFile, $configContent);
            $config = new Zend_Config_Ini($tmpConfigFile, $environment);
        }
        else {
            $config = new Zend_Config_Ini($file);
        }
        $this->_config = $config;

        return $config->toArray();
    }

    public function getConfig()
    {
        return $this->_config;
    }
}