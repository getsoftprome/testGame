<?php
namespace Model;

use Core\DB\Connect;
use Exception;

class Model
{

    /**
     * @throws Exception
     */
    public static function getPdoInstance(): \PDO
    {
        return Connect::getPdoInstance();
    }

}