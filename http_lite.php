<?php
class Http_Exception extends Exception{
    const NOT_MODIFIED = 304; 
    const BAD_REQUEST = 400; 
    const NOT_FOUND = 404; 
    const NOT_ALOWED = 405; 
    const CONFLICT = 409; 
    const PRECONDITION_FAILED = 412; 
    const INTERNAL_ERROR = 500; 
}

class Http
{
    private $_host = null;
    private $_port = null;
    private $_user = null;
    private $_pass = null;
    private $_protocol = null;

    const HTTP  = 'http';
    const HTTPS = 'https';
    
    /**
     * Factory of the class. Lazy connect
     *
     * @param string $host
     * @param integer $port
     * @param string $user
     * @param string $pass
     * @return Http
     */
    static public function connect($host, $port = 80, $protocol = self::HTTP)
    {
        return new self($host, $port, $protocol, false);
    }
    

    private $_append = array();
    public function add($http)
    {
        $this->_append[] = $http;
        return $this;
    }
    
    private $_silentMode = false;
    /**
     *
     * @param bool $mode
     * @return Http
     */
    public function silentMode($mode=true)
    {
        $this->_silentMode = $mode;
        return $this;    
    }
    
    protected function __construct($host, $port, $protocol)
    {
        $this->_host     = $host;
        $this->_port     = $port;
        $this->_protocol = $protocol;
    }
    
    public function setCredentials($user, $pass)
    {
        $this->_user = $user;
        $this->_pass = $pass;
    }

    const GET    = 'GET';

    private $_requests = array();
    /**
     * @param string $url
     * @param array $params
     * @return Http
     */
    public function get($url, $params=array())
    {
        $this->_requests[] = array(self::GET, $this->_url($url), $params);
        return $this;
    }
    
    public function _getRequests()
    {
        return $this->_requests;
    }
    
    /**
     * GET Request
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    public function doGet($url, $params=array())
    {
        return $this->_exec($this->_url($url), $params);
    }
    
    private $_headers = array();
    /**
     * setHeaders
     *
     * @param array $headers
     * @return Http
     */
    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }

    /**
     * Builds absolute url 
     *
     * @param unknown_type $url
     * @return unknown
     */
    private function _url($url=null)
    {
        $full_url = $this->_protocol . '://';
        $full_url .= $this->_host;
        $full_url .=  !is_null($this->_port) ? ':' . $this->_port : '';
        $full_url .= '/'. $url;
        //return "{$this->_protocol}://{$this->_host}:{$this->_port}/{$url}";
        return $full_url;
    }

    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACEPTED = 202;

    /**
     * Performing the real request
     *
     * @param string $type
     * @param string $url
     * @param array $params
     * @return string
     */
    private function _exec($url, $params = array())
    {
        $headers = $this->_headers;
        $s = curl_init();
        
        if(!is_null($this->_user)){
           curl_setopt($s, CURLOPT_USERPWD, $this->_user.':'.$this->_pass);
        }

        curl_setopt($s, CURLOPT_URL, $url . '?' . http_build_query($params));

        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        $_out = curl_exec($s);
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);
        switch ($status) {
            case self::HTTP_OK:
                $out = $_out;
                break;
            default:
                if (!$this->_silentMode) {
                    throw new Http_Exception("http error: {$status}", $status);
                }
        }
        return $out;
    }
    
}
