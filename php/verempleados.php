<?php
include 'vars.php';

class conexSqlite3{
    private $nombre_fichero="";
    private $conexion=[
        "conex"=>NULL,
        "error"=>NULL
    ];
    function __construct($nf) {
        $this->nombre_fichero=$nf;
    }
    function open(){
        if (file_exists($this->nombre_fichero)) {
            $conex = new PDO("sqlite:" . $this->nombre_fichero);
            $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion["conex"]=$conex;
            $this->conexion["error"]=NULL;
            return $this->conexion;
        }else{
            $this->conexion["conex"]=NULL;
            $this->conexion["error"]="no existe el archivo";
            return $this->conexion;
        }
    
    }
    function close(){
        $this->conexion["conex"]=NULL;
    }
}


$obj=new conexSqlite3($nombre_fichero);

$conexion=$obj->open();
if(is_null($conexion["error"])){
    $conex=$conexion["conex"];
    try{
        $stmt = $conex->prepare('SELECT * FROM Empleados;');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //cerrar la bd
        $stmt=null;
        $obj->close();
        http_response_code(200);
        echo json_encode($data);
    }catch(PDOException $exc){
        http_response_code(400);
        echo "Error al abrir la base de datos----->".$exc->getMessage();
    }
}else{
    http_response_code(400);
    echo "Error al abrir la base de datos: ".$conexion["error"];
}

?>