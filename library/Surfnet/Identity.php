<?php

/**
 * The Surfnet_Identity class is responsible for storing
 * the metadata of a user.
 *
 * Usually the metadata is provided by an external source
 * like an Identity Provider.
 *
 * @author marc
 */
class Surfnet_Identity
{
    /**
     * Display name to use in the interface for this user.
     * 
     * @var String
     */
    public $displayName;

    /**
     * Unique identifier for this identity
     *
     * @var mixed
     */
    public $id;

    /**
     * @param mixed $id Unique Identifier
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}
