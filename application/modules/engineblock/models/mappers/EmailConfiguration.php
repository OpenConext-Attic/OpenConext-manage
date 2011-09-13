<?php
/**
 *
 */

class EngineBlock_Model_Mapper_EmailConfiguration
{
    /**
     * @var EngineBlock_Model_DbTable_Abstract
     */
    protected $dao;

    public function __construct(EngineBlock_Model_DbTable_Abstract $dao)
    {
        $this->_dao = $dao;
    }

    /**
     * @throws Exception
     * @param  $id
     * @return EmailConfiguration
     */
    public function fetchById($id)
    {
        $rowsFound = $this->_dao->find($id);
        if ($rowsFound->count() < 1) {
            throw new Exception("EmailConfiguration with id '$id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple EmailConfiguration with id '$id' found?");
        }

        $row = $rowsFound->current();
        return $this->_mapRowToModel($row, new EngineBlock_Model_EmailConfiguration());
    }
    
    public function save(EngineBlock_Model_EmailConfiguration $model)
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
        $model->email_type = $row['email_type'];
        $model->email_text  = $row['email_text'];
        $model->email_from  = $row['email_from'];
        $model->email_subject  = $row['email_subject'];
        $model->is_html  = $row['is_html'];
        return $model;
    }

    protected function _mapModelToRow($model, $row)
    {
        $row['id'] = $model->id;
        $row['email_type'] = $model->email_type;
        $row['email_text'] = $model->email_text;
        $row['email_from'] = $model->email_from;
        $row['email_subject'] = $model->email_subject;
        $row['is_html'] = $model->is_html;
        return $row;
    }
}