<?php
/**
 * Created by PhpStorm.
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/27
 * @Time: 20:06
 */
class JsonRpc
{
    protected $host    = '';
    protected $port    = '';
    protected $version = '';
    protected $id      = 0;

    function __construct($host, $port, $version)
    {
        $this->host = $host;
        $this->port = $port;
        $this->version = $version;
    }

    function request($method, $params=array())
    {
        $data = array();
        $data['jsonrpc'] = $this->version;
        $data['id'] = $this->id++;
        $data['method'] = $method;
        $data['params'] = $params;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->host);
        curl_setopt($ch, CURLOPT_PORT, $this->port);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $ret = curl_exec($ch);

        if($ret !== FALSE)
        {
            $formatted = $this->format_response($ret);

            if(isset($formatted->error))
            {
                throw new \Exception($formatted->error->message, $formatted->error->code);
            }
            else
            {
                return $formatted;
            }
        }
        else
        {
            throw new \Exception("Server did not respond");
        }
    }

    function format_response($response)
    {
        return @json_decode($response);
    }
}