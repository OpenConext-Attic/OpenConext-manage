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
 * Simple filter to check if an input value is in the allowed range,
 * return default if not.
 *
 * @author marc
 */
class Surfnet_Filter_InArray implements Zend_Filter_Interface
{
    /**
     * Default value returned when the input
     * is either empty or not in the allowed range.
     *
     * @var Mixed
     */
    protected $_default;

    /**
     * Range of values to search in.
     * 
     * @var Array 
     */
    protected $_haystack;


    public function __construct(Array $haystack=null, $default=null)
    {
        $this->setHaystack($haystack);
        $this->setDefault($default);
    }

    /**
     * Set the default value.
     *
     * @param Mixed Default value
     */
    public function setDefault($value)
    {
        $this->_default = $value;
    }

    /**
     * Get the default value.
     */
    public function getDefault()
    {
        return $this->_default;
    }

    /**
     * Set the haystack.
     *
     * @param Array
     */
    public function setHaystack(Array $haystack=null)
    {
        $this->_haystack = $haystack;
    }

    /**
     * Get the haystack.
     *
     * @return Array
     */
    public function getHaystack()
    {
        return $this->_haystack;
    }

    public function filter($value)
    {
        if (in_array($value, $this->_haystack)) {
            return $value;
        } else {
            return $this->_default;
        }
    }
}
?>
