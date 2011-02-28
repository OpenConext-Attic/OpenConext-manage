<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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
