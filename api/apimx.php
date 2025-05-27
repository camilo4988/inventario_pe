

    <?php
        /**
         * Your API Key: e10e6add80aa28033b1b1149
         */

if(isset($_POST["valor"])) {
    $valor=floatval($_POST["valor"]);
    $apikey= "https://v6.exchangerate-api.com/v6/e10e6add80aa28033b1b1149/latest/USD";
    $iniciarCurl=curl_init();
    //curl_setopt($iniciarCurl, CURLOPT_URL,"");    
    curl_setopt($iniciarCurl,CURLOPT_RETURNTRANSFER,true) ;//trae la info de la solicitud
    //respuesta
    $response=curl_exec($iniciarCurl);
    print_r($response);

}


        $channel=curl_init();
        $endpoint="https://pokeapi.co/api/v2/pokemon/26";

        curl_setopt($channel,CURLOPT_URL,$endpoint) ;
        curl_setopt($channel,CURLOPT_RETURNTRANSFER,true) ;
        $response=curl_exec($channel);

        if (curl_errno($channel)) {
           // print_r('emtrp'); die;
            echo "error al conectarse al api pokemon".curl_error($channel)."";
            # code...
        }else {
                        

            
            curl_close($channel);

            $pokemon_data= json_decode($response,true);
           // print_r($response); die;

           // Iniciar tabla
            echo "<table border='1' cellpadding='10' cellspacing='0'>";

            // Fila: Nombre / Imagen
            echo "<tr>";
            echo "<td><strong>" . strtoupper($pokemon_data['name']) . "</strong></td>";
            echo "<td><img src='" . $pokemon_data['sprites']['front_default'] . "' alt='Imagen de " . $pokemon_data['name'] . "'></td>";

            echo "</tr>";

                                   
            // Fila: ID
            echo "<tr><td><strong>Identificador</strong></td><td>" . $pokemon_data['id'] . "</td></tr>";

            // Base Experience
            echo "<tr><td><strong>Base experience</strong></td><td>" . $pokemon_data['base_experience'] . "</td></tr>";

            // Altura
            echo "<tr><td><strong>Altura</strong></td><td>" . $pokemon_data['height'] . "cms </td></tr>";

            // Peso
            echo "<tr><td><strong>Peso</strong></td><td>" . $pokemon_data['weight'] . "grs</td></tr>";

            // Forma base
            echo "<tr><td><strong>¿Está en forma base?</strong></td><td>" . ($pokemon_data['is_default'] ? 'Sí' : 'No') . "</td></tr>";

            // Species
            echo "<tr><td><strong>Especie</strong></td><td>" . $pokemon_data['species']['name'] . " - <a href='" . $pokemon_data['species']['url'] . "'>Ver especie</a></td></tr>";

            // Habilidades
            echo "<tr><td><strong>Habilidades</strong></td><td>";
            echo "A continuación se enumeran las habilidades de " . $pokemon_data['name'] . ":<br><ul>";
            foreach ($pokemon_data['abilities'] as $index => $habilidad) {
                echo "<li>Habilidad " . ($index + 1) . ": -" . $habilidad['ability']['name'] . "</li>";
            }
            echo "</ul></td></tr>";

            // Cerrar tabla
            echo "</table>";
        }
            ?>
  