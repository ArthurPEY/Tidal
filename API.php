<?php
/*
Description du fonctionnement : voir le README
*/

header('Content-Type: application/json');
$valeur_bd = array();

// ================= Connection to DataBase ================= //
try {
    $dbh = new PDO('pgsql:host=localhost;port=5432;dbname=postgres', 'pgtp', 'tp');
}
catch(Exception $e) {
    echo "Err";
}

// ================= SQL request ================= //
$sql = "SELECT
    p.type as type,
    p.desc as patho,
    m.nom as mer,
    s.desc as symp
    FROM
    symptome AS s
    INNER JOIN symptPatho AS sp
    ON sp.idS = s.idS
    INNER JOIN patho AS p
    ON sp.idP = p.idP
    INNER JOIN meridien AS m
    ON m.code = p.mer";


// ================= GET or POST ================= //
$method = "GET";
$valeur_bd['method'] = $method;
switch ($method) {
// ================= POST Method ================= //
    case "POST" : 
        if (!empty($_POST['type'])){
            $valeur_bd['type_vide'] = false;
            $var = $_POST["type"];
            $sql = $sql . " WHERE p.type LIKE '$var' ";  
        }
        else {$valeur_bd['type_vide'] = true;}

        if (!empty($_POST['patho'])){
            $valeur_bd['patho_vide'] = false;
            $var = $_POST["patho"];
            if ($valeur_bd['type_vide']==true){
                $sql = $sql . " WHERE p.desc LIKE '$var' ";
            }
            else {
                $sql = $sql . " and p.desc LIKE '$var' ";
            }   
        }
        else {$valeur_bd['patho_vide'] = true;}

        if (!empty($_POST['mer'])){
            $valeur_bd['mer_vide'] = false;
            $var = $_POST["mer"];
            if (($valeur_bd['type_vide']==true) && ($valeur_bd['patho_vide']==true)){
                $sql = $sql . " WHERE m.nom LIKE '$var' "; 
            }
            else {
                $sql = $sql . " and m.nom LIKE '$var' "; 
            } 
        }
        else {$valeur_bd['mer_vide'] = true;}

        if (!empty($_POST['recherche'])){
            $valeur_bd['rech_vide'] = false;
            $var = $_POST["recherche"];
            if (($valeur_bd['type_vide']==true) && ($valeur_bd['patho_vide']==true) && ($valeur_bd['mer_vide']==true)){
                $sql = $sql . " WHERE s.desc LIKE '%$var%' "; 
            }
            else {
                $sql = $sql . " and s.desc LIKE '%$var%' "; 
            } 
        }
        else {$valeur_bd['rech_vide'] = true;}

// ================= GET Method ================= //
     case "GET" : 
        if ($_GET['type'] != NULL){
            $valeur_bd['type_vide'] = false;
            $var = $_GET['type'];
            $sql = $sql . " WHERE p.type LIKE '$var' ";  
        }
        else {$valeur_bd['type_vide'] = true;}

        if ($_GET['patho'] != NULL){
            $valeur_bd['patho_vide'] = false;
            $var = $_GET['patho'];
            if ($valeur_bd['type_vide']==true){
                $sql = $sql . " WHERE p.desc LIKE '%$var%' ";
            }
            else {
                $sql = $sql . " and p.desc LIKE '%$var%' ";
            }   
        }
        else {$valeur_bd['patho_vide'] = true;}

        if ($_GET['mer'] != NULL){
            $valeur_bd['mer_vide'] = false;
            $var = $_GET['mer'];
            if (($valeur_bd['type_vide']==true) && ($valeur_bd['patho_vide']==true)){
                $sql = $sql . " WHERE m.nom LIKE '$var' "; 
            }
            else {
                $sql = $sql . " and m.nom LIKE '$var' "; 
            } 
        }
        else {$valeur_bd['mer_vide'] = true;}

        if ($_GET['rech'] != NULL){
            $valeur_bd['rech_vide'] = false;
            $var = $_GET['rech'];
            if (($valeur_bd['type_vide']==true) && ($valeur_bd['patho_vide']==true) && ($valeur_bd['mer_vide']==true)){
                $sql = $sql . " WHERE s.desc LIKE '%$var%' "; 
            }
            else {
                $sql = $sql . " and s.desc LIKE '%$var%' "; 
            } 
        }
        else {$valeur_bd['rech_vide'] = true;}
}

$sql = $sql . " ORDER BY p.type";

// ================= Execution of SQL request ================= //
$sth = $dbh->prepare($sql);
$sth->execute();
$data = $sth->fetchAll(PDO::FETCH_ASSOC);

$valeur_bd['nbr_data'] = count($data);
$valeur_bd['DataBase'] = $data;


// ================= Echo JSON result of request ================= //
echo json_encode($valeur_bd);

?>

