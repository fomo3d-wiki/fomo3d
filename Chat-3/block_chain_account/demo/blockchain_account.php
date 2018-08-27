<?php
/**
 * Created by PhpStorm.
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/27
 * @Time: 19:57
 */
use BitWasp\BitcoinLib\BIP32;
use BitWasp\BitcoinLib\BIP39\BIP39;
class AccountController
{

    const ADD_PREFIX_0X = '0x';

    public $json_data;

    /**
     * 单例对象
     * @var object
     */
    protected $_object;

    /**
     * 解析全部助记词
     * @param $mnemonic
     * @param string $password
     * @return bool
     * @throws \Exception
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/2
     * @Time: 14:29
     */
    public function decodeAllMnemonicAction($mnemonic, $password = '')
    {

        $mnemonic = trim($mnemonic);

        /**
         * 助记词--->种子
         *
         * BIP39 采用 PBKDF2 函数推算种子，其参数如下：
         * 助记词句子作为密码
         * "mnemonic" + passphrase 作为盐
         * 2048 作为重复计算的次数
         * HMAC-SHA512 作为随机算法
         * 512 位(64 字节)是期望得到的密钥长度
         */
        $seed = BIP39::mnemonicToSeedHex($mnemonic, $password);

        /**
         * 种子--->根钥
         */
        $root_key = BIP32::master_key($seed);

        /**
         * 60' 代表 以太坊
         * BTC使用m/44'/0'/0'/0  的 Extended Public Key 生成 m/44'/0'/0'/0/*
         * ETH使用m/44'/60'/0'/0 的 Extended Public Key 生成 m/44'/60'/0'/0/*
         *
         * 规则:参考:https://github.com/ethereum/EIPs/issues/84
         * m / purpose' / coin_type' / account' / change / address_index
         */
        $def = "44'/60'/0'/0/0";

        /**
         * 根钥--->扩展私钥
         */
        $ex_private_key = BIP32::build_key($root_key, 'm/' . $def);


        /**
         * 扩展私钥--->账户信息 (公钥+私钥+账户地址)
         */
        $wallet = array();
        BIP32::bip32_keys_to_wallet($wallet, array($ex_private_key));

        $account = [];
        $address = '';
        foreach ($wallet as $iterm) {
            $account['public_key'] = self::ADD_PREFIX_0X . $iterm['public_key'];
            $account['private_key'] = self::ADD_PREFIX_0X . $iterm['private_key'];
            $address = substr($iterm['uncompressed_key'], 2);//删除'04'压缩标志
        }
        try {
            /**
             *以太坊Keccak-256
             */
            $address = $this->hashAction($address);
            $address = self::ADD_PREFIX_0X . substr($address, strlen($address) - 40, 40);//截取后四十位
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return 'ERR_API_NOT_AVAILABLE'.json_encode($error);
        }
        $account['address'] = $address;

        $this->json_data['seed'] = $seed;
        $this->json_data['root_key'] = $root_key;
        $this->json_data['ex_private_key'] = $ex_private_key;
        $this->json_data['account'] = $account;
        /**
         * 简单打印数据
         */
        exit($this->json_data);
    }

    /**
     * 以太坊hash地址
     * @param  mixed $str
     * @param  bool  $is_0x
     * @return mixed
     * Returns Keccak-256 (not the standardized SHA3-256) of the given data.
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/2
     * @Time: 14:23
     */
    public function hashAction($str, $is_0x=false)
    {
        if (!isset($str)) {
            return 'ERR_GET_HASH_FAIL';
        }
        if (!$is_0x) {
            $str = '0x'.$str;
        }
        $object = $this->_objectAction();
        try {
            return $object->web3_sha3($str);
        } catch (\Exception $e){
            $error = $e->getMessage();
            return 'ERR_API_NOT_AVAILABLE:'.json_encode($error);
        }
    }

    /**
     * 单例化
     * @return object
     * User: lidi
     */
    protected function _objectAction()
    {
        if (!$this->_object instanceof self) {
            $this->_object = new EtherClient();
        }
        return $this->_object;
    }
}