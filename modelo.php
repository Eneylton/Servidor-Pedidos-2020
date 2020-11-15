<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: PUT, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: text/json; charset=utf-8");


include "Connect/connect.php";

$postjson = json_decode(file_get_contents('php://input'), true);

if ($postjson['crud'] == "listar-modelos") {

    $data = array();

    $query = mysqli_query($mysqli, "SELECT 
        m.id, m.descricao AS veiculo, m.marca_id as marc_id, mc.descricao AS marca
    FROM
        modelo AS m
            INNER JOIN
        marca AS mc ON (m.marca_id = mc.id)
    ORDER BY mc.descricao ASC LIMIT $postjson[start], $postjson[limit]");

    while ($row = mysqli_fetch_array($query)) {
        $data[] = array(
            'id'           => $row['id'],
            'marca'        => $row['marca'],
            'marc_id'      => $row['marc_id'],
            'veiculo'      => $row['veiculo']

        );
    }

    if ($query) $result = json_encode(array('success' => true, 'result' => $data));
    else $result = json_encode(array('success' => false));
    echo $result;


} 

elseif($postjson['crud'] == "listar-model"){

    $data = array();
    
    $query = mysqli_query($mysqli, "SELECT * FROM modelo as c WHERE marca_id = $postjson[marca_id] ORDER BY c.descricao asc ");

    while($row = mysqli_fetch_array($query)){
        $data[] = array(
            'id'           => $row['id'],
            'descricao'    => $row['descricao'],
            'marca_id'     => $row['marca_id']
            
        );
    }

    if($query) $result = json_encode(array('success' => true,'result' =>$data));
    else $result = json_encode(array('success'=> false));
    echo $result;

}


elseif ($postjson['crud'] == "adicionar") {

    $data = array();

    $query   = mysqli_query($mysqli, "INSERT INTO modelo SET
                   
                   descricao         = '$postjson[descricao]',
                   marca_id          = '$postjson[marca_id]'
                   
                   ");

    $idadd = mysqli_insert_id($mysqli);

    if ($query) $result = json_encode(array('success' => true, 'idadd' => $idadd));
    else $result = json_encode(array('success' => false));
    echo $result;

    } 


    elseif ($postjson['crud'] == "editar") {


    $query   = mysqli_query($mysqli, "UPDATE modelo SET
	           
         
               descricao         = '$postjson[descricao]',
               marca_id          = '$postjson[marca_id]' WHERE id  = '$postjson[id]'");


    if ($query) $result = json_encode(array('success' => true));
    else $result = json_encode(array('success' => false));
    echo $result;
    } 

    
    elseif ($postjson['crud'] == "deletar") {

    $query   = mysqli_query($mysqli, "DELETE FROM modelo WHERE id  = '$postjson[id]'");


    if ($query) $result = json_encode(array('success' => true));
    else $result = json_encode(array('success' => false, 'msg' => 'error, Por favor, tente novamente... '));
    echo $result;
}
