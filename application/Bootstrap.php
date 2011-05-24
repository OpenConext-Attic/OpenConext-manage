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

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDataTimeZone()
    {
        date_default_timezone_set('Europe/Amsterdam');
    }

    /**
     * Set up the db adapter
     *
     * @return Void
     */
    protected function _initDbRegistry()
    {
        $this->bootstrap('multidb');
        $multiDb = $this->getPluginResource('multidb');

        Zend_Registry::set('db_coin_portal', $multiDb->getDb('coin_portal'));
        Zend_Registry::set('db_engine_block', $multiDb->getDb('engine_block'));
        Zend_Registry::set('db_service_registry', $multiDb->getDb('service_registry'));
    }

    protected function _initRegistry()
    {
        Zend_Registry::set('config', $this->getApplication()->getOptions());
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }

    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Surfnet_Helper');
    }

    protected function _initViewHelpers()
    {
        $this->bootstrap ( 'view' );
        $view = $this->getResource ( 'view' );

        $view->addHelperPath(APPLICATION_PATH . '/views/helpers/', 'Application_View_Helper');
    }
}
