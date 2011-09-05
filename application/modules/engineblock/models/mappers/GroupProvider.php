<?php
/**
 *
 */

class EngineBlock_Model_Mapper_GroupProvider
{
    /**
     * @var $_gpTable EngineBlock_ModelDbTable_Abstract
     */
    protected $_gpTable;

    public function __construct(EngineBlock_Model_DbTable_Abstract $gpTable)
    {
        $this->_gpTable = $gpTable;
    }

    public function fetchById($id)
    {
        // select
        $rowsFound = $this->_gpTable->find($id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider with id '$id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Providers with id '$id' found?");
        }
        $row = $rowsFound->current();
        
        //fetch  options
        $options = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderOption')->toArray();
        
        // fetch preconditions
        $preconditions = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderPrecondition')->toArray();
        // fetch precondition options (using query)
        $dao = new EngineBlock_Model_DbTable_GroupProviderPreconditionOption();
        foreach ($preconditions as &$p) {
            $p['options'] = $dao->fetchAll("group_provider_precondition_id = ".$p['id'])->toArray();
        }
        unset($p);

        // fetch decorators
        $decorators = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderDecorator')->toArray();
        // fetch decorator options (using query)
        $dao = new EngineBlock_Model_DbTable_GroupProviderDecoratorOption();
        foreach ($decorators as &$d) {
            $d['options'] = $dao->fetchAll("group_provider_decorator_id = ".$d['id'])->toArray();
        }
        unset($d);

        // fetch filters
        $filters = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderFilter')->toArray();
        // fetch filter options (using query)
        $dao = new EngineBlock_Model_DbTable_GroupProviderFilterOption();
        foreach ($filters as &$f) {
            $f['options'] = $dao->fetchAll("group_provider_filter_id = ".$f['id'])->toArray();
        }
        unset($f);        
        
        // create model
        $groupProvider = new EngineBlock_Model_GroupProvider();
        $this->_mapRowToGroupProvider($row, $options, $groupProvider);        

        // add preconditions, decorators and filters
        $this->_mapPreconditionsToGroupProvider($preconditions, $groupProvider);
        $this->_mapDecoratorsToGroupProvider($decorators, $groupProvider);
        $this->_mapFiltersToGroupProvider($filters, $groupProvider);

        return $groupProvider;
    }

    protected function _mapPreconditionsToGroupProvider(Array $preconditions, $groupProvider) {
        // translate preconditions to model properties
        foreach ($preconditions as $p) {
            switch ($p['type']) {
                case "EngineBlock_Group_Provider_Precondition_UserId_PregMatch":
                    $groupProvider->user_id_match = 'on';
                    if (is_array($p['options'])) {
                        foreach($p['options'] as $o) {
                            if ($o['name'] == 'search') {
                                $groupProvider->user_id_match_search = $o['value'];
                            }
                        }
                    }
                    break;
            }
        }
        return $groupProvider;
    }

    protected function _mapDecoratorsToGroupProvider(Array $decorators, $groupProvider) {
        foreach ($decorators as $d) {
            switch ($d['classname']) {
                case "EngineBlock_Group_Provider_Decorator_GroupIdReplace":
                    $groupProvider->modify_group_id = 'on';
                    if (is_array($d['options'])) {
                        foreach($d['options'] as $o) {
                            if ($o['name'] == 'search') {
                                $groupProvider->modify_group_id_search = $o['value'];
                            }
                            if ($o['name'] == 'replace') {
                                $groupProvider->modify_group_id_replace = $o['value'];
                            }
                        }
                    }
                    break;                
                case "EngineBlock_Group_Provider_Decorator_UserIdReplace":
                    $groupProvider->modify_user_id = 'on';
                    if (is_array($d['options'])) {
                        foreach($d['options'] as $o) {
                            if ($o['name'] == 'search') {
                                $groupProvider->modify_user_id_search = $o['value'];
                            }
                            if ($o['name'] == 'replace') {
                                $groupProvider->modify_user_id_replace = $o['value'];
                            }
                        }
                    }
                    break;                
            }
        }
        return $groupProvider;
    }    
    
    protected function _mapFiltersToGroupProvider(Array $filters, EngineBlock_Model_GroupProvider $groupProvider) {
        foreach ($filters as $f) {
            switch ($f['type']) {
                case "group":
                    $groupProvider->modify_group = 'on';
                    $rule = array();
                    if (is_array($f['options'])) {
                        foreach($f['options'] as $o) {
                            if ($o['name'] == 'property') {
                                $rule['property'] = $o['value'];
                            }
                            if ($o['name'] == 'search') {
                                $rule['search'] = $o['value'];
                            }
                            if ($o['name'] == 'replace') {
                                $rule['replace'] = $o['value'];
                            }
                        }
                    }
                    // all options are obligatory
                    if (count($rule) == 3) {
                        $groupProvider->modify_group_rule[] = $rule;
                    }
                    break;                
                case "groupMember":
                    $groupProvider->modify_user = 'on';
                    $rule = array();
                    if (is_array($f['options'])) {
                        foreach($f['options'] as $o) {
                            if ($o['name'] == 'property') {
                                $rule['property'] = $o['value'];
                            }
                            if ($o['name'] == 'search') {
                                $rule['search'] = $o['value'];
                            }
                            if ($o['name'] == 'replace') {
                                $rule['replace'] = $o['value'];
                            }
                        }
                    }
                    // all options are obligatory
                    if (count($rule) == 3) $groupProvider->modify_user_rule[] = $rule;
                    break;                
            }
        }
        // no sense in checking the box if there are no rules
        if (count($groupProvider->modify_group_rule) == 0) {
            $groupProvider->modify_group = null;
        }
        if (count($groupProvider->modify_user_rule) == 0) {
            $groupProvider->modify_user = null;
        }
        // done
        return $groupProvider;
    }    
    
    /**
     *
     * @param EngineBlock_Model_GroupProvider $groupProvider
     */
    public function save(EngineBlock_Model_GroupProvider $groupProvider, $isNewRecord = false)
    {
        // get existing record or create a new one
        if (!$isNewRecord) {
            $row = $this->_gpTable->find($groupProvider->id)->current();
            $optionRows = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderOption');
        } else {
            $row = $this->_gpTable->createRow();
            $optionRows = array();
        }

        // check the PK
        $uniqueSelect = $this->_gpTable->select()->where('id = ?', $groupProvider->id);
        $duplicates = $this->_gpTable->fetchAll($uniqueSelect)->toArray();
        if (!$isNewRecord || count($duplicates) == 0) {
            list($row, $newOptionRows, $preconditions, $decorators, $filters) = $this->_mapGroupProviderToRow($groupProvider, $row, $optionRows);
            $row->save();
            foreach($newOptionRows as $optionRow) {
                $optionRow->save();
            }
            // save preconditions
            $this->_savePreconditions($row, $preconditions);
            $this->_saveDecorators($row, $decorators);
            $this->_saveFilters($row, $filters);
        }
        else {
            $groupProvider->errors['id'][] = "A Group Provider with id '{$duplicates[0]['id']}' already exists";
        }

        return $groupProvider;
    }
    
    protected function _savePreconditions($row, $preconditions) {
        // delete the old
        $gppTable = new EngineBlock_Model_DbTable_GroupProviderPrecondition();
        $gppoTable = new EngineBlock_Model_DbTable_GroupProviderPreconditionOption();
        $gppOld = $gppTable->fetchAll($gppTable->select("id")->where("group_provider_id = '{$row['id']}'"));
        try {
            // delete the old
            foreach ($gppOld as $old) {
                $old->delete(); // cascade delete for options
            }
            // insert the new
            foreach ($preconditions as $p) {
                // separate options
                $gppoData = $p['options']; unset($p['options']);
                // insert precondition row
                $gppRow = $gppTable->createRow();
                $gppRow->setFromArray($p);
                $gppId = $gppRow->save();
                // insert related options
                if (is_array($gppoData)) {
                    foreach ($gppoData as $o) {
                        $gppoRow = $gppoTable->createRow();
                        $gppoRow->setFromArray($o);
                        $gppoRow->group_provider_precondition_id = $gppId;
                        $gppoRow->save();
                    }
                }
            }
        } 
        catch (Exception $ex) {
            // TODO: handle exception
            throw $ex;
        }
    }
    
    protected function _saveDecorators($row, $decorators) {
        // delete the old
        $gpdTable = new EngineBlock_Model_DbTable_GroupProviderDecorator();
        $gpdoTable = new EngineBlock_Model_DbTable_GroupProviderDecoratorOption();
        $gpdOld = $gpdTable->fetchAll($gpdTable->select("id")->where("group_provider_id = '{$row['id']}'"));
        try {
            // delete the old
            foreach ($gpdOld as $old) {
                $old->delete(); // cascade delete for options
            }
            // insert the new
            foreach ($decorators as $d) {
                // separate options
                $gpdoData = $d['options']; unset($d['options']);
                // insert decorator row
                $gpdRow = $gpdTable->createRow();
                $gpdRow->setFromArray($d);
                $gpdId = $gpdRow->save();
                // insert related options
                if (is_array($gpdoData)) {
                    foreach ($gpdoData as $o) {
                        $gpdoRow = $gpdoTable->createRow();
                        $gpdoRow->setFromArray($o);
                        $gpdoRow->group_provider_decorator_id = $gpdId;
                        $gpdoRow->save();
                    }
                }
            }
        } 
        catch (Exception $ex) {
            // TODO: handle exception
            throw $ex;
        }
    }
    
    protected function _saveFilters($row, $filters) {
        // delete the old
        $gpfTable = new EngineBlock_Model_DbTable_GroupProviderFilter();
        $gpfoTable = new EngineBlock_Model_DbTable_GroupProviderFilterOption();
        $gpfOld = $gpfTable->fetchAll($gpfTable->select("id")->where("group_provider_id = '{$row['id']}'"));
        try {
            // delete the old
            foreach ($gpfOld as $old) {
                $old->delete(); // cascade delete for options
            }
            // insert the new
            foreach ($filters as $d) {
                // separate options
                $gpfoData = $d['options']; unset($d['options']);
                // insert filter row
                $gpfRow = $gpfTable->createRow();
                $gpfRow->setFromArray($d);
                $gpfId = $gpfRow->save();
                // insert related options
                if (is_array($gpfoData)) {
                    foreach ($gpfoData as $o) {
                        $gpfoRow = $gpfoTable->createRow();
                        $gpfoRow->setFromArray($o);
                        $gpfoRow->group_provider_filter_id = $gpfId;
                        $gpfoRow->save();
                    }
                }
            }
        } 
        catch (Exception $ex) {
            // TODO: handle exception
            throw $ex;
        }
    }
    
    protected function _mapRowToGroupProvider(Zend_Db_Table_Row_Abstract $row, Array $options, $groupProvider)
    {
        $groupProvider->id = $row['id'];
        $groupProvider->identifier = $row['identifier'];
        $groupProvider->name = $row['name'];
        $groupProvider->fullClassname = $row['classname'];
        $groupProvider->classname = EngineBlock_Model_GroupProvider::getClassnameDisplayValue($row['classname']);
        
        // add options
        foreach ($options as $i => $option) {
            $column = $groupProvider->getColumnName($option['name']);
            if (strlen($column) > 0) $groupProvider->$column = $option['value'];
        }
        
        // special case: grouper URL is built from several components
        if ($groupProvider->classname == "GROUPER" && isset($groupProvider->host)) {
            $groupProvider->url = $groupProvider->protocol.'://'.$groupProvider->host.'/'.$groupProvider->version.'/'.$groupProvider->path;
            // clean multiple slashes (except ://)
            $groupProvider->url = preg_replace('/([^:])([\/]+)([^\/]|$)/', "$1/$3", $groupProvider->url);
        }
  
        return $groupProvider;
    }

    protected function _mapGroupProviderToRow(EngineBlock_Model_GroupProvider $groupProvider, Zend_Db_Table_Row_Abstract $row, $optionRows)
    {
        // the basics
        $row['id'] = $groupProvider->id;
        $row['identifier'] = $groupProvider->identifier;
        $row['name'] = $groupProvider->name;

        // transformations model->row: 
        // * authentication type -> classname
        $authType = (is_array($groupProvider->authentication) ? $groupProvider->authentication[0] : "GROUPER");
        switch($authType) {
            case 'BASIC':
                $row['classname'] = EngineBlock_Model_GroupProvider::getClassname('OPENSOCIAL_BASIC');
                break;
            case 'OAUTH':
                $row['classname'] = EngineBlock_Model_GroupProvider::getClassname('OPENSOCIAL_OAUTH');
                break;
            case 'GROUPER':
                $row['classname'] = EngineBlock_Model_GroupProvider::getClassname('GROUPER');
                break;
            default:
                $row['classname'] = $groupProvider->fullClassname;
                break;
        }
        // *         

        // special case: grouper URL is split into several components
        if ($groupProvider->classname == "GROUPER" && strlen(trim($groupProvider->url)) > 0) {
            $components = parse_url(trim($groupProvider->url));
            if (is_array($components)) {
                $groupProvider->protocol = $components['scheme'];
                $groupProvider->host = $components['host'];
                $pathList = array_filter(explode("/", $components['path']));
                $groupProvider->version = array_shift($pathList);
                $groupProvider->path = '/'.implode("/", $pathList);
            }
            unset($groupProvider->url);
        }

        // map preconditions
        $preconditions = array();
        if ($groupProvider->classname == 'OPENSOCIAL_OAUTH') {
            // mandatory precondition for OAuth
            $preconditions[] = array(
                'group_provider_id' => $groupProvider->id,
                'type' => 'EngineBlock_Group_Provider_Precondition_OpenSocial_Oauth_AccessTokenExists',
            );
        }
        if ($groupProvider->user_id_match == 'on') {
            // user id must match
            $preconditions[] = array(
                'group_provider_id' => $row['id'],
                'type' => 'EngineBlock_Group_Provider_Precondition_UserId_PregMatch',
                'options' => array(
                    array('name' => 'search', 'value' => $groupProvider->user_id_match_search)
                ),
            );
        }

        $decorators = array();
        if ($groupProvider->modify_group_id == 'on') {
            // group id modifier
            $decorators[] = array(
                'group_provider_id' => $row['id'],
                'classname' => 'EngineBlock_Group_Provider_Decorator_GroupIdReplace',
                'options' => array(
                    array('name' => 'search', 'value' => $groupProvider->modify_group_id_search),
                    array('name' => 'replace', 'value' => $groupProvider->modify_group_id_replace),
                ),
            );
        }
        if ($groupProvider->modify_user_id == 'on') {
            // user id modifier
            $decorators[] = array(
                'group_provider_id' => $row['id'],
                'classname' => 'EngineBlock_Group_Provider_Decorator_UserIdReplace',
                'options' => array(
                    array('name' => 'search', 'value' => $groupProvider->modify_user_id_search),
                    array('name' => 'replace', 'value' => $groupProvider->modify_user_id_replace),
                ),
            );
        }
        
        $filters = array();
        if ($groupProvider->modify_group == 'on') {
            foreach ($groupProvider->modify_group_rule as $rule) {
                $filters[] = array(
                    'group_provider_id' => $row['id'],
                    'classname' => 'EngineBlock_Group_Provider_Filter_ModelProperty_PregReplace',
                    'type' => 'group',
                    'options' => array(
                        array('name' => 'property', 'value' => $rule['property']),
                        array('name' => 'search', 'value' => $rule['search']),
                        array('name' => 'replace', 'value' => $rule['replace'])
                    )
                );
            }
        }
        if ($groupProvider->modify_user == 'on') {
            foreach ($groupProvider->modify_user_rule as $rule) {
                $filters[] = array(
                    'group_provider_id' => $row['id'],
                    'classname' => 'EngineBlock_Group_Provider_Filter_ModelProperty_PregReplace',
                    'type' => 'groupMember',
                    'options' => array(
                        array('name' => 'property', 'value' => $rule['property']),
                        array('name' => 'search', 'value' => $rule['search']),
                        array('name' => 'replace', 'value' => $rule['replace'])
                    )
                );
            }
        }
                
        // map existing and new options to new option rows
        $gpoTable = new EngineBlock_Model_DbTable_GroupProviderOption();
        $newOptionRows = array();
        foreach (EngineBlock_Model_GroupProvider::$allowedOptions as $column => $option) {
            if (isset($groupProvider->$column)) {
                // update?
                $updated = false;
                foreach ($optionRows as $optionRow) {
                    if ($optionRow->name == $option) {
                        $optionRow->value = $groupProvider->$column;
                        $newOptionRows[] = $optionRow;
                        $updated = true;
                        break;
                    }
                }
                // insert?
                if (!$updated) {
                    $newOptionRow = $gpoTable->createRow();
                    $newOptionRow->group_provider_id = $row['id'];
                    $newOptionRow->name = $option;
                    $newOptionRow->value = $groupProvider->$column;
                    $newOptionRows[] = $newOptionRow;
                }
            }
        }        
        
        return array($row, $newOptionRows, $preconditions, $decorators, $filters);
    }
}
