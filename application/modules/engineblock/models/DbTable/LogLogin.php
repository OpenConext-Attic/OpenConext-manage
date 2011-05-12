<?php

class EngineBlock_Model_DbTable_LogLogin extends EngineBlock_Model_DbTable_Abstract
{
    protected $_name         = 'log_logins';
    protected $_primary      = array(
       'loginstamp',
       'userid',
       'spentityid',
       'idpentityid'
    );
}