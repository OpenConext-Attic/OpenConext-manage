<?php

class Surfnet_Search_Results
{
    protected $_parameters;

    protected $_results;

    protected $_totalCount;

    /**
     * @param Surfnet_Search_Parameters $parameters
     * @param mixed $results
     * @param int $totalCount
     */
    public function __construct(Surfnet_Search_Parameters $parameters, $results, $totalCount)
    {
        $this->_parameters  = $parameters;
        $this->_results     = $results;
        $this->_totalCount  = $totalCount;
    }

    public function getResults()
    {
        return $this->_results;
    }

    public function getResultCount()
    {
        return count($this->_results);
    }

    public function getTotalCount()
    {
        return $this->_totalCount;
    }

    public function getParameters()
    {
        return $this->_parameters;
    }
}