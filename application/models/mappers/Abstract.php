<?php
/**
 * Abstract DataMapperClass
 *
 * @author marc
 */
abstract class Model_Mapper_Abstract
{
    /**
     * Data Access Object to use in mapper
     * 
     * @var <type> 
     */
    protected $_dao;

    /**
     *
     * @param String|Zend_Db_Table_Abstract
     * @return Void
     */
    public function __construct($dao)
    {
        $this->setDao($dao);
    }

    /**
     * Set the DAO to use
     *
     * If $dao is a string, instantiate an object,
     * or use the one provided.
     * Check if it is the correct type and use it.
     *
     * @param String|Zend_Db_Table_Abstract
     * @return Void
     */
    public function setDao($dao)
    {
        if (is_string($dao)) {
            $dao = new $dao();
        }
        if (!$dao instanceof Zend_Db_Table_Abstract) {
            throw new InvalidArgumentException('DAO is not correct type (Zend_Db_Table_Abstract)');
        }
        $this->_dao = $dao;
    }

    /**
     * Get the DAO
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getDao()
    {
        return $this->_dao;
    }

    /**
     * Delete
     *
     * Delete using the row object, this is needed for
     * cascading deletes.
     *
     * @param Integer $id
     */
    public function delete($id)
    {
        $select = $this>getDao()->select();
        $select->where('id = ?',$id);
        $row = $this->getDao()->fetchRow($select);
        if (null !== $row) {
            return $row->delete();
        } else {
            return 0;
        }
    }

    /**
     * Get all
     *
     * @return Array
     */
    public function fetchAll()
    {
        return $this->createObjectArray($this->getDao()->fetchAll());
    }

    public function fetchFiltered($where = null, $order = null, $count = null, $limit = null)
    {
        return $this->createObjectArray($this->getDao()->fetchAll($where, $order, $count, $limit));
    }

    abstract public function save($model);
    abstract public function find($id, $model = null);
    abstract protected function createObjectArray(Zend_Db_Table_Rowset_Abstract $rowSet);
}
