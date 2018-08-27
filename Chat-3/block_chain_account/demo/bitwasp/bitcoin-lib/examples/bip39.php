<?php

use BitWasp\BitcoinLib\BIP32;
use BitWasp\BitcoinLib\BIP39\BIP39;

require_once dirname(dirname(dirname(__DIR__))).'/autoload.php';

/*$password = "my-oh-so-secret-password";
$entropy = BIP39::generateEntropy(256);

$mnemonic = BIP39::entropyToMnemonic($entropy);*/

$mnemonic = 'blast doll nation charge critic loyal wine attitude history skin acquire stick';
//$password = 'woailidi521';
$password = '';

$seed = BIP39::mnemonicToSeedHex($mnemonic, $password);

//unset($entropy); // ignore, forget about this, don't use it!

echo $mnemonic.PHP_EOL; // this is what you print on a piece of paper, etc
echo $password.PHP_EOL; // this is secret of course
echo $seed.PHP_EOL; // this is what you use to generate a key

$key = BIP32::master_key($seed); // enjoy
var_dump($key);
