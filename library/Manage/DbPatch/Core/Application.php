<?php
/**
 * Override of DbPatch Application class to inject configuration,
 * read from the surfconext-admin configuration file
 *
 */
class Manage_DbPatch_Core_Application extends DbPatch_Core_Application
{
    protected function getConfig($filename = null)
    {
        try {

            
            $app = new Manage_Application(
                            $this->_getEnvironment(), 
                            APPLICATION_PATH . '/configs/application.ini');
            $dbConfig = $app->getConfig()->resources->multidb->manage->toArray();
            $config = array(
                'db' => array(
                    'adapter'   => $this->_convertPdoDriverToZendDbAdapter('mysql'),
                    'params' => $dbConfig,
                ),
                'patch_directory' => realpath(__DIR__ . '/../../../../database/patch'),
                'color' => true,
            );
        } catch (Exception $e) {
            die($e->getMessage()."\n");
        }
        
        return new Zend_Config($config);
    }

    private function _convertPdoDriverToZendDbAdapter($pdoDriver)
    {
        switch ($pdoDriver) {
            case 'mysql':
                return 'Mysqli';
            default:
                throw new Exception("Unsupported PDO driver '$pdoDriver'");
        }
    }
    
    /**
     * Get environment from shell environment variable 
     * 
     * @return String
     */
    protected function _getEnvironment()
    {
        if (getenv('APPLICATION_ENV')) {
            $this->_applicationEnvironmentId = getenv('APPLICATION_ENV');
            define('APPLICATION_ENV', $this->_applicationEnvironmentId);
        } else {
            throw new Exception("Environment variable APPLICATION_ENV not set or empty\n");
        }
        return $this->_applicationEnvironmentId;
    }
    
    
}