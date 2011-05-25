<?php

class Portal_Service_Gadget
{
    /**
     * Search for gadgets with a custom gadgetdefinition.
     *
     * @param Surfnet_Search_Parameters $params
     * @return Surfnet_Search_Result
     */
    public function search(Surfnet_Search_Parameters $params)
    {
        $dao = new Portal_Model_DbTable_Gadget();

        $query = $dao->select()->setIntegrityCheck(false)->from($dao,array('id'=>'gadget.id'))
                    ->join('gadgetdefinition', 'gadget.definition=gadgetdefinition.id')
                    ->join('tab', 'gadget.tab_id=tab.id')
                    ->where('object_type <> "ClonedTab"')
                    ->columns();

        $searchParams = $params->getSearchParams();
        foreach ($searchParams as $key => $value) {
            if (!$value) {
                continue;
            }
            
            $query->where($key . ' LIKE ' . $dao->getAdapter()->quote('%' . $value . '%'));
        }

        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order($params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $results = $dao->fetchAll($query)->toArray();

        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $results, $totalCount);
    }

    public function searchUsage(Surfnet_Search_Parameters $params)
    {
        if (!$params->getSortByField()) {
            $params->setSortByField('num');
            $params->setSortDirection('desc');
        }

        $dao = new Portal_Model_DbTable_Gadget();
        $query = $dao->select()->setIntegrityCheck(false)->from($dao, array('definition'))
                ->join(array('gd' => 'gadgetdefinition'),
                       'gadget.definition = gd.id',
                        array('num' => 'count(gd.id)',
                              'title' => 'gd.title',
                              'author' => 'gd.author'))
                ->join(array('t' => 'tab'),
                       'gadget.tab_id = t.id')
                ->group('gadget.definition');

        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        $query->order($params->getSortByField(). ' ' . $params->getSortDirection());

        $results = $dao->fetchAll($query)->toArray();

        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $results, $totalCount);
    }

    /**
     * Update the data for a gadget.
     *
     * Validate the data, update
     *
     * @param  $data
     * @return array|bool
     */
    public function update($data)
    {
        $form = new Portal_Form_Gadget();
        if ($form->isValid($data)) {
            $gadget = $this->findById($data['id']);
            $gadget->populate($data);

            $mapper = new Portal_Model_Mapper_GadgetMapper(new Portal_Model_DbTable_Gadget());
            return $mapper->save($gadget);
        }
        return $form->getErrors();
    }

    /**
     * Find a gadget for a given id.
     *
     * Use the mapper to revive a gadget model
     *
     * @param  $id
     * @return Portal_Model_GadgetDefinition
     */
    public function findById($id)
    {
        $mapper = new Portal_Model_Mapper_GadgetMapper(new Portal_Model_DbTable_Gadget());
        return $mapper->find($id);
    }

    public function delete($id)
    {
        if ((int)$id <= 0) {
            throw new Exception("No id provided");
        }
        $dao = new Portal_Model_DbTable_Gadget();
        return $dao->delete(array('id=?'=>$id));
    }
}
