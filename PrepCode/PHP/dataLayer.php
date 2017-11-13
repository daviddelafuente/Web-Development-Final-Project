<?php
//session_start();
header('Content-type: application/json');
header('Accept: application/json');

function connectToDB(){
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "prepcode";

  $conn = new mysqli($servername,$username,$password,$dbname);
  if($conn->connect_error){
    return null;
  }else{
    return $conn;
  }
}

function doRegAdmin($id,$pass)
{
  $conn = connectToDB();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM admin WHERE id = '$id'";
    $resu = $conn -> query($sql2);

    if($resu->num_rows == 0)
    {
      $sql = "INSERT INTO admin VALUES ('$id', '$pass')";
      $resu = $conn -> query($sql);

      if($resu === TRUE)
      {
        $conn -> close();
        return array("status" => "OK");
      }
      else
      {
        $conn -> close();
        return array("status" => "204");
      }
    }
    else
    {
      $conn -> close();
      return array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function doRegUser($username, $pass, $fName, $lName, $email, $organization, $gender, $country)
{
  $conn = connectToDB();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM Users WHERE username = '$username'";
    $resu = $conn -> query($sql2);

    if($resu->num_rows == 0)
    {
      $sql = "INSERT INTO Users VALUES ('$username', '$pass', '$fName', '$lName', '$email', '$gender', '$country', '$organization')";

      $resu = $conn -> query($sql);

      if($resu === TRUE)
      {
        $conn -> close();
        return array("status" => "OK");
      }
      else
      {
        $conn -> close();
        return array("status" => "204");
      }
    }
    else
    {
      $conn -> close();
      return array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function doLoginUser($user)
{
  $conn = connectToDB();
  $resultArr["status"] = "OK";
  $resultArr["data"] = array();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM Users WHERE username = '$user'";
    $result = $conn->query($sql2);

    if($result->num_rows>0)
    {
      while($row = $result->fetch_assoc())
      {
        $resultArr["data"]  = $row["Password"];
      }

      $conn -> close();
      return $resultArr;

    }
    else
    {
      $conn -> close();
      return  array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function dologinAdmin($user)
{
  $conn = connectToDB();
  $resultArr["status"] = "OK";
  $resultArr["data"] = array();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM admin WHERE id = '$user'";
    $result = $conn->query($sql2);

    if($result->num_rows > 0)
    {
      while($row = $result->fetch_assoc())
      {
        $resultArr["data"]  = $row["Password"];
      }

      $conn -> close();
      return $resultArr;
    }
    else
    {
      $conn -> close();
      return  array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function doAddProblem($source,$link,$tags,$title)
{
  $conn = connectToDB();
  $resultArr["status"] = "OK";

  if($conn != NULL)
  {
    $sql2 = "INSERT INTO Problems (OrgFROM,title ,link) VALUES ('$source','$title','$link')";
    $res = $conn -> query($sql2);

    if($res === TRUE)
    {
      $sql2 = "SELECT id FROM Problems WHERE link = '$link'";
      $result = $conn->query($sql2);

      while($row = $result->fetch_assoc())
      {
        $aux  = $row["id"];
      }

      foreach ($tags as $value)
      {
        $sql2 = "Insert INTO Tags(id,tag) VALUES ('$aux','$value')";
        $conn -> query($sql2);
      }
      $conn -> close();
      return $resultArr;
    }else{
      $conn -> close();
      return  array("status" => "409");
    }
  }else{
    return array("status" => "500");
  }
}

function doAddNews($title,$source,$link)
{
  $conn = connectToDB();
  $resultArr["status"] = "OK";
  $mysqltime = date ("Y-m-d H:i:s", time());

  if($conn != NULL)
  {
    $sql2 = "INSERT INTO news (OrgFROM,title, link,timePosted) VALUES ('$source','$title','$link','$mysqltime')";
    $result = $conn->query($sql2);

    if($result === TRUE)
    {
      $conn -> close();
      return array("status" => "OK");
    }
    else
    {
      $conn -> close();
      return  array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function doloadNewsAndProblems()
{
  $conn = connectToDB();
  $resultArr["status"] = "OK";
  $resultArr["data"] = array();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM news   ORDER BY id DESC";
    $result = $conn->query($sql2);

    if($result->num_rows>0)
    {
      while($row = $result->fetch_assoc())
      {
        $response = array("link"=>$row["link"],"title"=>$row["title"],"orgFROM"=>$row["OrgFROM"],"timeposted"=>$row["timePosted"]);
        array_push($resultArr["data"],$response);

      }

      $conn -> close();
      return $resultArr;

    }
    else
    {
      $conn -> close();
      return  array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function doloadProblems()
{

  $conn = connectToDB();
  $resultArr["status"] = "OK";
  $resultArr["data"] = array();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM problems";
    $result = $conn->query($sql2);

    if($result->num_rows > 0)
    {
      while($row = $result->fetch_assoc())
      {
        $tags = array();
        $aux = $row['id'];
        $sql3 = "SELECT * FROM tags WHERE id = '$aux'";
        $result2 = $conn->query($sql3);

        while($row2 = $result2->fetch_assoc())
        {
            array_push($tags,$row2["tag"]);
        }

        $response = array("link"=>$row["link"],"id"=>$row["id"],"title"=>$row["title"],"orgFROM"=>$row["OrgFROM"],"tags"=>$tags);
        array_push($resultArr["data"],$response);
      }
      $conn -> close();
      return $resultArr;
    }
    else
    {
      $conn -> close();
      return  array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function dosearchProblem($search,$tags)
{
  //Falta poder filtrar tomando en cuenta los tags
  //Puede ser ver si el search esta vacio en ese caso solo tomas en Tags
  //Si no tiene tags entonces es como esta normal
  $conn = connectToDB();
  $resultArr["status"] = "OK";
  $resultArr["data"] = array();

  if($conn != NULL)
  {
    if(count($tags) == 1)
    {
      $sql2 = "SELECT * FROM problems WHERE id = '$search' OR title = '$search'";
    }
    else if($search == "" && count($tags) > 1)
    {
      //Poner Query solo tomar en cueta los tags juntar tags y problems
      $sql2 = "SELECT * FROM problems,tags
               WHERE tags.id = problems.id
               and problems.id IN (SELECT id FROM tags WHERE ";

      for ($x = 1; $x < count($tags); $x++) 
      {
        if($x == 1)
        {
          $sql2 .= " tag = " . "'" . $tags[$x] . "'";
        }
        else
        {
          $sql2 .= " or tag = " . "'" . $tags[$x] . "'";
        }
      }
      $sql2 .= "  GROUP by id HAVING COUNT(id) = ". (count($tags)-1)  .")";
    }
    else
    {
      //Poner el query con ambos usar la base que tenemos y concatenar cada uno
      //que este en la lista de tags
      $sql2 = "SELECT * FROM problems,tags
               WHERE tags.id = problems.id and (problems.id = '$search' OR problems.title = '$search')
               and problems.id IN (SELECT id FROM tags WHERE ";

      for ($x = 1; $x < count($tags); $x++) 
      {
        if($x == 1)
        {
          $sql2 .= " tag = " . "'" . $tags[$x] . "'";
        }
        else
        {
          $sql2 .= " or tag = " . "'" . $tags[$x] . "'";
        }
      }

      $sql2.="  GROUP by id HAVING COUNT(id) = ". (count($tags)-1)  .")";
    }

    $sql2.=" GROUP BY problems.id;";
    $result = $conn->query($sql2);

    if($result->num_rows > 0)
    {
      while($row = $result->fetch_assoc())
      {
        $tags = array();
        $aux = $row['id'];
        $sql3 = "SELECT * FROM tags WHERE id = '$aux'";
        $result2 = $conn->query($sql3);

        while($row2 = $result2->fetch_assoc())
        {
            array_push($tags, $row2["tag"]);
        }

        $response = array("link" => $row["link"], "id" => $row["id"], "title" => $row["title"], "orgFROM" => $row["OrgFROM"], "tags" => $tags);
        array_push($resultArr["data"], $response);
      }

      $conn -> close();
      return $resultArr;
    }
    else
    {
      $conn -> close();
      return $resultArr;
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function doLoadUser($username)
{
  $conn = connectToDB();
  $resultArr["status"] = "OK";
  $resultArr["data"] = array();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM Users WHERE username = '$username'";
    $result = $conn->query($sql2);

    if($result->num_rows > 0)
    {
      while($row = $result->fetch_assoc())
      {
          $response = array("username" => $row["Username"], "uPassword" => doDecrypt($row["Password"]), "uFname" => $row["Fname"], "uLname" => $row["Lname"], "uEmail" => $row["Email"], "uGender" => $row["Gender"], "uCountry" => $row["Country"], "uOrganization" => $row["Organization"]);

          array_push($resultArr["data"], $response);
      }

      $conn -> close();
      return $resultArr;
    }
    else
    {
      $conn -> close();
      return  array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}

function doUpdateUser($username, $pass, $fName, $lName, $email, $organization, $gender, $country)
{
  $conn = connectToDB();

  if($conn != NULL)
  {
    $sql2 = "SELECT * FROM Users WHERE username = '$username'";
    $resu = $conn -> query($sql2);

    if($resu->num_rows > 0)
    {
      $sql = "UPDATE Users SET Password = '$pass', Fname = '$fName', Lname = '$lName', Gender = '$gender', Country = '$country', Organization = '$organization' WHERE Username = '$username'";

      $resu = $conn -> query($sql);

      if($resu === TRUE)
      {
        $conn -> close();
        return array("status" => "OK");
      }
      else
      {
        $conn -> close();
        return array("status" => "204");
      }
    }
    else
    {
      $conn -> close();
      return array("status" => "409");
    }
  }
  else
  {
    return array("status" => "500");
  }
}