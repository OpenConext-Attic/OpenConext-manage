<?php
/**
 *
 */

class EngineBlock_Model_GroupProviderGroupFilter extends Default_Model_Abstract
{
    /**
     * Group Provider Identifier
     *
     * @var String
     */
    public $group_provider_id;

    /**
     * Group Filter Identifier
     * @var String
     */
    public $group_filter_id;

    /**
     * Group Filter Class Name
     *
     * @var String
     */
    public $group_filter_class_name;

    /**
     * Group Filter Property
     *
     * @var String
     */
    public $group_filter_property;

    /**
     * Group Filter Search
     *
     * @var String
     */
    public $group_filter_search;

    /**
     * Group Filter Replace
     *
     * @var String
     */
    public $group_filter_replace;
}
