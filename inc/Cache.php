<?php


class Cache 
{
    CONST MEMCACHED_PERSISTENT_ID = '2022';
    
    private static $instance;
    
    private $memcached;
    
    private function __construct() 
    {
        $this->memcached = new Memcached(static::MEMCACHED_PERSISTENT_ID);
        if (!count($this->memcached->getServerList())) {
            $this->memcached->addServer("127.0.0.1",11211);
        }
    }
    
    
    /**
     * singleton getter
     * 
     * @return Cache
     */
    protected static function getInstance() 
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * @return Memcached
     */
    protected function getMemcached() 
    {
        return $this->memcached;
    }
    
    /**
     * Save an object or other stuff in Cache
     * 
     * @param string $key the cache KEY
     * @param type $value the cache VALUE
     * @param type $ttl (optional) the TTL, if null is passed then Config::MEMCACHED_TTL will be used
     * 
     * @return boolean true on success
     */
    public static function save(string $key, $value, $ttl = null)
    {
        return static::getInstance()->getMemcached()->set($key, $value, $ttl !== null ? $ttl : 84500);
    }
    
    /**
     * Get an object or other stuff from Cache
     * 
     * @param string $key the cache KEY
     * 
     * @return mixed the object that was in cache
     */
    public static function get(string $key)
    {
        $result = static::getInstance()->getMemcached()->get($key);
        if (static::getInstance()->getMemcached()->getResultCode() ==  Memcached::RES_NOTFOUND) {
            $result = null;
        }
        return $result;
    }
    
    /**
     * delete an object or other stuff from Cache
     * 
     * @param string $key the cache KEY
     * 
     * @return boolean true on success
     */
    public static function delete(string $key)
    {
        return static::getInstance()->getMemcached()->delete($key);
    }
    
    
    /**
     * Flush all cache
     * 
     * @return boolean true on success
     */
    public static function flush()
    {
        return static::getInstance()->getMemcached()->flush();
    }
    
    
}
