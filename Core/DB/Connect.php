<?php
namespace Core\DB;

use Exception;
use PDO;

class Connect
{
    private static $pdoInstance = null;

    /**
     * @throws Exception
     */
    private static function loadConfig($configFile)
    {
        if (!is_readable($configFile)) {
            throw new Exception(sprintf('Config file %s is not found', $configFile));
        }
        return include_once($configFile);
    }

    /**
     * @throws Exception
     */
    public static function init($configFile)
    {
        $config = self::loadConfig($configFile);
        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];

        try{
            self::$pdoInstance = new PDO($dsn, $config['user'], $config['password']);
            self::$pdoInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e){
            echo 'Соединение оборвалось: '. $e->getMessage();
        }

    }

    /**
     * @throws Exception
     */
    public static function getPdoInstance(): PDO
    {
        if (is_null(self::$pdoInstance)) {
            throw new \Exception("Use init() at first call");
        }

        return self::$pdoInstance;
    }


}