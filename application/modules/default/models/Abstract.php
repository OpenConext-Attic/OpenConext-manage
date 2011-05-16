<?php
/**
 *
 */

abstract class Default_Model_Abstract
{
    public $errors = array();

    public function toArray()
    {
        $ret = array();
        foreach ($this as $propertyName => $propertyValue) {
            $ret[$propertyName] = $propertyValue;
        }
        return $ret;
    }

    public function populate(array $row)
    {
        foreach ($row as $key=>$value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }
}