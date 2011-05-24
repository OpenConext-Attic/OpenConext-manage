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

/*
 * Start output buffering
 */
ob_start();

/*
 * Set error reporting to the level to which Zend Framework code must comply.
 */
error_reporting( E_ALL | E_STRICT );

/*
 * Maximize memory limit
 */
ini_set('memory_limit', -1);

/*
 * Set default timezone
 */
date_default_timezone_set('GMT');

define('BASE_PATH', realpath(dirname(__FILE__). '/../'));
defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', BASE_PATH . '/application/');
defined('LIBRARY_PATH')
    or define('LIBRARY_PATH', BASE_PATH . '/library');
defined('TEST_PATH')
    or define('TEST_PATH', BASE_PATH . '/tests/');
/*
 * Testing environment
 */

define('APPLICATION_ENV', 'testing');

/*
 * Determine the root, library, tests, and models directories
 */
$root        = realpath(dirname(__FILE__) . '/../');
$library     = $root . '/library';
$tests       = $root . '/tests';
$models      = $root . '/application/models';
$controllers = $root . '/application/controllers';

/*
 * Prepend the library/, tests/, and models/ directories to the
 * include_path. This allows the tests to run out of the box.
 */
$path = array(
    $models,
    $library,
    $tests,
    $controllers,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

/*
 * Add library/ and models/ directory to the PHPUnit code coverage
 * whitelist. This has the effect that only production code source files appear
 * in the code coverage report and that all production code source files, even
 * those that are not covered by a test yet, are processed.
 */
if (defined('TESTS_GENERATE_REPORT') && TESTS_GENERATE_REPORT === true &&
    version_compare(PHPUnit_Runner_Version::id(), '3.1.6', '>=')) {
    PHPUnit_Util_Filter::addDirectoryToWhitelist($library);
    PHPUnit_Util_Filter::addDirectoryToWhitelist($models);
    PHPUnit_Util_Filter::addDirectoryToWhitelist($controllers);
}

require_once 'Zend/Application.php';
$application = new Zend_Application(APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap();

/*
 * Unset global variables that are no longer needed.
 */
unset($root, $library, $models, $controllers, $tests, $path);
