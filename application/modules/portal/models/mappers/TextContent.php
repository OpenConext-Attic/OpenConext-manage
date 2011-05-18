<?php
/**
 *
 */

class Portal_Model_Mapper_TextContent
{
    /**
     * @var Portal_Model_DbTable_Abstract
     */
    protected $dao;

    public function __construct(Portal_Model_DbTable_Abstract $dao)
    {
        $this->_dao = $dao;
    }

    /**
     * @throws Exception
     * @param  $id
     * @return Portal_Model_GadgetDefinition
     */
    public function fetchById($id)
    {
        $rowsFound = $this->_dao->find($id);
        if ($rowsFound->count() < 1) {
            throw new Exception("TextContent with id '$id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple TextContents with id '$id' found?");
        }

        $row = $rowsFound->current();
        return $this->_mapRowToModel($row, new Portal_Model_TextContent());
    }
    
    public function save(Portal_Model_TextContent $model)
    {
        if (isset($model->id) && $model->id) {
            $row = $this->_dao->find($model->id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }
        $row = $this->_mapModelToRow($model, $row);
        return $row->save();
    }

    protected function _mapRowToModel($row, $model)
    {
        $model->id = $row['id'];
        $model->field = $row['field'];
        $model->text  = $row['text'];
        return $model;
    }

    protected function _mapModelToRow($model, $row)
    {
        $row['id'] = $model->id;
        $row['field'] = $model->field;
        $row['text'] = $model->text;
        return $row;
    }
}