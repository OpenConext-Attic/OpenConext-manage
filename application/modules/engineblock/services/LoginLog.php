<?php

class EngineBlock_Service_LoginLog
{
    public function searchCountByType(Surfnet_Search_Parameters $params)
    {
        $dao = new EngineBlock_Model_DbTable_LogLogin();


        $selectTotal = $dao->select()->from(
            $dao,
            array('num' => 'COUNT(userid)')
        );

        $selectUnique = $dao->select()->from(
            $dao,
            array('num' => 'COUNT(DISTINCT(userid))')
        );

        if ($params->searchByDate()) {
            $searchParams = $params->getSearchParams();
            $dateWhere = $this->_getLoginDateWhere(
                $searchParams['year'],
                $searchParams['month']
            );
            $selectUnique->where($dateWhere);
            $selectTotal->where($dateWhere);
        }

        $rows = array(
            array(
                'total'  => (int)$dao->fetchRow($selectTotal)->num,
                'unique' => (int)$dao->fetchRow($selectUnique)->num,
            ),
        );

        return new Surfnet_Search_Results($params, $rows, 2);
    }

    public function searchCountByVo(Surfnet_Search_Parameters $params)
    {
        return $this->_searchCountGrouped('voname', $params);
    }

    public function searchCountByUseragent(Surfnet_Search_Parameters $params)
    {
        return $this->_searchCountGrouped('useragent', $params);
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
                 'num' => 'COUNT(*)',
                 'grouped' => $groupByField
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
        if ($params->searchByDate()) {
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
     * @todo This is insecure and should be removed, potential for SQL injection
     *
     * @param String $field
     * @param String $value
     * @return array
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
