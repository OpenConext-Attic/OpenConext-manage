<?php

class Model_Mapper_GadgetMapper extends Model_Mapper_Abstract
{
    public function save(Model_Abstract $model)
    {

    }

    /**
     *
     *
     * @param Model_Abstract $model
     */
    public function delete(Model_GadgetDefinition $model)
    {
        $this->setDao('Model_Dao_Gadget');
        $this->_dao->delete('definition=' . $model->id);

        $this->setDao('Model_Dao_GadgetDefinition');
        return $this->_dao->delete('id=' . $model->id);
    }

    /**
     * 
     */
    public function find($id, $model = null)
    {
        $this->setDao('Model_Dao_GadgetDefinition');

        $row = $this->_dao->fetchRow($this->_dao->select()->from($this->_dao, array('id', 'title', 'description'))->where('id='.$id));

        $gadgetDefinition = new Model_GadgetDefinition();
        $gadgetDefinition->id             = $row['id'];
        $gadgetDefinition->title          = $row['title'];
        $gadgetDefinition->description    = $row['description'];
        return $gadgetDefinition;
    }

    /**
     * @todo Make this actually do something.
     */
    protected function createObjectArray(Zend_Db_Table_Rowset_Abstract $rowSet)
    {
        $result = array();
        foreach ($rowSet as $row) {
            $gadget = new Model_GadgetDefinition();

            $gadget->id             = $row['id'];
            $gadget->title          = $row['title'];
            $gadget->description    = $row['description'];

            $result[] = $gadget;
        }
        return $result;
    }

    /**
     * Gets the usage per gadget
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the full dataset.
     *
     * @return Array|Integer Array with gadget usage data or row count.
     */
    public function fetchAll($order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        $this->setDao('Model_Dao_GadgetDefinition');

        $select = $this->_dao->select();
        if ($countOnly) {
            $fields = array('count' => 'COUNT(*)');
        } else {
            $fields = array(
                'id','title', 'author', 'added', 'description',
                'install_count', 'screenshot', 'url',
                'approved', 'supportssso', 'supports_groups'
            );
        }
        
        $select->from($this->_dao, $fields);
        
        if (isset($limit)) {
            $select->limit($limit, $offset);
        }
        
        if ($order != '' && !$countOnly) {
            $select->order($order . ' ' . $dir);
        }

        $rows = $this->_dao->fetchAll($select);
        
        if ($countOnly) {
            return $rows[0]['count'];
        }

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'id'                => $row['id'],
                'title'             => $row['title'],
                'author'            => $row['author'],
                'added'             => $row['added'],
                'description'       => $row['description'],
                'install_count'     => $row['install_count'],
                'screenshot'        => $row['screenshot'],
                'url'               => $row['url'],
                'approved'          => $row['approved'],
                'supportssso'       => $row['supportssso'],
                'supports_groups'   => $row['supports_groups']
            );
        }

        return $result;
    }

    public function fetchAllCustom($order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        $this->setDao('Model_Dao_GadgetDefinition');

        $select = $this->_dao->select();
        if ($countOnly) {
            $fields = array('count' => 'COUNT(*)');
        } else {
            $fields = array(
                'id','title', 'author', 'added', 'description',
                'install_count', 'screenshot', 'url',
                'approved', 'supportssso', 'supports_groups'
            );
        }

        $select->from($this->_dao, $fields);

        $select->where('custom_gadget=?', 'T');

        if (isset($limit)) {
            $select->limit($limit, $offset);
        }

        if ($order != '' && !$countOnly) {
            $select->order($order . ' ' . $dir);
        }

        $rows = $this->_dao->fetchAll($select);

        if ($countOnly) {
            return $rows[0]['count'];
        }

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'id'                => $row['id'],
                'title'             => $row['title'],
                'author'            => $row['author'],
                'added'             => $row['added'],
                'description'       => $row['description'],
                'install_count'     => $row['install_count'],
                'screenshot'        => $row['screenshot'],
                'url'               => $row['url'],
                'approved'          => $row['approved'],
                'supportssso'       => $row['supportssso'],
                'supports_groups'   => $row['supports_groups']
            );
        }

        return $result;
    }

    public function fetchAllNonCustom($order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        $this->setDao('Model_Dao_GadgetDefinition');

        $select = $this->_dao->select();
        if ($countOnly) {
            $fields = array('count' => 'COUNT(*)');
        } else {
            $fields = array(
                'id','title', 'author', 'added', 'description',
                'install_count', 'screenshot', 'url',
                'approved', 'supportssso', 'supports_groups'
            );
        }

        $select->from($this->_dao, $fields);

        $select->where('custom_gadget=?', 'F');

        if (isset($limit)) {
            $select->limit($limit, $offset);
        }

        if ($order != '' && !$countOnly) {
            $select->order($order . ' ' . $dir);
        }

        $rows = $this->_dao->fetchAll($select);

        if ($countOnly) {
            return $rows[0]['count'];
        }

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'id'                => $row['id'],
                'title'             => $row['title'],
                'author'            => $row['author'],
                'added'             => $row['added'],
                'description'       => $row['description'],
                'install_count'     => $row['install_count'],
                'screenshot'        => $row['screenshot'],
                'url'               => $row['url'],
                'approved'          => $row['approved'],
                'supportssso'       => $row['supportssso'],
                'supports_groups'   => $row['supports_groups']
            );
        }

        return $result;
    }

    /**
     * Gets the counts for different types of
     *
     * @return Array Array with gagdet count data
     */
    public function fetchCount($order='type', $dir='desc', $limit=null, $offset=0, $countOnly=false)
    {
        if (empty($order)) {
            $order = 'num';
        }

        if (empty($dir)) {
            $dir = 'desc';
        }

        $this->setDao('Model_Dao_GadgetDefinition');
        
        $selectTotal = $this->_dao->select();
        $selectTotal->from(
            $this->_dao,
            array(
                 "num" => "COUNT(id)",
                'type' => new Zend_Db_Expr("'Totaal'"))
        );

        $selectGroupEnabled = $this->_dao->select();
        $selectGroupEnabled->from(
            $this->_dao,
            array(
                 "num" => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'Group enabled'"))
        )->where("UPPER(supports_groups) = 'T'");

        $selectSsoEnabled = $this->_dao->select();
        $selectSsoEnabled->from(
            $this->_dao,
            array(
                 'num'  => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'SSO Enabled'"))
        )->where("UPPER(supportssso) = 'T'");

        $selectSsoGroupEnabled = $this->_dao->select();
        $selectSsoGroupEnabled->from(
            $this->_dao,
            array(
                 "num" => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'SSO and Group Enabled'"))
        )->where("UPPER(supportssso) = 'T' AND upper(supports_groups) = 'T'");


        $select = $this->_dao->select()
                ->union(array(
                    $selectTotal,
                    $selectSsoGroupEnabled,
                    $selectSsoEnabled,
                    $selectGroupEnabled
        ));

        if ($order != '' && !$countOnly) {
            $select->order($order
                           . (empty($dir) ? '' : ' ')
                           . $dir
                          );
        }

        $rows = $this->_dao->fetchAll($select);

        if ($countOnly) {
            return count($rows);
        }
        
        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'num' => $row['num'],
                'type' => $row['type']
            );
        }
        return $result;
    }

    /**
     * Gets the usage per gadget
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     *
     * @return Array|Integer Array with gagdet usage data or row count.
     */
    public function fetchUsage($order='num', $dir='desc', $limit=null, $offset=0, $countOnly=false)
    {
        if (empty($order)) {
            $order = 'num';
        }

        if (empty($dir)) {
            $dir = 'desc';
        }

        $db = $this->_dao->getAdapter();
        $select = $db->select();

        $select->from(array('g' => 'gadget'),
                      array('definition'))
                ->join(array('gd' => 'gadgetdefinition'),
                       'g.definition = gd.id',
                        array('num' => 'count(gd.id)',
                              'title' => 'gd.title',
                              'author' => 'gd.author'))
                ->join(array('t' => 'tab'),
                       'g.tab_id = t.id')
                ->group('g.definition');

        if (isset($limit) && !$countOnly) {
            $select->limit($limit, $offset);
        }

        if ($order != '' && !$countOnly) {
            $select->order($order 
                           . (empty($dir) ? '' : ' ')
                           . $dir
                          );
        }

        $rows = $db->fetchAll($select);

        if ($countOnly) {
            return count($rows);
        }

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'num' => $row['num'],
                'title' => $row['title'],
                'author' => $row['author']
            );
        }
        return $result;
    }

    public function fetchInvites($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        $this->setDao('Model_Dao_Invite');

        /**
         * $qry = "select COUNT(id) as num, status from coin_portal.invite group by status";
         */
        $select = $this->_dao->select();
        $select->from($this->_dao,
                      array("num" => "COUNT(id)",
                            "status" => "status"))
               ->group("status");

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
                'status' => $row['status'],
            );
        }
        return $result;
    }

    public function fetchTeamTabs($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        if (empty($order)) {
            $order = 'num';
        }

        if (empty($dir)) {
            $dir = 'desc';
        }

        /**
         * $qry = "SELECT COUNT(id) AS num, 'Total Tabs' as type FROM `coin_portal`.`tab`
         *  UNION
         * SELECT COUNT(id) AS num, 'Shared Team Tabs' as type FROM `coin_portal`.`tab` where team IS NOT NULL
         *  UNION
         * SELECT COUNT(id) AS num, 'Not Shared' as type FROM `coin_portal`.`tab` where team IS NULL";
         */
        $this->setDao('Model_Dao_Tab');
        $selectTotal = $this->_dao->select();
        $selectTotal->from(
            $this->_dao,
            array(
                 "num" => "COUNT(id)",
                 'type' => new Zend_Db_Expr("'Totaal'")
            )
        );

        $selectShared = $this->_dao->select();
        $selectShared->from($this->_dao,
                        array("num" => "COUNT(id)",
                              'type' => new Zend_Db_Expr("'Gedeeld'"))
                       )
                      ->where('team IS NOT NULL');

        $selectNotShared = $this->_dao->select();
        $selectNotShared->from($this->_dao,
                           array("num" => "COUNT(id)",
                                 'type' => new Zend_Db_Expr("'Niet gedeeld'"))
                          )
                          ->where('team IS NULL');

        $select = $this->_dao->select()
                ->union(array(
                    $selectTotal,
                    $selectShared,
                    $selectNotShared
                ));

        if (isset($limit) && !$countOnly) {
            $select->limit($limit, $offset);
        }

        if ($order != '' && !$countOnly) {
            $select->order($order
                           . (empty($dir) ? '' : ' ')
                           . $dir
            );
        }

        $rows = $this->_dao->fetchAll(
            $select
        );

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'num'  => $row['num'],
                'type' => $row['type']
            );
        }
        return $result;
    }
}