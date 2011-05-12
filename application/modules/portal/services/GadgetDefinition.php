<?php
/**
 *
 */

class Portal_Service_GadgetDefinition
{
    public function searchCountByCapabililty(Surfnet_Search_Parameters $params)
    {
        if ($params->getSortByField()) {
            $params->setSortByField('num');
            $params->setSortDirection('desc');
        }

        $dao = new Portal_Model_DbTable_GadgetDefinition();
        
        $selectTotal = $dao->select();
        $selectTotal->from(
            $dao,
            array(
                 "num" => "COUNT(id)",
                'type' => new Zend_Db_Expr("'Totaal'"))
        );

        $selectGroupEnabled = $dao->select();
        $selectGroupEnabled->from(
            $dao,
            array(
                 "num" => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'Group enabled'"))
        )->where("UPPER(supports_groups) = 'T'");

        $selectSsoEnabled = $dao->select();
        $selectSsoEnabled->from(
            $dao,
            array(
                 'num'  => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'SSO Enabled'"))
        )->where("UPPER(supportssso) = 'T'");

        $selectSsoGroupEnabled = $dao->select();
        $selectSsoGroupEnabled->from(
            $dao,
            array(
                 "num" => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'SSO and Group Enabled'"))
        )->where("UPPER(supportssso) = 'T' AND upper(supports_groups) = 'T'");


        $select = $dao->select()
                ->union(array(
                    $selectTotal,
                    $selectSsoGroupEnabled,
                    $selectSsoEnabled,
                    $selectGroupEnabled
        ));

        if ($params->getLimit()) {
            $select->limit($params->getLimit(), $params->getOffset());
        }
        $select->order($params->getSortByField(). ' ' . $params->getSortDirection());

        $rows = $dao->fetchAll($select)->toArray();

       return new Surfnet_Search_Results($params, $rows, 4);
    }
    
    public function searchCustom(Surfnet_Search_Parameters $params)
    {
        return $this->_searchWhere($params, 'custom_gadget="T"');
    }

    public function searchNonCustom(Surfnet_Search_Parameters $params)
    {
        return $this->_searchWhere($params, 'custom_gadget="F"');
    }

    protected function _searchWhere($params, $where)
    {
        $dao = new Portal_Model_DbTable_GadgetDefinition();

        $query = $dao->select()->from($dao)
                    ->where($where);
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('gadgetdefinition.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $results = $dao->fetchAll($query)->toArray();

        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $results, $totalCount);
    }
}