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
 * Abstract DataMapperClass
 *
 * @author marc
 */
abstract class Default_Model_Mapper_Abstract
{
    /**
     * Data Access Object to use in mapper
     * 
     * @var Zend_Db_Table
     */
    protected $_dao;

    /**
     *
     * @param String|Zend_Db_Table_Abstract
     * @return Void
     */
    public function __construct($dao)
    {
        $this->setDao($dao);
    }

    /**
     * Set the DAO to use
     *
     * If $dao is a string, instantiate an object,
     * or use the one provided.
     * Check if it is the correct type and use it.
     *
     * @param String|Zend_Db_Table_Abstract
     * @return Void
     */
    public function setDao($dao)
    {
        if (is_string($dao)) {
            $dao = new $dao();
        }
        if (!$dao instanceof Zend_Db_Table_Abstract) {
            throw new InvalidArgumentException('DAO is not correct type (Zend_Db_Table_Abstract)');
        }
        $this->_dao = $dao;
    }

    /**
     * Get the DAO
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getDao()
    {
        return $this->_dao;
    }
}
