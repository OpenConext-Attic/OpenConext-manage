<?php
/**
 * Dao class for JanusEntity.
 *
 *
 * @author marc
 */
class Model_Dao_JanusEntity extends Model_Dao_Abstract
{
   protected $_name = 'janus__entity';
   protected $_schema = 'service_registry';
   protected $_use_adapter = 'db_service_registry';
   protected $_primary = array('eid','revisionid');
}