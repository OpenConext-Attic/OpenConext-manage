<?php
/**
 * Abstract DataMapperClass
 *
 * @author marc
 */
abstract class Default_Model_Mapper_Abstract
{
    /**
     * Data Access Object to use in mapper
     * 
     * @var Zend_Db_Table
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
}