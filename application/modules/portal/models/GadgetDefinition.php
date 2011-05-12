<?php

class Portal_Model_GadgetDefinition extends Default_Model_Abstract
{
    /**
     * Gadget identifier
     *
     * @var Integer
     */
    public $id;

    /**
     * Unix timestamp of when the gadget definition was added.
     *
     * @var int
     */
    public $addedAt;

    /**
     * @var bool
     */
    public $isApproved;

    /**
     * Name of the author
     *
     * @var string
     */
    public $authorName;

    /**
     * E-mail for the Author.
     *
     * @var string
     */
    public $authorEmail;

    /**
     * Gadget title
     * @var String
     */
    public $title;

    /**
     * Gadget description
     * @var String
     */
    public $description;

    /**
     * Number of times this definition had been installed / used
     *
     * @var int
     */
    public $installCount;

    /**
     * URL to a screen shot of the gadget.
     *
     * @var string
     */
    public $screenShotUrl;

    /**
     * Does this gadget support external Group providers?
     *
     * @var bool
     */
    public $supportsGroups;

    /**
     * Does this gadget support Signle Sign On?
     *
     * @var bool
     */
    public $supportsSingleSignOn;

    /**
     * URL to a thumbnail to use for the gadget
     *
     * @var string
     */
    public $thumbnailUrl;

    /**
     * URL for the Gadget XML.
     *
     * @var string
     */
    public $url;

    /**
     * ??
     *
     * @var int
     */
    public $status;

    /**
     * Is a user added gadget
     *
     * @var bool
     */
    public $isCustom;

    protected $_populateMapping = array(
        'added'    => 'addedAt',
        'approved'  => array(
            'property'  => 'isApproved',
            'type'      => 'boolean',
        ),
        'author'   => 'authorName',
        'screenshot'=>'screenShotUrl',
        'supports_groups' => array(
            'property'  => 'supportsGroups',
            'type'      => 'boolean',
        ),
        'supportssso'=>array(
            'property'  => 'supportsSingleSignOn',
            'type'      => 'boolean',
        ),
        'custom_gadget' => array(
            'property'  => 'isCustom',
            'type'      => 'boolean',
        ),
        'thumbnail' => 'thumbnailUrl',
    );
}