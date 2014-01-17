<?php
/**
 * User: inpu
 * Date: 28.12.13
 * Time: 2:26
 */

namespace bc\Mongo;

use bc\Config\ConfigManager;

class Mongo {

    /**
     * @var \MongoClient
     */
    private static $mongo;
    /**
     * @var \MongoDB
     */
    private static $mongoDB;
    /**
     * @var \MongoCollection[]
     */
    private static $storedCollections = array();

    /**
     * @param $collection
     * @throws \Exception
     * @return \MongoCollection
     */
    public static function getCollection($collection) {
        self::checkMongo();
        if(isset(self::$storedCollections[$collection])) {
            return self::$storedCollections[$collection];
        }
        self::$storedCollections[$collection] = self::$mongoDB->selectCollection($collection);
        return self::$storedCollections[$collection];
    }

    /**
     * Get default MongoDB if $name is null, otherwise named MongoDB
     * @param string $name
     * @return \MongoDB
     */
    public static function getDb($name = null)
    {
        self::checkMongo();
        if(is_null($name)) {
            return self::$mongoDB;
        } else {
            return self::$mongo->selectDB($name);
        }
    }

    private static function checkMongo()
    {
        $cfg = ConfigManager::get('config/mongo.json');
        if (is_null(self::$mongo)) {
            $connectString = 'mongodb://';
            $options = array('connect' => true);
            $connectString .= $cfg->get('host');
            try {
                $connectString .= ':'.$cfg->get('port');
            }
            catch(\InvalidArgumentException $e) {
                //ничего не делаем. например, в хосте указан сокет
            }
            $options = array_merge($options, $cfg->get('options'));

            self::$mongo = new \MongoClient($connectString, $options);
        } elseif(!self::$mongo->connected) {
            if(!self::$mongo->connect()) {
                throw new \RuntimeException();
            }
        }
        if(is_null(self::$mongoDB)) {
            self::$mongoDB = self::$mongo->selectDB($cfg->get('database'));
        }
    }

} 