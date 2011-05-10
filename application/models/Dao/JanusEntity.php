<?php

class Model_Dao_JanusEntity extends Model_Dao_Abstract
{
   protected $_name = 'janus__entity';
   protected $_use_adapter = 'db_service_registry';
   protected $_primary = array('eid','revisionid');
}