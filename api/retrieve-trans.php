<?php
header("Access-Control-Allow-Origin: *");

$ben_cd = isset($_REQUEST['ben_cd']) ? $_REQUEST['ben_cd'] : '';

   // Define database connection parameters
   $hn      = '10.152.249.61';
   $un      = 'mydb';
   $pwd     = '';
   $db      = 'pmaj';
   $cs      = 'utf8';

   // Set up the PDO parameters
   $dsn 	= "mysql:host=" . $hn . ";port=3306;dbname=" . $db . ";charset=" . $cs;
   $opt 	= array(
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                       );
   // Create a PDO instance (connect to the database)
   $pdo 	= new PDO($dsn, $un, $pwd, $opt);
   $data    = array();


   // Attempt to query database table and retrieve data
   try {


      $stmt ="SELECT bill_no,DATE_FORMAT(bill_dt,'%d/%m/%y') as bill_dt ";
      $stmt.=",tr_code,amount ";
      $stmt.="FROM trans_mas ";
      $stmt.="where ben_cd=:ben_cd ";
      $sth = $pdo->prepare($stmt);
      if(!empty($ben_cd))
      {
      $sth->bindParam(':ben_cd', $ben_cd);
      }
      $sth->execute();
      $ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
      $row = $sth->fetchAll();
      $sl=0;
      $data[] =$row;

      /* 
      foreach ($row as $key => $value) 
      {


      }

        
      while($row  = $stmt->fetch(PDO::FETCH_OBJ))
      {
         // Assign each row of data to associative array
         $data[] = $row;
      }
      */
      // Return data as JSON
      echo json_encode($row);
   }
   catch(PDOException $e)
   {
      echo $e->getMessage();
   }


?>