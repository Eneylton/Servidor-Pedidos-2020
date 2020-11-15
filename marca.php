<?php
 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: PUT, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/json; charset=utf-8");


include "Connect/connect.php";

$postjson = json_decode(file_get_contents('php://input'),true);

if($postjson['crud'] == "listar-marcas"){

        $data = array();
        
        $query = mysqli_query($mysqli, "SELECT * FROM marca as c ORDER BY c.id desc LIMIT $postjson[start], $postjson[limit]");

        while($row = mysqli_fetch_array($query)){
            $data[] = array(
				'id'           => $row['id'],
				'descricao'    => $row['descricao']
				
            );
        }

        if($query) $result = json_encode(array('success' => true,'result' =>$data));
        else $result = json_encode(array('success'=> false));
        echo $result;

    }

    

    elseif($postjson['crud'] == "listar-marc"){

        $data = array();
        
        $query = mysqli_query($mysqli, "SELECT * FROM marca as c ORDER BY c.id ");

        while($row = mysqli_fetch_array($query)){
            $data[] = array(
				'id'           => $row['id'],
				'descricao'    => $row['descricao']
				
            );
        }

        if($query) $result = json_encode(array('success' => true,'result' =>$data));
        else $result = json_encode(array('success'=> false));
        echo $result;

    }


    elseif($postjson['crud'] == "adicionar"){
       
        $data = array();
    
        $query   = mysqli_query($mysqli, "INSERT INTO marca SET
                   descricao          = '$postjson[descricao]'");
    
        $idadd = mysqli_insert_id($mysqli);
    
        if($query) $result = json_encode(array('success' => true, 'idadd' => $idadd));
        else $result = json_encode(array('success'=> false));
        echo $result;
    }

    
    elseif($postjson['crud'] == "editar"){
  
  
        $query   = mysqli_query($mysqli, "UPDATE marca SET
	           
         
        descricao  =  '$postjson[descricao]' WHERE id  = '$postjson[id]'");
    

        if($query) $result = json_encode(array('success'=>true));
        else $result = json_encode(array('success'=>false));
        echo $result;
    }

    elseif($postjson['crud'] == "deletar"){
  
        $query   = mysqli_query($mysqli, "DELETE FROM marca WHERE id  = '$postjson[id]'");
      
    
        if($query) $result = json_encode(array('success'=>true));
        else $result = json_encode(array('success'=>false, 'msg'=>'error, Por favor, tente novamente... '));
        echo $result;
    }

?>