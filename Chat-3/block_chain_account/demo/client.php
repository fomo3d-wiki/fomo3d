<?php
/**
 * Created by PhpStorm.
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/27
 * @Time: 20:05
 */
class EtherClient
{
    /**
     * 向区块链底层服务发送请求
     * @param $method
     * @param array $params
     * @return mixed
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/27
     * @Time: 20:12
     */
    private function ether_request($method, $params=array())
    {
        if(!defined('RPC_HOST_NAME')) {define ( 'RPC_HOST_NAME', '****');}
        if(!defined('RPC_PORT')) {define ( 'RPC_PORT', '***');}
        if(!defined('RPC_VERSION')) {define ( 'RPC_VERSION', '***');}

        try
        {
            /**
             * 区块链JsonRpc请求
             */
            $jsonRpc = new JsonRpc(RPC_HOST_NAME, RPC_PORT, RPC_VERSION);
            $ret = $jsonRpc->request($method, $params);
            return $ret->result;
        }
        catch(\Exception $e)
        {
            throw new $e;
        }
    }

    private function decode_hex($input)
    {
        if(substr($input, 0, 2) == '0x')
            $input = substr($input, 2);

        if(preg_match('/[a-f0-9]+/', $input))
            return hexdec($input);

        return $input;
    }

    function web3_clientVersion()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function web3_sha3($input)
    {
        return $this->ether_request(__FUNCTION__, array($input));
    }

    //TODO JsonRpc 目前没有，必须在geth的命令行里 敲写并封装 一个方法？2018/05/05
    /* function web3_fromWei($input, $unit='ether')
     {
         return $this->ether_request(__FUNCTION__, array($input, $unit));
     }
     /**
      *按对应货币 转为以wei为单位
      *可选单位：
      *1、kwei/ada
      *2、mwei/babbage
      *3、gwei/shannon
      *4、szabo
      *5、finney
      *6、ether
      *7、kether/grand/einstein
      *8、mether
      *9、gether
      *10、tether
      * @param number|string|bigNumber $input 货币量
      * @param string $unit                   货币单位
      * @return mixed
      * User: lidi
      */
    /*function web3_toWei($input, $unit='ether')
    {
        return $this->ether_request(__FUNCTION__, array($input, $unit));
    }*/

    function net_version()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function net_listening()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function net_peerCount()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_protocolVersion()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_coinbase()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_mining()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_hashrate()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_gasPrice()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_accounts()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_blockNumber($decode_hex=FALSE)
    {
        $block = $this->ether_request(__FUNCTION__);

        if($decode_hex)
            $block = $this->decode_hex($block);

        return $block;
    }

    function eth_getBalance($address, $block='latest', $decode_hex=FALSE)
    {
        $balance = $this->ether_request(__FUNCTION__, array($address, $block));

        if($decode_hex)
            $balance = $this->decode_hex($balance);

        return $balance;
    }

    function eth_getStorageAt($address, $at, $block='latest')
    {
        return $this->ether_request(__FUNCTION__, array($address, $at, $block));
    }

    function eth_getTransactionCount($address, $block='latest', $decode_hex=FALSE)
    {
        $count = $this->ether_request(__FUNCTION__, array($address, $block));

        if($decode_hex)
            $count = $this->decode_hex($count);

        return $count;
    }

    function eth_getBlockTransactionCountByHash($tx_hash)
    {
        return $this->ether_request(__FUNCTION__, array($tx_hash));
    }

    function eth_getBlockTransactionCountByNumber($tx='latest')
    {
        return $this->ether_request(__FUNCTION__, array($tx));
    }

    function eth_getUncleCountByBlockHash($block_hash)
    {
        return $this->ether_request(__FUNCTION__, array($block_hash));
    }

    function eth_getUncleCountByBlockNumber($block='latest')
    {
        return $this->ether_request(__FUNCTION__, array($block));
    }

    function eth_getCode($address, $block='latest')
    {
        return $this->ether_request(__FUNCTION__, array($address, $block));
    }

    function eth_sign($address, $input)
    {
        return $this->ether_request(__FUNCTION__, array($address, $input));
    }

    function eth_sendTransaction($transaction)
    {
        /*if(!is_a($transaction, 'Ethereum_Transaction'))
        {
            throw new \ErrorException('Transaction object expected');
        }
        else
        {*/
        return $this->ether_request(__FUNCTION__, $transaction->toArray());
        /*}*/
    }

    function eth_call($message, $block="latest")
    {
        /*if(!is_a($message, 'Ethereum_Message')) {
            exit(var_dump($message));
            throw new \ErrorException('Message object expected');
        } else {*/
        //exit(var_dump($array));
        return $this->ether_request(__FUNCTION__, array($message, $block));
        /*}*/
    }

    function eth_estimateGas($message, $block)
    {
        if(!is_a($message, 'Ethereum_Message'))
        {
            throw new \ErrorException('Message object expected');
        }
        else
        {
            return $this->ether_request(__FUNCTION__, $message->toArray());
        }
    }

    function eth_getBlockByHash($hash, $full_tx=TRUE)
    {
        return $this->ether_request(__FUNCTION__, array($hash, $full_tx));
    }

    function eth_getBlockByNumber($block='latest', $full_tx=TRUE)
    {
        return $this->ether_request(__FUNCTION__, array($block, $full_tx));
    }

    function eth_getTransactionByHash($hash)
    {
        return $this->ether_request(__FUNCTION__, array($hash));
    }

    function eth_getTransactionByBlockHashAndIndex($hash, $index)
    {
        return $this->ether_request(__FUNCTION__, array($hash, $index));
    }

    function eth_getTransactionByBlockNumberAndIndex($block, $index)
    {
        return $this->ether_request(__FUNCTION__, array($block, $index));
    }

    function eth_getTransactionReceipt($tx_hash)
    {
        return $this->ether_request(__FUNCTION__, array($tx_hash));
    }

    function eth_getUncleByBlockHashAndIndex($hash, $index)
    {
        return $this->ether_request(__FUNCTION__, array($hash, $index));
    }

    function eth_getUncleByBlockNumberAndIndex($block, $index)
    {
        return $this->ether_request(__FUNCTION__, array($block, $index));
    }

    function eth_getCompilers()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_compileSolidity($code)
    {
        return $this->ether_request(__FUNCTION__, array($code));
    }

    function eth_compileLLL($code)
    {
        return $this->ether_request(__FUNCTION__, array($code));
    }

    function eth_compileSerpent($code)
    {
        return $this->ether_request(__FUNCTION__, array($code));
    }

    function eth_newFilter($filter, $decode_hex=FALSE)
    {
        if(!is_a($filter, 'Ethereum_Filter'))
        {
            throw new \ErrorException('Expected a Filter object');
        }
        else
        {
            $id = $this->ether_request(__FUNCTION__, $filter->toArray());

            if($decode_hex)
                $id = $this->decode_hex($id);

            return $id;
        }
    }

    function eth_newBlockFilter($decode_hex=FALSE)
    {
        $id = $this->ether_request(__FUNCTION__);

        if($decode_hex)
            $id = $this->decode_hex($id);

        return $id;
    }

    function eth_newPendingTransactionFilter($decode_hex=FALSE)
    {
        $id = $this->ether_request(__FUNCTION__);

        if($decode_hex)
            $id = $this->decode_hex($id);

        return $id;
    }

    function eth_uninstallFilter($id)
    {
        return $this->ether_request(__FUNCTION__, array($id));
    }

    function eth_getFilterChanges($id)
    {
        return $this->ether_request(__FUNCTION__, array($id));
    }

    function eth_getFilterLogs($id)
    {
        return $this->ether_request(__FUNCTION__, array($id));
    }

    function eth_getLogs($filter)
    {
        if(!is_a($filter, 'Ethereum_Filter'))
        {
            throw new \ErrorException('Expected a Filter object');
        }
        else
        {
            return $this->ether_request(__FUNCTION__, $filter->toArray());
        }
    }

    function eth_getWork()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function eth_submitWork($nonce, $pow_hash, $mix_digest)
    {
        return $this->ether_request(__FUNCTION__, array($nonce, $pow_hash, $mix_digest));
    }

    function db_putString($db, $key, $value)
    {
        return $this->ether_request(__FUNCTION__, array($db, $key, $value));
    }

    function db_getString($db, $key)
    {
        return $this->ether_request(__FUNCTION__, array($db, $key));
    }

    function db_putHex($db, $key, $value)
    {
        return $this->ether_request(__FUNCTION__, array($db, $key, $value));
    }

    function db_getHex($db, $key)
    {
        return $this->ether_request(__FUNCTION__, array($db, $key));
    }

    function shh_version()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function shh_post($post)
    {
        if(!is_a($post, 'Whisper_Post'))
        {
            throw new \ErrorException('Expected a Whisper post');
        }
        else
        {
            return $this->ether_request(__FUNCTION__, $post->toArray());
        }
    }

    function shh_newIdentinty()
    {
        return $this->ether_request(__FUNCTION__);
    }

    function shh_hasIdentity($id)
    {
        return $this->ether_request(__FUNCTION__);
    }

    function shh_newFilter($to=NULL, $topics=array())
    {
        return $this->ether_request(__FUNCTION__, array(array('to'=>$to, 'topics'=>$topics)));
    }

    function shh_uninstallFilter($id)
    {
        return $this->ether_request(__FUNCTION__, array($id));
    }

    function shh_getFilterChanges($id)
    {
        return $this->ether_request(__FUNCTION__, array($id));
    }

    function shh_getMessages($id)
    {
        return $this->ether_request(__FUNCTION__, array($id));
    }

    function personal_newAccount($pass)
    {
        return $this->ether_request(__FUNCTION__, array($pass));
    }

    function personal_unlockAccount($account, $pass)
    {
        return $this->ether_request(__FUNCTION__, array($account, $pass));
    }

    function miner_setGasPrice($number)
    {
        return $this->ether_request(__FUNCTION__, array($number));
    }

    function miner_start($number)
    {
        return $this->ether_request(__FUNCTION__, array($number));
    }

    function miner_stop()
    {
        return $this->ether_request(__FUNCTION__);
    }

}

/**
 *	Ethereum transaction object
 */
class Ethereum_Transaction
{
    private $to, $from, $gas, $gasPrice, $value, $data, $nonce;

    function __construct($from, $to, $gas, $gasPrice, $value, $data='', $nonce=NULL)
    {
        $this->from = $from;
        $this->to = $to;
        $this->gas = $gas;
        $this->gasPrice = $gasPrice;
        $this->value = $value;
        $this->data = $data;
        $this->nonce = $nonce;
    }

    function toArray()
    {
        return array(
            array
            (
                'from'=>$this->from,
                'to'=>$this->to,
                'gas'=>$this->gas,
                'gasPrice'=>$this->gasPrice,
                'value'=>$this->value,
                'data'=>$this->data,
                'nonce'=>$this->nonce
            )
        );
    }
}

/**
 *	Ethereum message -- Same as a transaction, except using this won't
 *  post the transaction to the blockchain.
 */
class Ethereum_Message
{
    public $to, $from, $gas, $gasPrice, $value, $data, $nonce;

    function __construct($from, $to, $data, $gas, $gasPrice, $value)
    {
        $this->from = $from;
        $this->to   = $to;
        $this->data = $data;
        $this->gas  = $gas;
        $this->gasPrice = $gasPrice;
        $this->value = $value;

    }

    function toArray()
    {
        return array(
            array
            (
                'from'=>$this->from,
                'to'=>$this->to,
                'data'=>$this->data,
                'gas'=>$this->gas,
                'gasPrice'=>$this->gasPrice,
                'value'=>$this->value,
            )
        );
    }
}

/**
 *	Ethereum transaction filter object
 */
class Ethereum_Filter
{
    private $fromBlock, $toBlock, $address, $topics;

    function __construct($fromBlock, $toBlock, $address, $topics)
    {
        $this->fromBlock = $fromBlock;
        $this->toBlock = $toBlock;
        $this->address = $address;
        $this->topics = $topics;
    }

    function toArray()
    {
        return array(
            array
            (
                'fromBlock'=>$this->fromBlock,
                'toBlock'=>$this->toBlock,
                'address'=>$this->address,
                'topics'=>$this->topics
            )
        );
    }
}

/**
 * 	Ethereum whisper post object
 */
class Whisper_Post
{
    private $from, $to, $topics, $payload, $priority, $ttl;

    function __construct($from, $to, $topics, $payload, $priority, $ttl)
    {
        $this->from = $from;
        $this->to = $to;
        $this->topics = $topics;
        $this->payload = $payload;
        $this->priority = $priority;
        $this->ttl = $ttl;
    }

    function toArray()
    {
        return array(
            array
            (
                'from'=>$this->from,
                'to'=>$this->to,
                'topics'=>$this->topics,
                'payload'=>$this->payload,
                'priority'=>$this->priority,
                'ttl'=>$this->ttl
            )
        );
    }
}