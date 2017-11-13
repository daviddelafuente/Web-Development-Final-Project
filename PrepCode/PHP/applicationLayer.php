<?php

  header('Content-type: application/json');
  require_once __DIR__ . '/dataLayer.php';
  $action = $_POST["action"];
  $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

  switch ($action) 
  {
    case 'MakeFirstAdmin':
      MakeFirstAdmin();
      break;

    case 'logUser':
      logUser();
      break;

    case 'logAdmin':
     logAdmin();
     break;

    case 'ValidateSession':
     validateS();
     break;

    case "RemoveSessionA":
      RemoveSessionA();
      break;

    case "RegAdmin":
      RegAdmin();
      break;

    case "RegUser":
      RegUser();
      break;

    case "AddProblem":
      AddProblem();
      break;

    case "AddNews":
      AddNews();
      break;

    case "loadNewsAndProblems":
      loadNewsAndProblems();
      break;

    case "loadProblems":
      loadProblems();
      break;

    case "searchProblem":
      searchProblem();
      break;

    case "LoadUser":
      loadUser();
      break;

    case "UpdateUser":
      updateUser();
      break;

  }

  function MakeFirstAdmin()
  {
    $pass = doEncryption("admin");
    $do = doRegAdmin("admin",$pass);

    if($do["status"] == "OK" || $do["status"] == "409")
    {
     session_start();
     $_SESSION['is_open'] = "TRUE";
     $result = array("message" => "OK");

     echo json_encode($result);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function logUser()
  {
    $user = $_POST["username"];
    $pass = $_POST["uPass"];

    $do = dologinUser($user);

    if($do["status"]=="OK")
    {
      if($pass == doDecrypt($do["data"]))
      {
       session_start();
       $_SESSION['is_open'] = "TRUE";
       $_SESSION['currentUser'] = $user;

       $result = array("message" => "Login Sucessfull");
       echo json_encode($result);
     }
     else
     {
       GenericError("406");
     }
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function logAdmin(){
    $user = $_POST["uID"];
    $pass = $_POST["uPass"];

    $do = dologinAdmin($user);

    if($do["status"]=="OK")
    {
      if($pass == doDecrypt($do["data"]))
      {
       session_start();
       $_SESSION['is_open'] = "TRUE";
       $result = array("message" => "Login Sucessfull");
       echo json_encode($result);
     }
     else
     {
       GenericError("406");
     }
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function RegUser()
  {
    $user = $_POST["username"];
    $pass = doEncryption($_POST["uPass"]);
    $fName = $_POST["uFname"];
    $lName = $_POST["uLname"];
    $email = $_POST["uEmail"];
    $organization = $_POST["uOrganization"];
    $gender = $_POST["uGender"];
    $country = $_POST["uCountry"];

    $do = doRegUser($user, $pass, $fName, $lName, $email, $organization, $gender, $country);

    if($do["status"] == "OK")
    {
      session_start();
      $_SESSION['is_open'] = "TRUE";
      $result = array("message" => "OK");
      echo json_encode($result);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function RegAdmin()
  {
    $user = $_POST["uID"];
    $pass = doEncryption($_POST["uPass"]);
    $do = doRegAdmin($user,$pass);

    if($do["status"] == "OK")
    {
     session_start();
     $_SESSION['is_open'] = "TRUE";
     $result = array("message" => "OK");
     echo json_encode($result);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function AddProblem()
  {
    $source = $_POST["Source"];
    $link = $_POST["Link"];
    $tags = $_POST["Tags"];
    $title = $_POST["Title"];
    $do = doAddProblem($source,$link,$tags,$title);

    if($do["status"] == "OK")
    {
      $result = array("message" => "OK");
      echo json_encode($result);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function AddNews()
  {
    $source = $_POST["Source"];
    $link = $_POST["Link"];
    $title = $_POST["Title"];
    $do = doAddNews($title,$source,$link);

    if($do["status"] == "OK")
    {
      $result = array("message" => "OK");
      echo json_encode($result);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function loadNewsAndProblems()
  {
    $do = doloadNewsAndProblems();
    if($do["status"] == "OK"){
      echo json_encode($do["data"]);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function loadProblems()
  {
    $do = doloadProblems();

    if($do["status"] == "OK")
    {
      echo json_encode($do["data"]);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function searchProblem()
  {
    $search = $_POST["search"];
    $tags = $_POST["Tags"];
    $do = dosearchProblem($search,$tags);

    if($do["status"] == "OK")
    {
      echo json_encode($do["data"]);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function loadUser()
  {
    session_start();
    $user = $_SESSION['currentUser'];

    $do = doLoadUser($user);

    if($do["status"] == "OK")
    {
      echo json_encode($do["data"]);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function updateUser()
  {
    $user = $_POST["username"];
    $pass = doEncryption($_POST["uPass"]);
    $fName = $_POST["uFname"];
    $lName = $_POST["uLname"];
    $email = $_POST["uEmail"];
    $organization = $_POST["uOrganization"];
    $gender = $_POST["uGender"];
    $country = $_POST["uCountry"];

    $do = doUpdateUser($user, $pass, $fName, $lName, $email, $organization, $gender, $country);

    if($do["status"] == "OK")
    {
      session_start();
      $_SESSION['is_open'] = "TRUE";
      $result = array("message" => "OK");
      echo json_encode($result);
    }
    else
    {
      GenericError($do["status"]);
    }
  }

  function validateS()
  {
   session_start();

   if(isset($_SESSION['is_open']))
   {
     $response = array("session" => 'TRUE' );
     echo json_encode($response);
   }
   else
   {
     $response = array("session" => 'FALSE' );
     echo json_encode($response);
   }
 }

 function RemoveSessionA()
 {
   session_start();
   unset($_SESSION['is_open']);

   session_destroy();
   echo json_encode(array("status" => "Noiz"));
 }

  function doEncryption($string)
  {
   $iv = mcrypt_create_iv($GLOBALS["iv_size"], MCRYPT_RAND);
   $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $GLOBALS["key"],
                                 $string, MCRYPT_MODE_CBC, $iv);

   $ciphertext = $iv . $ciphertext;
   $ciphertext_base64 = base64_encode($ciphertext);
    return $ciphertext_base64;
  }

function doDecrypt($string)
{
    $ciphertext_dec = base64_decode($string);
    $iv_dec = substr($ciphertext_dec, 0, $GLOBALS["iv_size"]);
    $ciphertext_dec = substr($ciphertext_dec, $GLOBALS["iv_size"]);
    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,  $GLOBALS["key"],$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

    return rtrim($plaintext_dec);
  }

  function GenericError($var)
  {
     switch ($var) 
     {
       case '500':
          header('HTTP/1.1 500' . "Couldn't connect to Database");
          die("Wasnt able to connect to Database");
         break;

      case '406':
          header('HTTP/1.1 406' . "Data didnt match");
          die("Data didnt match on Database");
        break;

      case '409':
          header('HTTP/1.1 409' . "Wasnt able to upload petition");
          die("Petition wasnt uploaded into the Database");
        break;
      case '204':
          header('HTTP/1.1 204' . "No Content");
          die("No Content in the Petition");
        break;
     }
   }
?>
