<?php
/**
 *
 */
class Portal_Service_Invite
{
    /**
     * 
     *
     * SELECT COUNT(id) AS num, status
     * FROM portal.invite
     * GROUP BY status
     *
     * @param Surfnet_Search_Parameters $params
     * @return Surfnet_Search_Results
     */
    public function searchCountByStatus(Surfnet_Search_Parameters $params)
    {
        $dao = new Portal_Model_DbTable_Invite();
        $select = $dao->select()->from($dao)->group('status');

        $select = $select->columns(
            array(
                "num" => "COUNT(id)",
                "status" => "status"
            )
        );
        if ($params->getLimit()) {
            $select->limit($params->getLimit(), $params->getOffset());
        }

        if ($params->getSortByField() != '') {
            $select->order($params->getSortByField() . ' ' . $params->getSortDirection());
        }

        $results = $dao->fetchAll($select)->toArray();
        
        $totalCount = $dao->fetchRow(
            $select->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $results, $totalCount);
    }
}