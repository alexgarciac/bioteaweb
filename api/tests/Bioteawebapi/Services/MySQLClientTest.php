<?php

namespace Bioteawebapi\Services;

class MySQLClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var boolean  If true, an attempt to use a real MySQL db and DBAL is used
     */
    private $realDB = true;

    // --------------------------------------------------------------

    public function setUp()
    {
        parent::setUp();

        //See if we can connect for real
        if ($this->realDB) {

            //Check DBAL
            $dbal = $this->getRealDBALObject();
            try {
                $dbal->connect();
            }
            catch (\PDOException $e) {
                $this->markTestSkipped("Could not connect to MySQL Database to run tests.");
            }

            //Check Schema
            $client = $this->getObj();
            if ( ! $client->checkSchema()) {
                $this->markTestSkipped("Could not run tests.  Schema not up-to-date.  Run console/buildschema to update.");
            }
        }
    }

    // --------------------------------------------------------------

    public function testStuff()
    {
        $obj = $this->getObj();
        $obj->upsert('vocabularies', 'id', array('col1' => 'abc', 'cols2' => 'def'));
    }

    // --------------------------------------------------------------

    /**
     * Get object for testing
     *
     * @return MySQLClient
     */
    public function getObj()
    {
        $conn = ($this->realDB) ? $this->getRealDBALObject() : $this->getMockDBALObject();
        return new MySQLClient($conn);
    }

    // --------------------------------------------------------------

    protected function getRealDBALObject()
    {
        //Load DBAL
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'dbname' => 'bioteaweb',
            'user' => 'root',
            'password' => '',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        return $conn;
    }

    // --------------------------------------------------------------

    protected function getMockDBALObject()
    {
        //@TODO: FLESH THIS OUT WHEN WRITING REAL UNIT TESTS!
        return null;
    }
}

/* EOF: MySQLClientTest.php */