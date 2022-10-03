<?php

/**
 * Class to load Database configurations.
 *
 * Class DatabaseConfiguration.
 */
class DatabaseConfiguration
{
    protected $bdHost;
    protected $bdUser;
    protected $dbPass;
    protected $dbName;

    /**
     * Singleton instance of configurations.
     *
     * @var DatabaseConfiguration $instance.
     */
    protected static $instance;

    /**
     * Get a new initialized instance.
     *
     * @return DatabaseConfiguration.
     */
    public static function getInstance() {
        if (empty(self::$instance)) {
            require_once 'config.php';
            self::$instance =  new static($bdHost, $dbUser, $dbPass, $dbName);
        }
        return self::$instance;
    }

    /**
     * Private constructor only for internal use.
     *
     * DatabaseConfiguration constructor.
     * @param $dbUser
     * @param $dbPass
     * @param $dbName
     */
    private function __construct($bdHost, $dbUser, $dbPass, $dbName) {
        $this->bdHost = $bdHost;
        $this->bdUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbName = $dbName;
    }

    /**
     * Get database host.
     *
     * @return string
     */
    public function getBdHost()
    {
        return $this->bdHost;
    }

    /**
     * Get database user.
     *
     * @return string
     */
    public function getBdUser()
    {
        return $this->bdUser;
    }

    /**
     * Get database password.
     *
     * @return string
     */
    public function getDbPass()
    {
        return $this->dbPass;
    }

    /**
     * Get database name.
     *
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

}
