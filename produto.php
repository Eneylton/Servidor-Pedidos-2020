<?php
 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: PUT, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/json; charset=utf-8");


include "Connect/connect.php";

$postjson = json_decode(file_get_contents('php://input'),true);

if($postjson['crud'] == "listar-produtos"){

        $data = array();
       
        $query = mysqli_query($mysqli, "SELECT 
        p.id,
        p.referencia as ref,
        p.foto as foto,
        m.id as marca_id,
        md.id as mod_id,
        c.id as cat_id,
        f.id as fab_id,
        m.descricao as marca,
        md.descricao as modelo,
        c.descricao as cat,
        f.descricao as fab
    FROM
        produto AS p
            INNER JOIN
        marca AS m ON (p.marca_id = m.id)
         INNER JOIN
        modelo AS md ON (p.modelo_id = md.id)
           INNER JOIN
        categoria AS c ON (p.categoria_id = c.id)
          INNER JOIN
        fabricante AS f ON (p.fabricante_id = f.id) order by p.id  DESC LIMIT $postjson[start], $postjson[limit]");

        while($row = mysqli_fetch_array($query)){
            $data[] = array(
				'id'           => $row['id'],
				'ref'          => $row['ref'],
				'cat'          => $row['cat'],
				'marca'        => $row['marca'],
				'fab'          => $row['fab'],
				'foto'         => $row['foto'],
				'cat_id'       => $row['cat_id'],
				'marca_id'     => $row['marca_id'],
				'fab_id'       => $row['fab_id'],
				'mod_id'       => $row['mod_id'],
				'modelo'       => $row['modelo']
				
            );
        }

        if($query) $result = json_encode(array('success' => true,'result' =>$data));
        else $result = json_encode(array('success'=> false));
        echo $result;

    }


	elseif($postjson['crud'] == "adicionar"){
       
        $data = array();

        $radom     = date('Y-m-d_H_i_s');

        $entry     = base64_decode($postjson['foto']);

        $img       = imagecreatefromstring($entry);

        $directory = "./imgs/img_user".$radom.".jpg";

        imagejpeg($img, $directory);

        imagedestroy($img);
    
        $query   = mysqli_query($mysqli, "INSERT INTO produto SET
                   
                   referencia         = '$postjson[referencia]',
                   foto               = '$directory',
                   marca_id           = '$postjson[marca_id]',
                   modelo_id          = '$postjson[modelo_id]',
                   categoria_id       = '$postjson[categoria_id]',
                   fabricante_id      = '$postjson[fabricante_id]'


                   ");
    
        $idadd = mysqli_insert_id($mysqli);
    
        if($query) $result = json_encode(array('success' => true, 'idadd' => $idadd));
        else $result = json_encode(array('success'=> false));
        echo $result;

        }
    
        elseif($postjson['crud'] == "editar"){
  
        if($postjson['foto'] != "" )  {

        $data = array();

        $radom     = date('Y-m-d_H_i_s');

        $entry     = base64_decode($postjson['foto']);

        $img       = imagecreatefromstring($entry);

        $directory = "./imgs/img_user".$radom.".jpg";

        imagejpeg($img, $directory);

        imagedestroy($img);
  
        $query   = mysqli_query($mysqli, "UPDATE produto SET
	           
                   referencia         = '$postjson[referencia]',
                   foto               = '$directory',
                   marca_id           = '$postjson[marca_id]',
                   modelo_id          = '$postjson[modelo_id]',
                   categoria_id       = '$postjson[categoria_id]',
                   fabricante_id      = '$postjson[fabricante_id]'  WHERE id  = '$postjson[id]'");
    

        if($query) $result = json_encode(array('success'=>true));
        else $result = json_encode(array('success'=>false));
        echo $result;

        }else{
            $query   = mysqli_query($mysqli, "UPDATE produto SET
	           
                   referencia         = '$postjson[referencia]',
                   marca_id           = '$postjson[marca_id]',
                   modelo_id          = '$postjson[modelo_id]',
                   categoria_id       = '$postjson[categoria_id]',
                   fabricante_id      = '$postjson[fabricante_id]'  WHERE id  = '$postjson[id]'");
    

        if($query) $result = json_encode(array('success'=>true));
        else $result = json_encode(array('success'=>false));
        echo $result;

        }
       
    }
    elseif ($postjson['crud'] == "deletar") {

        $query   = mysqli_query($mysqli, "DELETE FROM produto WHERE id  = '$postjson[id]'");
    
    
        if ($query) $result = json_encode(array('success' => true));
        else $result = json_encode(array('success' => false, 'msg' => 'error, Por favor, tente novamente... '));
        echo $result;
    }
?>