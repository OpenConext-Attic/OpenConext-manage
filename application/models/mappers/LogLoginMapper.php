<?php
/**
 * Log Login datamapper
 *
 * @author marc
 */
class Model_Mapper_LogLoginMapper extends Model_Mapper_Abstract
{
    /**
     *
     * @todo Make this actually do something.
     * @param Model_Abstract $model
     */
    public function save($model)
    {

    }

    /**
     * @todo Make this actually do something.
     */
    public function find($id, $model = null)
    {

    }

    /**
     * @todo Make this actually do something.
     */
    protected function createObjectArray(Zend_Db_Table_Rowset_Abstract $rowset)
    {
    }

    /**
     * Gets the usage per gadget
     *
     * @param String  $grouped   Column to group by.
     * @param String  $order     Column to order on.
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     *
     * @return Array|Integer Array with gagdet usage data or row count.
     */
    public function fetchGrouped($grouped='', $order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {

        $select = $this->_dao->select();
        $select->from($this->_dao,
                      array("num" => "COUNT(id)",
                            "grouped" => $grouped))
               ->group($grouped);

        if (isset($limit)) {
            $select->limit($limit, $offset);
        }

        if ($order != '' && !$countOnly) {
            $select->order($order . ' ' . $dir);
        }

        $rows = $this->_dao->fetchAll($select);

        if ($countOnly) {
            return count($rows);
        }

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'num' => $row['num'],
                'grouped' => $row['grouped'],
            );
        }
        return $result;
    }

    public function fetchCount()
    {
        /**
         *
         * $qry = "SELECT COUNT(userid) as num, 'Total Logins' as type FROM `engine_block`.`log_logins`
         *    UNION
         *   SELECT COUNT(DISTINCT(userid)) as num, 'Unique Logins' as type FROM `engine_block`.`log_logins`";
         */

        $selectTotal = $this->_dao->select();
        $selectTotal->from($this->_dao,
                      array("num" => "COUNT(userid)",
                            'type' => new Zend_Db_Expr("'Total Logins'"))
                     );
        $selectUnique = $this->_dao->select();
        $selectUnique->from($this->_dao,
                        array("num" => "COUNT(DISTINCT(userid))",
                              'type' => new Zend_Db_Expr("'Unique Logins'"))
                       );

        $select = $this->_dao->select()
                ->union(array(
                    $selectTotal,
                    $selectUnique
                ));
        $rows = $this->_dao->fetchAll(
            $select
        );

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'num' => $row['num'],
                'type' => $row['type']
            );
        }
        return $result;
    }

}