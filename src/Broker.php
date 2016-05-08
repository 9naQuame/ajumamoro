<?php

namespace ajumamoro;

use ntentan\config\Config;

abstract class Broker
{
    private static $instance;
    
    abstract public function put($job);
    abstract public function get();
    abstract public function init();
    
    private static function factory()
    {
        if(!Config::get('ajumamoro:broker') || !Config::get('ajumamoro:broker.driver'))
        {
            throw new Exception('Please specify a broker for the jobs.');
        }
        $storeDriverClass = '\ajumamoro\brokers\\' . ucfirst(Config::get('ajumamoro:broker.driver')) . 'Broker';
        $storeDriver = new $storeDriverClass();
        $storeDriver->init();
        return $storeDriver;
    }
    
    /**
     *
     * @return ajumamoro\Store
     */
    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = Broker::factory();
            self::$instance->init();
        }
        return self::$instance;
    }  
    
    public static function reset()
    {
        self::$instance = null;
    }    
    
    public static function setParameters($parameters)
    {
        self::$parameters = $parameters;
    }
}