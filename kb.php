<?php
// echo phpinfo(); exit;
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://kb.rootcapture.com/api/postData',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_SSL_VERIFYPEER => false,
//   CURLOPT_VERBOSE => true,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_COOKIEJAR => 'cookie.txt',
//   CURLOPT_COOKIEFILE => 'cookie.txt',
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>'{
//     "first_name": "Ank",
//     "last_name": "Pandey",
//     "password": "12345678",
//     "email_address" : "ank@yopmail.com"
// }',
//   CURLOPT_HTTPHEADER => array(
//     'x-api-key: kbroot-091221-021195-220893',
//     'Content-Type: application/json',
//     'Cookie: z_session=snqtu48tr6k9vkg3cfmmip9ch5bsi81r'
//   ),
// ));
// print_r($curl);
// $response = curl_exec($curl);
// print_r($response);
// curl_close($curl);
?>
<form action="https://kb.rootcapture.com/api/postData" method="POST" >
<input name="first_name" value="Ank">
<input name="last_name" value="Pandey">
<input name="password" value="12345678">
<input name="email_address" value="ank@yopmail.com">
<input type="submit">

</form>
