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
        $searchParams = $params->getSearchParams();

        $selectUnique = $dao->select()->from($dao,
                        array("num" => "COUNT(DISTINCT(userid))",
                              'type' => new Zend_Db_Expr("'Unique Logins'"))
                       );

        if (!empty($searchParams['year']) && !empty($searchParams['month'])) {
            $dateWhere = $this->_getLoginDateWhere(
                $searchParams['year'],
                $searchParams['month']
            );
            $selectUnique->where($dateWhere);
            $selectTotal->where($dateWhere);
        }
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
        return $this->_searchCountGrouped('idpentityname', $params);
    }

    public function searchCountBySp(Surfnet_Search_Parameters $params)
    {
        return $this->_searchCountGrouped('spentityname', $params);
    }

    public function searchSpLoginsByIdp(Surfnet_Search_Parameters $params)
    {
        $params->addSearchParam('entity_field', 'spentityname');
        return $this->_searchCountGrouped('idpentityname', $params);
    }

    public function searchIdpLoginsBySp(Surfnet_Search_Parameters $params)
    {
        $params->addSearchParam('entity_field', 'idpentityname');
        return $this->_searchCountGrouped('spentityname', $params);
    }

    /**
     * Get logins grouped by type (IDP or SP)
     *
     * @param String $groupByField
     * @param Surfnet_Search_Parameters $params
     * @return Surfnet_Search_Results
     */
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
            $select->order($params->getSortByField()
                . ' ' . $params->getSortDirection());
        }

        $searchParams = $params->getSearchParams();
        if (!empty($searchParams['year']) && !empty($searchParams['month'])) {
            $select->where(
                $this->_getLoginDateWhere(
                    $searchParams['year'],
                    $searchParams['month']
                )
            );
        }
        if (isset($searchParams['entity_field'])
             && !empty($searchParams['entity_field'])) {
            $select->where(
                $this->_getEntityWhere(
                    $searchParams['entity_field'], $searchParams['entity_id']
                )
            );
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
    
    /**
     * Get the SQL selector for month
     *
     * @param <type> $year
     * @param <type> $month
     * @return String
     */
    protected function _getLoginDateWhere($year, $month) {
        $year = intval($year);
        $month = intval($month);
        return sprintf(
            '(YEAR(loginstamp) = %04u) AND (MONTH(loginstamp)= %02u)',
            $year,
            $month
        );
    }
    /**
     * Get the SQL selector for entity
     *
     * @param String $field
     * @param String $value
     */
    protected function _getEntityWhere($field, $value)
    {
        return sprintf(
            "(`%s` = '%s')",
            $field,
            $value
        );
    }

}
