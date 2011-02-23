<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GadgetList
 *
 * @author marc
 */
class Model_GadgetList extends Model_Abstract
{
    /**
     * The gadgets in the list
     *
     * @var Array
     */
    protected $_gadgets;

    public function __construct()
    {
        $mapper = new Model_Mapper_GadgetMapper('Model_Dao_Gadget');
        $this->setMapper($mapper);
    }

    /**
     * Get a list of gadgets
     *
     * @return Array
     */
    public function getList()
    {
        $this->populate($this->getMapper()->fetchAll());
        return $this->_gadgets;
    }

    /**
     * Get a list of the amount of gadgets in certain classes.
     * SSO enabled, Group enabled etc.
     */
    public function getCount()
    {
       return $this->getMapper()->fetchCount();
    }

    /**
     * Get a list of the amount of available gadgets
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     */
    public function getAvailable($limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchAvailable($limit, $offset, $countOnly);
    }

    /**
     * Get a list of the amount of gadget usage
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     */
    public function getUsage($limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchUsage($limit, $offset, $countOnly);
    }

    /**
     * Populate the gadget list.
     *
     * @param array $gadgets
     */
    public function populate(Array $gadgets)
    {
        $this->_gadgets = $gadgets;
    }
}
?>
