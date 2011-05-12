<?php
/**
 *
 */

class Portal_Model_Tab extends Default_Model_Abstract
{
    /**
     * Is this a cloned tab?
     *
     * @var bool
     */
    public $isCloned;

    /**
     * Id for the tab
     *
     * @var int
     */
    public $id;

    /**
     * Tab name
     *
     * @var string
     */
    public $name;

    /**
     * Is a favorite (located in the menu, not in 'Other tabs')
     *
     * @var bool
     */
    public $favorite;

    /**
     * Order in the menu.
     *
     * @var int
     */
    public $order;

    /**
     * CollabPersonId for the owner
     *
     * @var string
     */
    public $ownerId;

    /**
     * URN for the team
     *
     * @var string
     */
    public $teamId;

    /**
     * Name of the team
     *
     * @var string
     */
    public $teamTitle;

    /**
     * Unix timestamp when the tab was created.
     *
     * @var int
     */
    public $createdAt;
}