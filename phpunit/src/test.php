<?php
require "../vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// $keyPair = sodium_crypto_sign_keypair();
$keyPair ="Hello";

var_dump(phpinfo());
die();

$privateKey = base64_encode(sodium_crypto_sign_secretkey($keyPair));

$publicKey = base64_encode(sodium_crypto_sign_publickey($keyPair));

$payload = [
    'iss' => 'example.org',
    'aud' => 'example.com',
    'iat' => 1356999524,
    'nbf' => 1357000000
];

$jwt = JWT::encode($payload, $privateKey, 'EdDSA');
echo "Encode:\n" . print_r($jwt, true) . "\n";

$decoded = JWT::decode($jwt, new Key($publicKey, 'EdDSA'));
echo "Decode:\n" . print_r((array) $decoded, true) . "\n";