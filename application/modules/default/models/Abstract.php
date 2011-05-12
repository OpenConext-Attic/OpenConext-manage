<?php
/**
 *
 */

abstract class Default_Model_Abstract
{
    protected $_populateMapping = array();

    public function populate(array $row)
    {
        foreach ($row as $key=>$value) {
            // Convert underscores to camelCase
            $convertedKey = lcfirst(implode(array_map('ucfirst', explode('_', $key))));

            // First check for a populate method
            $customPopulateMethod = '_populate' . ucfirst($convertedKey);
            if (method_exists($this, $customPopulateMethod)) {
                $this->$customPopulateMethod($value);
                continue;
            }

            // Then for a property mapping for the original (database) key.
            if (isset($this->_populateMapping[$key])) {
                $mapping = $this->_populateMapping[$key];
                if (is_string($mapping)) {
                    $convertedKey = $mapping;
                }
                else if (is_array($mapping) && isset($mapping['type']) && isset($mapping['property'])) {
                    $populateMethod = '_populate' . ucfirst($mapping['type']);
                    $this->{$mapping['property']} = $this->$populateMethod($key, $value);
                }
            }

            // Then for a property mapping for the original (database) key.
            if (isset($this->_populateMapping[$key])) {
                $convertedKey = $this->_populateMapping[$key];
            }

            // Finally for a property.
            if (property_exists($this, $convertedKey)) {
                $this->$convertedKey = $value;
                continue;
            }
        }
    }

    protected function _populateBoolean($key, $value)
    {
        if ($value==='T') {
            $this->$key = TRUE;
        }
        else if ($value==='F' || !$value) { // 'F' or falsy like NULL
            $this->$key = FALSE;
        }
        else {
            throw new Exception("Unknown value '$value' for boolean");
        }
    }
}