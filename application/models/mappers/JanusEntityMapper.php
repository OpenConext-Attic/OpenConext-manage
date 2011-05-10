<?php
/**
 * Log Login datamapper
 *
 * @author marc
 */
class Model_Mapper_JanusEntityMapper extends Model_Mapper_Abstract
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
    protected function createObjectArray(Zend_Db_Table_Rowset_Abstract $rowSet)
    {
    }

    /**
     * Gets the available Idps
     *
     * @param String  $order     Column to order on.
     * @param String  $dir
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     *
     * @return Array|Integer Array with gagdet usage data or row count.
     */
    public function fetchAvailableType($type='saml20-idp', $order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        $db = $this->_dao->getAdapter();

        $rev_select = $db->select();

        $rev_fields = array(
            'eid' => 'eid',
            'maxrev' => 'max(revisionid)',
        );

        $rev_select->from(
                           array('janus__entity'),
                           $rev_fields
                         )
                   ->group('eid');

        $fields = array(
            'entityid' => 'entityid',
            'state' => 'state',
            'metadataurl' => 'metadataurl',
            'created' => 'created',
            'user' => 'user'
        );

        $entityType = 'idp';
        if ($type==='saml20-sp') {
            $entityType = 'sp';
        }
        
        $select = $db->select();
        $select->from(
                       array('ent' => 'janus__entity'),
                       $fields
                     )
                ->joinInner(
                             array('entgrp' => $rev_select),
                             'ent.eid = entgrp.eid and ent.revisionid = entgrp.maxrev'
                           )
                ->join(
                        array('jm' => 'janus__metadata'),
                        '(ent.eid=jm.eid AND jm.revisionid=entgrp.maxrev)',
                        array($entityType=>'value', 'key' =>'key')
                      )
                ->join(
                        array('ju' => 'janus__user'),
                        '(ent.user=ju.uid)',
                        array('userid'=>'userid')
                      )
                ->where('ent.type= ?',$type)
                ->where('jm.key=?', 'name:nl');

        if (isset($limit)) {
            $select->limit($limit, $offset);
        }

        if ($order != '' && !$countOnly) {
            $select->order($order . ' ' . $dir);
        }

        $rows = $db->fetchAll($select);

        if ($countOnly) {
            return count($rows);
        }
        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'valid'  => $row['entityid'],
                $entityType => $row[$entityType],
                'state' => $row['state'],
                'metadataurl' => $row['metadataurl'],
                'created' => $row['created'],
                'userid' => $row['userid'],
            );
        }
        return $result;
    }
    /**
     * Gets the available Idps
     *
     * @param String  $order     Column to order on.
     * @param String  $dir
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     *
     * @return Array|Integer Array with gagdet usage data or row count.
     */
    public function fetchIdpAndSpCount($order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
        /*
         $qry = "SELECT COUNT(DISTINCT(entityid)) AS num, 'Identity Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-idp'
         UNION SELECT COUNT(DISTINCT(entityid)) AS num, 'Service Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-sp'";
         */
        $selectIdp = $this->_dao->select();
        $selectIdp->from($this->_dao,
                         array("num" => "COUNT(DISTINCT(entityid))",
                              'type' => new Zend_Db_Expr("'Identity Provider'"))
                        )
                  ->where('type = ?', 'saml20-idp');
        $selectSp = $this->_dao->select();
        $selectSp->from($this->_dao,
                        array("num" => "COUNT(DISTINCT(entityid))",
                              'type' => new Zend_Db_Expr("'Service Provider'"))
                       )
                 ->where('type = ?', 'saml20-sp');

        $select = $this->_dao->select()
                ->union(array(
                    $selectIdp,
                    $selectSp
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
