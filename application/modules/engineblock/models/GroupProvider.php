<?php
/**
 *
 */

class EngineBlock_Model_GroupProvider extends Default_Model_Abstract
{
    /**
     * Group Provider Identifier
     *
     * @var String
     */
    public $group_provider_id;

    /**
     * Group Provider Type
     *
     * @var String
     */
    public $group_provider_type;

    /**
     * Group Provider Class Name
     *
     * @var String
     */
    public $class_name;

    /**
     * Group Provider Endpoint
     *
     * @var String
     */
    public $endpoint;

    /**
     * Group Provider Adapter
     *
     * @var String
     */
    public $adapter;

    /**
     * Group Provider Timeout
     *
     * @var int
     */
    public $timeout;

    /**
     * Group Provider preconditions
     *
     * @var Array
     */
    public $preconditions;

    /**
     * Group Provider Decorators
     *
     * @var Array
     */
    public $decorators;

    /**
     * Group Provider Group Filters
     *
     * @var Array
     */
    public $group_filters;

    /**
     * Group Provider Group Member Filters
     *
     * @var Array
     */
    public $group_member_filters;
}
