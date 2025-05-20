<?php
    namespace app\models;
    use PDO;
    
    $connection="/../../config/server.php";
    if (file_exists(__DIR__.$connection) ){
        require_once __DIR__.$connection;
    }

    /***
    atributos privados, solo accesibles desde esta clase /
     */
    class mainModel{
        private $server=DB_SERVER;
        private $db= DB_NAME;
        private $user= DB_USER;
        private $password= DB_PASSWORD;
        
        /**
         * Solo accesible desde la propia clase y clases hijas
         * @var 
         */
        protected function conectar(){
            $conexion= new PDO("mysql:host=".$this->server.";dbname=".$this->db,
            $this->user,$this->password) ;
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        }
/*
this para acceder a atributos y metodos de la clase
*/     
        protected function ejecutarConsulta($consulta){
            /**
             * nos conectamos a db y preparamos consulta
             */
            $sql=$this->conectar()->prepare($consulta);
            /**ejecutamos consulta */
            $sql->execute();
            return $sql;

        }

        protected function prepararConsulta($consulta): {
            /**
             * nos conectamos a db y preparamos consulta
             */
            $sql=$this->conectar()->prepare($consulta);
            return $sql;
            

        }
        /**funcion para evitar inyeccion sql */
        public function limpiarCadena($cadena){
            $palabras=["<script>","</script>","<script src","<script type=",
            "SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","<",">","==","=",";","::"];

			$cadena=trim($cadena);
            //quita barras invertidas
			$cadena=stripslashes($cadena);
            
            //recorro array palabras para quitarlas de la cadena
			foreach($palabras as $palabra){
				$cadena=str_ireplace($palabra, "", $cadena);
			}

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			return $cadena;

        }

        protected function verificarDatos($filtro,$cadena){
            if (preg_match("/^".$filtro."$/",$cadena)) {//caracteres admitidos y tamaño
                return false;
            }else{
                return true;
            }
        }

        protected function guardarDatos($tabla, $datos){
            if (empty($tabla) || empty($datos) || !is_array($datos)) {
                return false;
            }

            $columnas = [];
            $valores = [];

            foreach ($datos as $key) {
                if (!isset($key["campo_nombre"], $key["campo_marcador"], $key["campo_valor"])) {
                    return false; // Validación adicional por si algún elemento está mal formado
                }

                $columnas[] = $key["campo_nombre"];
                $valores[] = $key["campo_marcador"];
            }

            $query = "INSERT INTO $tabla (" . implode(",", $columnas) . ") VALUES (" . implode(",", $valores) . ")";

            try {
                $conexion = $this->conectar();
                $sql = $conexion->prepare($query);

                foreach ($datos as $key) {
                    $sql->bindParam($key["campo_marcador"], $key["campo_valor"]);
                }

                return $sql->execute(); // Devuelve true si la ejecución fue exitosa, false si falló
            } catch (\Exception  $e) {
                // Opcional: puedes loguear el error aquí o manejarlo de otra forma
                 error_log("Error al guardar datos: " . $e->getMessage());
                return false;
            }
        }

        public function seleccionarDatos($tipo, $tabla, $campo, $id){
            // se ejecuta limpiar cadena en parametros para evitar inyecciones sql
            $tipo=$this->limpiarCadena($tipo);
            $tabla=$this->limpiarCadena($tabla);
            $campo=$this->limpiarCadena($campo);
            $id=$this->limpiarCadena($id);
            $consulta="SELECT * FROM $tabla WHERE $campo=:ID";

            if ($tipo=="Unico") {
                
                $sql=$this->prepararConsulta($consulta);
                $sql->bindParam(":ID",$id);
            }else{
                $consulta="SELECT $campo FROM $tabla";
                $sql=$this->prepararConsulta($consulta);

            }

            $sql->execute();
            return $sql;  
        }

        protected function actualizarDatos($tabla, $datos, $where){
         
            $query="UPTADE $tabla SET";

            //recorro el array de datos y a cada dato a excepcion del primero, le pongo una coma 
            $c=0;
            foreach ($datos as $key) {
                    if ($c>=1) {
                        $query.= ","

                    }
                    //A CADA CAMPO del array de datos, le asigno su marcador
                    $query.=$key["campo_nombre"]."=".$key["campo_marcador"];
                    $c++;
                }
                $query.=" Where ".$where["condicion_campo"]."=".$where["campo_marcador"];

                $sql=$this->prepararConsulta($query);
                foreach ($datos as $key) {
                    $sql->bindParam($key["campo_marcador"], $key["campo_valor"]);
                }
                $sql->bindParam($where["condicion_marcador"], $where["condicion_valor"]);

                $sql->execute();
                return $sql;  

        }
        
                                ///funcion opcional para ver si sirve
                                public function ejecutaryRetornar($sql){
                                    $sql->execute();
                                    return $sql;            

                                }

        protected function eliminarRegistros($tabla, $campo, $where){
         
            $consulta="DELETE FROM $tabla WHERE $campo=:ID";
            $sql=$this->prepararConsulta($consulta);
            $sql->bindParam(":ID",$where);
            $sql->ejecutaryRetornar($sql);


        }

             
            
    }

    