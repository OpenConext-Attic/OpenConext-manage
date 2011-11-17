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

require_once('Surfnet/Search/Parameters.php');

/**
 * tests for the Parameters class.
 *
 * @author marc
 */
class Surfnet_Search_ParametersTest extends PHPUnit_Framework_TestCase
{
    /**
     * Filter to test.
     *
     * @var Surfnet_Zend_Filter_Parameters
     */
    protected $_params;

    /**
     * Standard default value for tests
     *
     * @var Mixed
     */
    protected $_default = '__DEFAULT__';


    public function setUp()
    {
        $this->_params = Surfnet_Search_Parameters::create();
    }

    /**
     * Test the getter/setter combo.
     */
    public function testGetSetParameters()
    {
        $dummyParams = array(
                                    'param1' => 'value1',
                                    'param2' => 'value2',
                                );
        $this->_params->setSearchParams($dummyParams);
        $this->assertEquals(
                $dummyParams,
                $this->_params->getSearchParams(),
                'Parameters did not get processed correctly'

        );
    }
}
