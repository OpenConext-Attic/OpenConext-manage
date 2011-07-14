<?php
/**
 * SURFconext Manage
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

include __DIR__ . '/../public/_include.php';

/** Zend_Application */
require_once 'Surfnet/Application.php';

// Create application, bootstrap, and run
$application = new Surfnet_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// bootstrap and retrive the frontController resource
$front = $application->getBootstrap()
                     ->bootstrap('frontController')
                     ->getResource('frontController');

$request = new Zend_Controller_Request_Simple('vers', 'export', 'default');

// set front controller options to make everything operational from CLI
$front->setRequest($request)
      ->setResponse(new Zend_Controller_Response_Cli())
      ->setRouter(new Surfnet_Controller_Router_Cli())
      ->throwExceptions(true);

try {
    // lets bootstrap our application and enjoy!
    $res = $application->bootstrap()
                       ->run();
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
    exit ($e->getCode());
}

exit(0);
