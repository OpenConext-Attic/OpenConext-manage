<?php
/**
 * Unit tests for the portal controller.
 */

require_once TEST_PATH . 'ControllerTestCase.php';

class PortalControllerTest extends ControllerTestCase
{
    public function testGadgetUsageAction()
    {
        $this->dispatch('/');
        $this->assertController('index');
        $this->assertAction('index');
    }
}
?>
