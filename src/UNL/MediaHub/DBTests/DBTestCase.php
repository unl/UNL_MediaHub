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

    /**
     * Prepare for a new db Test (clean database and install mock data).
     * 
     * @param UNL_MediaHub_DBTests_MockTestDataInstallerInterface $installer
     */
    protected function prepareTestDB(UNL_MediaHub_DBTests_MockTestDataInstallerInterface $installer = NULL)
    {
        if (!$installer) {
            $installer = new UNL_MediaHub_DBTests_BaseTestDataInstaller();
        }

        $this->cleanDB();
        $this->installBaseDB();
        $this->installMockData($installer);
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