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
    public $installCount = 0;

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
    public $supportsGroups = false;

    /**
     * Does this gadget support Signle Sign On?
     *
     * @var bool
     */
    public $supportsSingleSignOn = false;

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
     * Is a user added gadget
     *
     * @var bool
     */
    public $isCustom;

    public function __construct()
    {
        $this->addedAt = date('Y-m-d H:i:s');
    }
}
