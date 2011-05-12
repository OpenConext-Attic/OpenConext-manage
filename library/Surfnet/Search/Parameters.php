<?php
/**
 *
 */

class Surfnet_Search_Parameters
{
    const SORT_DIRECTION_ASC = 'asc';
    const SORT_DIRECTION_DESC = 'desc';

    protected $_limit;

    protected $_offset = 0;

    protected $_sortField;

    protected $_sortDirection = self::SORT_DIRECTION_ASC;

    protected function __construct()
    {
    }

    /**
     * @return Surfnet_Search_Parameters
     */
    public static function create()
    {
        return new self();
    }

    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @return Surfnet_Search_Parameters
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @return Surfnet_Search_Parameters
     */
    public function setOffset($offset)
    {
        $this->_offset = (int)$offset;
        return $this;
    }

    public function getSortByField()
    {
        return $this->_sortField;
    }

    /**
     * @return Surfnet_Search_Parameters
     */
    public function setSortByField($sortField)
    {
        $this->_sortField = $sortField;
        return $this;
    }

    public function getSortDirection()
    {
        return $this->_sortDirection;
    }

    /**
     * @return Surfnet_Search_Parameters
     */
    public function setSortDirection($direction)
    {
        if (!in_array($direction, array(self::SORT_DIRECTION_ASC, self::SORT_DIRECTION_DESC))) {
            throw new Exception("Unrecognized sort direction");
        }

        $this->_sortDirection = $direction;
        return $this;
    }
}