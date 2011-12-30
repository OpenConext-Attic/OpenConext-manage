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
abstract class Surfnet_Zend_Controller_Abstract
    extends Zend_Controller_Action
{
    /**
     * Search parametrs (limit, offset, sort, month, year etc.)
     * 
     * @var Surfnet_Search_Parameters
     */
    protected $_searchParams;
    
    /**
     * External parameters input filter.
     * 
     * @var type 
     */
    protected $_inputFilter;
    
    public function init()
    {
        $action = $this->getRequest()->getActionName();

        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch()->addActionContext(
            $action,
            array(
                'json',
                'json-export',
                'csv-export'
            )
        )->initContext();

        $this->_inputFilter = $this->_helper->FilterLoader();
        $this->_searchParams = Surfnet_Search_Parameters::create(
            $this->_inputFilter
        );
        $this->_initExportParameters();
        $this->view->gridConfig = $this->_helper->gridSetup(
            $this->_inputFilter
        );
        $this->_helper->ContextSwitch()->setGridConfig($this->view->gridConfig);
    }
    
    /**
     * Set up an array to store extra export parameters.
     * 
     * @return void
     */
    protected function _initExportParameters()
    {
        $this->view->exportParameters = array();
    }
    
    /**
     * Add extra hidden parameter to export form.
     * 
     * @return void
     */
    protected function _addExportParameter($name, $value)
    {
        $this->view->exportParameters[$name] = $value;
    }
}
