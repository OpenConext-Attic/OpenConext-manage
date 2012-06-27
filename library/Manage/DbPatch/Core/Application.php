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
                APPLICATION_PATH . '/configs/application.ini'
            );
            $dbConfig = $app->getConfig()->resources->multidb->manage->toArray();

            $config = array(
                'db' => array(
                    'adapter'   => $this->_convertPdoDriverToZendDbAdapter('mysql'),
                    'params' =>array(
                        'host'      => isset($dbConfig['host'])    ? $dbConfig['host']    : 'localhost',
                        'username'  => isset($dbConfig['user'])    ? $dbConfig['user']    : 'root',
                        'password'  => isset($dbConfig['password'])? $dbConfig['password']: '',
                        'dbname'    => isset($dbConfig['dbname'])  ? $dbConfig['dbname']  : 'manage',
                        'charset'   => isset($dbConfig['charset']) ? $dbConfig['charset'] : 'utf8',
                    ),
                ),
                'patch_directory' => realpath(__DIR__ . '/../../../../database/patch'),
                'color' => true,
            );

            return new Zend_Config($config, true);
        } catch (Exception $e) {
            die($e->getMessage()."\n");
        }
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
            if (!defined('APPLICATION_ENV')) {
                define('APPLICATION_ENV', $this->_applicationEnvironmentId);
            }
        } else {
            throw new Exception("Environment variable APPLICATION_ENV not set or empty\n");
        }
        return $this->_applicationEnvironmentId;
    }
    
    
}