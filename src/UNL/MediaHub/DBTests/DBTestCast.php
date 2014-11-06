<?php
class UNL_MediaHub_DBTests_DBTestCase extends \PHPUnit_Framework_TestCase {
    /**
     * @var UNL_MediaHub
     */
    protected $mediahub;

    /**
     * @var UNL_MediaHub_Installer
     */
    protected $installer;
    
    protected function setUp()
    {
        try {
            $this->mediahub = new UNL_MediaHub();
        } catch (\Exception $e) {
            $this->markTestSkipped('Test database is not available, database tests were skipped: ' . $e->getMessage());
        }
        
        $this->installer = new UNL_MediaHub_Installer($this->mediahub);
    }

    public function cleanDB()
    {
        $this->installer->uninstall();
    }

    public function installBaseDB()
    {
        $this->installer->install();
    }

    public function installMockData(UNL_Mediahub_DBTests_MockTestDataInstallerInterface $installer)
    {
        $installer->install();
    }
}