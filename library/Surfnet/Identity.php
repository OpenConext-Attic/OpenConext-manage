<?php
/* 
 */

/**
 * The Surfnet_Identity class is responsible for storing
 * the metadata of a
 *
 * Usually the metadata is provided by an external source
 * like an IdP.
 *
 * @author marc
 */
class Surfnet_Identity
{
    /**
     * 
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
     *
     * @param mixed $id Unique Identifier
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

}
