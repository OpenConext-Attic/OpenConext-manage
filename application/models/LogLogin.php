<?php
/**
 * Login log model
 *
 * @author marc
 */
class Model_LogLogin extends Model_Abstract
{

    public function __construct()
    {
        $mapper = new Model_Mapper_LogLoginMapper('Model_Dao_LogLogin');
        $this->setMapper($mapper);
    }

    /**
     * Get a list of the amount of available gadgets
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     */
    public function getByIdp($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchGrouped('idpentityid', $order, $dir, $limit, $offset, $countOnly);
    }

    public function getBySP($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        return $this->getMapper()->fetchGrouped('spentityid', $order, $dir, $limit, $offset, $countOnly);
    }

    public function getCount()
    {
        return $this->getMapper()->fetchCount();
    }
}
