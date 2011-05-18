<?php

class EngineBlock_Service_LoginLog
{
    public function searchCountByType(Surfnet_Search_Parameters $params)
    {
        $dao = new EngineBlock_Model_DbTable_LogLogin();
        /**
         *
         * $qry = "SELECT COUNT(userid) as num, 'Total Logins' as type FROM `engine_block`.`log_logins`
         *    UNION
         *   SELECT COUNT(DISTINCT(userid)) as num, 'Unique Logins' as type FROM `engine_block`.`log_logins`";
         */

        $selectTotal = $dao->select()->from($dao,
                      array("num" => "COUNT(userid)",
                            'type' => new Zend_Db_Expr("'Total Logins'"))
                     );
        $selectUnique = $dao->select()->from($dao,
                        array("num" => "COUNT(DISTINCT(userid))",
                              'type' => new Zend_Db_Expr("'Unique Logins'"))
                       );

        $select = $dao->select()
                ->union(array(
                    $selectTotal,
                    $selectUnique
                ));
        $rows = $dao->fetchAll($select)->toArray();

        return new Surfnet_Search_Results($params, $rows, 2);
    }

    public function searchCountByIdp(Surfnet_Search_Parameters $params)
    {
        return $this->_searchCountGrouped('idpentityid', $params);
    }

    public function searchCountBySp(Surfnet_Search_Parameters $params)
    {
        return $this->_searchCountGrouped('spentityid', $params);
    }

    protected function _searchCountGrouped($groupByField, Surfnet_Search_Parameters $params)
    {
        $dao = new EngineBlock_Model_DbTable_LogLogin();
        $select = $dao->select()->from(
            $dao,
            array(
                 "num" => "COUNT(*)",
                 "grouped" => $groupByField
            )
        )->group($groupByField);

        if ($params->getLimit()) {
            $select->limit($params->getLimit(), $params->getOffset());
        }

        if ($params->getSortByField() != '') {
            $select->order($params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $rows = $dao->fetchAll($select)->toArray();

        $select->reset(Zend_Db_Select::LIMIT_COUNT)
               ->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect = $dao->getAdapter()
            ->select()
            ->from($select)
            ->columns(array('count'=>'COUNT(*)'));
        $totalCount = $countSelect->query()->fetchObject()->count;

        return new Surfnet_Search_Results($params, $rows, $totalCount);
    }
}
