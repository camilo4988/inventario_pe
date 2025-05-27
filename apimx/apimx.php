

    <?php
        /**
         * Your API Key: e10e6add80aa28033b1b1149
         */

if(isset($_POST["valor"])) {


    //SOLICITUD A API
    $valor_=floatval($_POST["valor"]);
    $apikey= "https://v6.exchangerate-api.com/v6/e10e6add80aa28033b1b1149/pair/USD/COP/{$valor_}";
    $iniciarCurl=curl_init($apikey);
    //curl_setopt($iniciarCurl, CURLOPT_URL,"");  
    
    
    //RECOLECCION DE INFORMACION
    curl_setopt($iniciarCurl,CURLOPT_RETURNTRANSFER,true) ;//trae la info de la solicitud
    //respuesta
    $response=curl_exec($iniciarCurl);
   // print_r($response);

}
    if(curl_errno($iniciarCurl)){
        echo "ocurrio un error".curl_error($iniciarCurl);
    }
    curl_close($iniciarCurl);


    //INTERPRETACION Y RESPUESTA
    $datos=json_decode($response,true);
    //info de la solicitud respuesta de la api, transformada en arrays
    //print_r($datos['conversion_result']);

    $valorUSD=$datos['conversion_rate'];

    echo '1USD equivale a: '.$valorUSD.', por lo tanto el valor ingresado de'.$valor_.' dolares, corresponde a:  '.$datos['conversion_result'].'   pesos colombianos';
?>