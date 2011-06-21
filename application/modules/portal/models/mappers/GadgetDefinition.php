<?php
/**
 *
 */

class Portal_Model_Mapper_GadgetDefinition
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
            throw new Exception("Gadgetdefinition with id '$id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple gadgetdefinitions with id '$id' found?");
        }

        $row = $rowsFound->current();
        $gadgetDefinition = new Portal_Model_GadgetDefinition();
        $this->_mapRowToGadgetDefinition($row, $gadgetDefinition);
        return $gadgetDefinition;
    }

    /**
     * Note that the 'approved' boolean field is unused
     * and the 'status' enum (0=PUBLISHED, 1=UNPUBLISHED, 2=TEST) is also unused.
     *
     * @param Portal_Model_GadgetDefinition $gadgetDefinition
     */
    public function save(Portal_Model_GadgetDefinition $gadgetDefinition)
    {
        if (isset($gadgetDefinition->id) && (int)$gadgetDefinition->id > 0) {
            $row = $this->_dao->find($gadgetDefinition->id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }
        $uniqueSelect = $this->_dao->select()->where('url = ?', $gadgetDefinition->url);
        if (isset($gadgetDefinition->id) && $gadgetDefinition->id) {
            $uniqueSelect->where('id <> ?', (int)$gadgetDefinition->id);
        }

        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        if (empty($duplicates)) {
            $row = $this->_mapGadgetDefinitionToRow($gadgetDefinition, $row);
            $row->save();
        }
        else {
            $gadgetDefinition->errors['url'][] = "A gadgetdefinition with this URL already exists";
        }

        return $gadgetDefinition;
    }

    protected function _mapRowToGadgetDefinition(Zend_Db_Table_Row_Abstract $row, Portal_Model_GadgetDefinition $gadgetDefinition)
    {
        $gadgetDefinition->id                      = $row['id'];
        $gadgetDefinition->url                     = $row['url'];
        $gadgetDefinition->title                   = $row['title'];
        $gadgetDefinition->description             = $row['description'];
        $gadgetDefinition->addedAt                 = $row['added'];
        $gadgetDefinition->authorName              = $row['author'];
        $gadgetDefinition->authorEmail             = $row['author_email'];
        $gadgetDefinition->screenShotUrl           = $row['screenshot'];
        $gadgetDefinition->supportsGroups          = $row['supports_groups']==='T'?true:false;
        $gadgetDefinition->supportsSingleSignOn    = $row['supportssso']==='T'?true:false;
        $gadgetDefinition->fixedTabGadget		   = $row['fixed_tab_gadget']==='T'?true:false;
        $gadgetDefinition->isCustom                = $row['custom_gadget']==='T'?true:false;
        $gadgetDefinition->thumbnailUrl            = $row['thumbnail'];
        $gadgetDefinition->installCount            = $row['install_count'];
        return $gadgetDefinition;
    }

    protected function _mapGadgetDefinitionToRow(Portal_Model_GadgetDefinition $gadgetDefinition, Zend_Db_Table_Row_Abstract $row)
    {
        $row['id']              = $gadgetDefinition->id;
        $row['url']             = $gadgetDefinition->url;
        $row['title']           = $gadgetDefinition->title;
        $row['description']     = $gadgetDefinition->description;
        $row['added']           = $gadgetDefinition->addedAt;
        $row['author']          = $gadgetDefinition->authorName;
        $row['author_email']    = $gadgetDefinition->authorEmail;
        $row['added']           = $gadgetDefinition->addedAt;
        $row['author']          = $gadgetDefinition->authorName;
        $row['screenshot']      = $gadgetDefinition->screenShotUrl;
        $row['supports_groups'] = ($gadgetDefinition->supportsGroups?'T':'F');
        $row['supportssso']     = ($gadgetDefinition->supportsSingleSignOn?'T':'F');
        $row['fixed_tab_gadget']= ($gadgetDefinition->fixedTabGadget?'T':'F');
        $row['custom_gadget']   = ($gadgetDefinition->isCustom?'T':'F');
        $row['thumbnail']       = $gadgetDefinition->thumbnailUrl;
        $row['approved']        = 'F';
        return $row;
    }
}
