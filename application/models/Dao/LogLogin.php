<?php

class Model_Dao_LogLogin extends Model_Dao_Abstract
{
   protected $_name         = 'log_logins';
   protected $_use_adapter  = 'db_engine_block';
   protected $_primary      = array(
       'loginstamp',
       'userid',
       'spentityid',
       'idpentityid'
   );
}