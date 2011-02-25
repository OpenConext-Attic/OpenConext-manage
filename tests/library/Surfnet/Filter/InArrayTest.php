<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('Surfnet/Filter/InArray.php');

/**
 * Description of InArrayTest
 *
 * @author marc
 */
class Surfnet_Filter_InArrayTest extends PHPUnit_Framework_TestCase
{
    /**
     * Filter to test.
     *
     * @var Surfnet_Filter_InArray
     */
    protected $_filter;

    /**
     * Standard default value for tests
     *
     * @var Mixed
     */
    protected $_default = '__DEFAULT__';


    protected $_haystack = array(
                                    'InArray1',
                                    'InArray2',
                                    'InArray3'
                                );
    public function setUp()
    {
        $this->_filter = new Surfnet_Filter_InArray();
    }

    /**
     * Test the getter/setter combo.
     */
    public function testGetSetDefault()
    {
        $this->_filter->setDefault($this->_default);
        $this->assertEquals(
                $this->_default,
                $this->_filter->getDefault(),
                'Default value did not get processed correctly'

        );
    }

    /**
     * Test the getr/set default combo.
     */
    public function testGetSetHaystack()
    {
        $this->_filter->setHaystack($this->_haystack);
        $this->assertEquals(
                $this->_haystack,
                $this->_filter->getHaystack(),
                'Haystack did not get processed correctly'

        );
    }

    /**
     * Test for value in range
     */
    public function testValueInArray()
    {
        $this->_filter->setDefault($this->_default);
        $this->_filter->setHaystack($this->_haystack);
        $input = 'InArray1';
        $this->assertEquals(
                $input,
                $this->_filter->filter($input),
                'Value in array gets filtered out.'
        );
    }

    /**
     * Test for value not in range
     */
    public function testValueNotInArray()
    {
        $this->_filter->setDefault($this->_default);
        $this->_filter->setHaystack($this->_haystack);
        $input = 'NotInArray1';
        $this->assertEquals(
                $this->_default,
                $this->_filter->filter($input),
                'Value in array gets filtered out.'
        );
    }
}
?>
