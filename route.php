<?php
// ================= Chargement TWIG ================= //

spl_autoload_register(function ($classname) {
  $filename = ltrim(str_replace('\\', '/', $classname)) . '.php';
  if (file_exists($filename))
    require_once $filename;
});

$loader = new \Twig\Loader\FilesystemLoader('.');
$twig = new \Twig\Environment($loader);

// ================= \Twig load ================= //

// ================= Fonction requete SQL ================= //

function query($dbh,$sql){
  $dbh->beginTransaction();
  try {
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $data = $sth->fetchAll();
    $dbh->commit();
  } catch(PDOException $e) {
    $dbh->rollback();
  }
  return($data);
  }


// ================= \Fonction requete SQL ================= //

// ================= Gestion admin ================= //

$accountsDB = new PDO('pgsql:host=localhost;port=5432;dbname=accounts', 'pgtp', 'tp');

$sqlAllAccounts = "SELECT * FROM accounts";

$allAcc = query($accountsDB,$sqlAllAccounts);


$currentToken = $_COOKIE["login"];

$autorisedTokenConnected=[];
$userAccount=[];
for ($i=0;$i<count($allAcc);$i++) {
  array_push($userAccount,$allAcc[$i]['username']);
  if($allAcc[$i]['token']!=null){
    array_push($autorisedTokenConnected,$allAcc[$i]['token']);
  }
  if ($currentToken!=null && $allAcc[$i]['token']==$currentToken && $allAcc[$i]['isadmin']=='true'){
    $isAdmin=True;
  }
}

if (isset($_POST["delete"])){
  try{
    $user = $_POST['userList'];
    $userRemoveQuery = "DELETE FROM accounts WHERE username='$user';"; // SQL suppression utilisateur
    query($accountsDB,$userRemoveQuery); // Requete SQL 'userRemoveQuery' sur database 'accounts' (objet $accountsDB)
    $_POST['userList']=null;
    $url = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: ".$url); // Redirige vers le meme lien pour ne pas renvoyer le formulaire de suppresion
  }
  catch(PDOException $e) {}
}

if (isset($_POST["addAdmin"])){
  try{
    $user = $_POST['userList'];
    $userAddAdminQuery = "UPDATE accounts SET isadmin = 'true' WHERE username LIKE '$user';"; // SQL suppression utilisateur
    query($accountsDB,$userAddAdminQuery); // Requete SQL 'userRemoveQuery' sur database 'accounts' (objet $accountsDB)
    $_POST['userList']=null;
    $url = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: ".$url); // Redirige vers le meme lien pour ne pas renvoyer le formulaire de suppresion
  }
  catch(PDOException $e) {}
}

if (isset($_POST["removeAdmin"])){
  try{
    $user = $_POST['userList'];
    $userRemoveAdminQuery = "UPDATE accounts SET isadmin = 'false' WHERE username LIKE '$user';"; // SQL suppression utilisateur
    query($accountsDB,$userRemoveAdminQuery); // Requete SQL 'userRemoveQuery' sur database 'accounts' (objet $accountsDB)
    $_POST['userList']=null;
    $url = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: ".$url); // Redirige vers le meme lien pour ne pas renvoyer le formulaire de suppresion
  }
  catch(PDOException $e) {}
}

// ================= \Gestion admin ================= //

// ================= Connexion ================= //

$usernameLogin = $_POST['username'];
$passwordLogin = $_POST['password'];


$sqlAccounts = "SELECT acc.username,acc.password FROM accounts as acc
                WHERE acc.username LIKE '$usernameLogin'
                AND acc.password LIKE '$passwordLogin' ";


$dataAccount = query($accountsDB,$sqlAccounts); // Requete SQL 'sqlAccounts' sur database 'accounts' (objet $accountsDB)

$isConnected=false;

$autorisedToken=[];
for ($i=0;$i<count($dataAccount);$i++) {
  array_push($autorisedToken,$dataAccount[$i]['token']);
}
$currentToken = $_COOKIE['login'];

if (isset($_POST["connect"])){
  if (($dataAccount[0]['username']==$usernameLogin) && ($dataAccount[0]['password']==$passwordLogin)
    && ($usernameLogin!=null) && ($passwordLogin!=null)){
    $token = bin2hex(random_bytes(20));
    setcookie("login",$token);

    $setTokenLogin = "UPDATE accounts SET token = '$token' WHERE username LIKE '$usernameLogin'";
    query($accountsDB,$setTokenLogin);
  
    $loginFile = file_get_contents('HTML/loged.twig', true); // HTML quand connecté
    $isConnected=true;
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: ".$url); // Redirige vers le meme lien pour ne pas renvoyer le formulaire de suppresion
    
  }
  else{
    $loginFile = file_get_contents('HTML/login.twig', true); // HTML quand deconnecte
  }
}
elseif(in_array($currentToken, $autorisedTokenConnected) && $currentToken!=null){
  $loginFile = file_get_contents('HTML/loged.twig', true); // HTML quand connecté
  $isConnected=true;
}
else{
  $loginFile = file_get_contents('HTML/login.twig', true); // HTML quand deconnecte
}


// ================= \Connexion ================= //

// ================= Deconnexion ================= //


if ($_POST['disconnect']){
  $tokenToDisconnect = $_COOKIE['login'];
  $resetTokenLogin = "UPDATE accounts SET token = null WHERE token LIKE '$tokenToDisconnect'";
  query($accountsDB,$resetTokenLogin);
  unset($_COOKIE['login']);
  setcookie('login', null, -1, '/'); // Supression du cookie de connexion
  $loginFile = file_get_contents('HTML/login.twig', true);
  $url = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  header("Location: ".$url);
}

// ================= \Deconnexion ================= //

// ================= BDD pathologies ================= //

$dbh = new PDO('pgsql:host=localhost;port=5432;dbname=postgres', 'pgtp', 'tp');


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
  ON m.code = p.mer
ORDER BY
  p.type";
  
$data = query($dbh,$sql); // Requete SQL 'sql' sur database 'postgres' (objet $dbh)


// ================= \BDD pathologies ================= //

// ================= Traitement BBD pathologies pour envoie TWIG ================= //

$typeSet[] = "Types";
$pathologieSet[] = "Pathologie";
$meridienSet[] = "Méridien";

for ($i=0;$i<count($data);$i++) {
  array_push($typeSet,$data[$i][0]);
  array_push($pathologieSet,$data[$i][1]);
  array_push($meridienSet,$data[$i][2]);
}
$typeSet = array_unique($typeSet);
$pathologieSet = array_unique($pathologieSet);
$meridienSet = array_unique($meridienSet);

// ================= \Traitement BBD pathologies pour envoie TWIG ================= //

// ================= Formualre d'inscription ================= //

/*value erreur initial*/
$succed=-1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST['inscriptionsubmit'])){
    //recuperations des differentes informations du formulaire
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    
    if ($password == $confirm_password){ //verfication confirmation de MDP   
      $sqlExistAcc = "SELECT username from accounts WHERE username LIKE '$name'";   
      $data= query($accountsDB,$sqlExistAcc);
      
      if (count($data) == 0){ //verfication que l'identifiant n'existe pas deja
          $sqlCreateAcc = "INSERT INTO accounts (username,password,email,dob,gender) 
          VALUES ('$name','$password','$email','$dob','$gender')";
          query($accountsDB,$sqlCreateAcc);
          $succed=1;
          $message = "Bonjour $name votre compte à été créé avec succès.";
      }
      else{$message = "Cet utilisateur existe deja";
           $succed=0;} // Value erreur mauvais mots de passe           
    }
    else{$message = "Erreur saisi de mot de passe";
         $succed=0;} /*value erreur mauvais mots de passe*/
  }
}

// ================= \Formualre d'inscription ================= //


// ================= Render page TWIG ================= //

try{
  $page = $_GET['page'];
  if (is_null($page)) {
  echo $twig->render('HTML/header.twig', ['Titre' => 'Acupuncture', 'loginFile' => $loginFile ,'isAdmin' => $isAdmin]);
  echo $twig->render('HTML/index.twig', []);
  echo $twig->render('HTML/footer.twig', []);
  }
  elseif ("_$page"=='_index') {
    echo $twig->render('HTML/header.twig', ['Titre' => 'Acupuncture', 'loginFile' => $loginFile ,'isAdmin' => $isAdmin]);
    echo $twig->render('HTML/index.twig', []);
    echo $twig->render('HTML/footer.twig', []);
  }
  elseif ("_$page"=='_pathologies'){
    echo $twig->render('HTML/header.twig', ['Titre' => 'Pathologies', 'loginFile' => $loginFile ,'isAdmin' => $isAdmin]);
    echo $twig->render('HTML/pathologies.twig', ['data' => $data,'typeSet'=>$typeSet,
      'pathologieSet'=>$pathologieSet,'meridienSet'=>$meridienSet, "isConnected" => $isConnected]);
    echo $twig->render('HTML/footer.twig', []);
  }
  elseif ("_$page"=='_inscription'){
    echo $twig->render('HTML/header.twig', ['Titre' => 'Inscription', 'loginFile' => $loginFile ,'isAdmin' => $isAdmin]);
    if($succed==1){echo $twig->render('HTML/succed.twig',['message' => $message]);} 
    elseif($succed==0){echo $twig->render('HTML/erreur.twig',['message' => $message]);}
    echo $twig->render('HTML/inscription.twig',['message' => $message]);
    echo $twig->render('HTML/footer.twig', []);
  }

  elseif ("_$page"=='_paneladmin' && $isAdmin==True){
    echo $twig->render('HTML/header.twig', ['Titre' => 'Panel admin', 'loginFile' => $loginFile ,'isAdmin' => $isAdmin]);
    echo $twig->render('HTML/admin.twig', ['userAccount' => $userAccount]);
    echo $twig->render('HTML/footer.twig', []);
  }
  else{
    echo $twig->render('HTML/header.twig', ['Titre' => 'Acupuncture', 'loginFile' => $loginFile ,'isAdmin' => $isAdmin]);
    echo $twig->render('HTML/index.twig', []);
    echo $twig->render('HTML/footer.twig', []);
  }
}
catch(\Exception $e){
  echo "Err";
  $page = "index";
}



// ================= \Render page TWIG ================= //

?>
