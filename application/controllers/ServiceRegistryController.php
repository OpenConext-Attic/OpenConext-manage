<?php
/**
 *
 */

/**
 *
 */ 
class ServiceRegistryController extends Zend_Controller_Action
{
    public function init()
    {
        //Get the identity
        $this->view->identity = $this->_helper->Authenticate('portal');
    }

    public function indexAction()
    {
    }

    public function validateEntityAction()
    {
        $this->getHelper('viewRenderer')->setNoRender();

        if (!$this->_getParam('eid')) {
            throw new Exception('Entity ID required');
        }

        $config = Zend_Registry::get('config');
        $serviceRegistryConfig = $config['serviceRegistry'];

        $baseUrl = $serviceRegistryConfig['scheme'] . '://' . $serviceRegistryConfig['host'];
        $url = $baseUrl . $serviceRegistryConfig['url']['validate']['entityCertificate'];
        $url .= urlencode($this->_getParam('eid'));

        $urlResponse = file_get_contents($url);
        $data = json_decode($urlResponse);
        if (!$data) {
            throw new Exception("Unable to decode data from '$url', response: " . htmlentities($urlResponse));
        }

        $warnings = array();
        if (isset($data->Warnings)) {
            $warnings = array_merge($warnings, $data->Warnings);
        }
        $errors = array();
        if (isset($data->Errors)) {
            $errors = array_merge($errors, $data->Errors);
        }

        $url = $baseUrl . $serviceRegistryConfig['url']['validate']['entityEndpoints'];
        $url .= urlencode($this->_getParam('eid'));

        $urlResponse = file_get_contents($url);
        $data = json_decode($urlResponse);
        if (!$data) {
            throw new Exception("Unable to decode data from '$url', response: " . htmlentities($urlResponse));
        }
        if (isset($data->Warnings)) {
            $warnings = array_merge($warnings, $data->Warnings);
        }
        if (isset($data->Errors)) {
            $errors = array_merge($errors, $data->Errors);
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'Response'=>array(
                'Link' => $baseUrl . $serviceRegistryConfig['url']['validate']['link'],
                'Results'=>array(
                    array(
                        'Warnings'=>$warnings,
                        'Errors'=>$errors
                    )
                )
            )
        ));
        exit;
    }
}