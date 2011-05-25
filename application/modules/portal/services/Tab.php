<?php
/**
 *
 */

class Portal_Service_Tab
{
    public function searchTeams(Surfnet_Search_Parameters $params)
    {
        if (!$params->getSortByField()) {
            $params->setSortByField('num');
            $params->setSortDirection('desc');
        }

        $dao = new Portal_Model_DbTable_Tab();

        /**
         * $qry = "SELECT COUNT(id) AS num, 'Total Tabs' as type FROM `coin_portal`.`tab`
         *  UNION
         * SELECT COUNT(id) AS num, 'Shared Team Tabs' as type FROM `coin_portal`.`tab` where team IS NOT NULL
         *  UNION
         * SELECT COUNT(id) AS num, 'Not Shared' as type FROM `coin_portal`.`tab` where team IS NULL";
         */
        $selectTotal = $dao->select()->from(
            $dao,
            array(
                 "num" => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'Total'")
            )
        );

        $selectShared = $dao->select()->from(
            $dao,
            array(
                 "num" => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'Shared'"))
            )
        ->where('team IS NOT NULL');

        $selectNotShared = $dao->select();
        $selectNotShared->from($dao,
                           array("num" => "COUNT(id)",
                                 'type' => new Zend_Db_Expr("'Not shared'"))
                          )
                          ->where('team IS NULL');

        $select = $dao->select()
            ->union(array(
                $selectTotal,
                $selectShared,
                $selectNotShared
            )
        );

        if ($params->getLimit()) {
            $select->limit($params->getLimit(), $params->getOffset());
        }

        $select->order($params->getSortByField() . ' ' . $params->getSortDirection());

        $rows = $dao->fetchAll($select)->toArray();

        return new Surfnet_Search_Results($params, $rows, 3);
    }
}