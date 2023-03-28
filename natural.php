<?php
echo "100";
$dbh = new PDO('pgsql:host=localhost;port=5432;dbname=postgres', 'pgtp', 'tp');
echo "2";

$sql = 'SELECT * FROM patho';
$dbh->beginTransaction();
try {
  $sth = $dbh->prepare($sql);
  $sth->execute();
  $data = $sth->fetchAll();
  $dbh->commit();
} catch(PDOException $e) {
  $dbh->rollback();
}

var_dump($data);
?>