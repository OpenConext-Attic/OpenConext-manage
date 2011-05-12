<?php

class Portal_Model_JanusEntity extends Model_Abstract
{

    public function __construct()
    {
        $mapper = new Portal_Model_Mapper_JanusEntityMapper('Model_Dao_JanusEntity');
        $this->setMapper($mapper);
    }

    /**
     * Get a list of the available IdPs
     *
     * @param String  $order
     * @param String  $dir
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     */
    public function getAvailableIdps($order='eid', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchAvailableType('saml20-idp',$order, $dir, $limit, $offset, $countOnly);
    }

    public function getAvailableSps($order='eid', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchAvailableType('saml20-sp',$order, $dir, $limit, $offset, $countOnly);
    }

    public function getIdpAndSpCount($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        return $this->getMapper()->fetchIdpAndSpCount($order, $dir, $limit, $offset, $countOnly);
    }
}
