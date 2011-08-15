<?php
/**
 *
 */

class EngineBlock_Model_GroupProviderDecorator extends Default_Model_Abstract
{
    /**
     * Group Provider identifier
     *
     * @var String
     */
    public $group_provider_id;

    /**
     * Decorator identifier
     *
     * @var String
     */
    public $decorator_id;

    /**
     * Decorator Class Name
     *
     * @var String
     */
    public $decorator_class_name;

    /**
     * Decorator Search
     *
     * @var String
     */
    public $decorator_search;

    /**
     * Decorator Replace
     *
     * @var String
     */
    public $decorator_replace;
}
