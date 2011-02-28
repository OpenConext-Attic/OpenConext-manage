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
        $this->_filter = new Surfnet_Filter_InArray(
                                $this->_haystack,
                                $this->_default
                             );
    }

    /**
     * Test the constructor
     */
    public function testConstructor()
    {
        $dummyDefault = 'Dummy';
        $dummyHaystack = array(
                                  'Dummy1',
                                  'Dummy2'
                              );
        $dummyFilter = new Surfnet_Filter_InArray(
                               $dummyHaystack,
                               $dummyDefault
                           );
        $this->assertEquals(
                $dummyDefault,
                $dummyFilter->getDefault(),
                'Constructor did not process default correctly'

        );
        $this->assertEquals(
                $dummyHaystack,
                $dummyFilter->getHaystack(),
                'Constructor did not process haystack correctly'

        );

    }

    /**
     * Test the getter/setter combo.
     */
    public function testGetSetDefault()
    {
        $dummyDefault = 'Dummy';
        $this->_filter->setDefault($dummyDefault);
        $this->assertEquals(
                $dummyDefault,
                $this->_filter->getDefault(),
                'Default value did not get processed correctly'

        );
    }

    /**
     * Test the getr/set default combo.
     */
    public function testGetSetHaystack()
    {
        $dummyHaystack = array(
                                  'Dummy1',
                                  'Dummy2'
                              );
        $this->_filter->setHaystack($dummyHaystack);
        $this->assertEquals(
                $dummyHaystack,
                $this->_filter->getHaystack(),
                'Haystack did not get processed correctly'

        );
    }

    /**
     * Test for value in range
     */
    public function testValueInArray()
    {
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
        $input = 'NotInArray1';
        $this->assertEquals(
                $this->_default,
                $this->_filter->filter($input),
                'Value in array gets filtered out.'
        );
    }
}
?>
