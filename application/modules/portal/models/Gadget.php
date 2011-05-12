<?php
/**
 *
 */

class Portal_Model_Gadget extends Default_Model_Abstract
{
    const COLUMN_LEFT = 0;
    const COLUMN_CENTER = 1;
    const COLUMN_RIGHT = 2;

    /**
     * Gadget identifier
     *
     * @var int
     */
    public $id;

    /**
     * Column on the tab (can be either 0 = left, 1 = center, or 2 = right)
     *
     * @var int
     */
    public $column;

    /**
     * Order on the tab.
     *
     * @var int
     */
    public $order;

    /**
     * @var bool
     */
    public $permitted;

    /**
     * Array with gadget settings.
     *
     * Most settings are specific to the gadget, except 'groupContext', which is added by the portal
     * to specify what group the gadget should use.
     *
     * @var array
     */
    public $preferences = array();

    /**
     * Id for the tab the gadget is located on.
     *
     * @var int
     */
    public $tabId;

    /**
     * Id for the definition of the gadget.
     *
     * @var int
     */
    public $gadgetDefinitionId;

    /**
     * Instance of this gadgets definition
     *
     * @var Portal_Model_GadgetDefinition
     */
    public $gadgetDefinition;

    /**
     * Unix timestamp for when the gadget was created
     *
     * @var int
     */
    public $createdAt;

    protected $_populateMapping = array(
        'gadget_column'     => 'column',
        'gadget_order'      => 'order',
        'has_permission'    => array(
            'property'  => 'permitted',
            'type'      => 'boolean',
        ),
        'definition'        => 'gadgetDefinitionId',
        'creation_timestamp'=> 'createdAt',
    );

    protected function _populatePrefs($prefString)
    {
        $preferences = explode('&', $prefString);
        foreach ($preferences as $preference) {
            list($name, $value) = explode('=', $preference);
            $this->preferences[$name] = urldecode($value);
        }
    }
}