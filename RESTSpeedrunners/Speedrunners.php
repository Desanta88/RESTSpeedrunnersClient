<?php
    $method=$_SERVER["REQUEST_METHOD"];
    $url=parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH);
    $url=explode("/",$url);

    if(isset($_SERVER["CONTENT_TYPE"])){
        $content_type=$_SERVER["CONTENT_TYPE"];
        $content_type=explode("/",$content_type);
    }
    if(isset($_SERVER["HTTP_ACCEPT"])){
        $content_type_request=$_SERVER["HTTP_ACCEPT"];
        $content_type_request=explode("/",$content_type_request);
    }

    $server="localhost";
    $username="programma";
    $password="12345";
    $connection=new PDO("mysql:host=$server;dbname=speedrunning",$username,$password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($method=="GET")
    {
        if(count($url)==4 && $url[3]!="")
        {
            $id=$url[3];
            $query="SELECT * FROM speedrunner WHERE Id=:type";
            $statement=$connection->prepare($query);
            $statement->bindParam(":type",$id,PDO::PARAM_STR);
            $statement->execute();
            $data=$statement->fetchAll();
            if(empty($data)==false)
                echo json_encode($data);
            else
                http_response_code(404);
        }
        else if(count($url)==4 && $url[3]=="")
        {
            $query="SELECT * FROM speedrunner;";
            $statement=$connection->prepare($query);
            $statement->execute();
            $data=$statement->fetchAll();
            echo json_encode($data);
            http_response_code(200);
        }
    }
    else if($method=="POST"){
        if(count($url)==4 && $url[3]=="ADD"){
            $body=file_get_contents("php://input");
            $data=json_decode($body,true);
            $user=$data["Username"];
           
            $nationality=$data["Nationality"];
          
            $gender=$data["Gender"];
           
            $totalSpeedruns=$data["TotalSpeedruns"];
            
            $query="INSERT INTO speedrunner (Username,Nationality,Gender,TotalSpeedruns) VALUES(:type,:type2,:type3,:type4)";
            $statement=$connection->prepare($query);
            $statement->bindParam(":type",$user,PDO::PARAM_STR);
            $statement->bindParam(":type2",$nationality,PDO::PARAM_STR);
            $statement->bindParam(":type3",$gender,PDO::PARAM_STR);
            $statement->bindParam(":type4",$totalSpeedruns,PDO::PARAM_STR);
            $statement->execute();

            http_response_code(200);
        }
        else
            echo strval(count($url));
    }
    else if($method=="PUT"){
        if(count($url)==5 && $url[3]=="EDIT" && $url[4]!=""){
            $body=file_get_contents("php://input");
            $data=json_decode($body,true);

            $user=$data["Username"];
           
            $nationality=$data["Nationality"];
          
            $gender=$data["Gender"];
           
            $totalSpeedruns=$data["TotalSpeedruns"];
            $runner=$url[4];
            
            $query="UPDATE speedrunner SET Username=:type,Nationality=:type2,Gender=:type3,TotalSpeedruns=:type4 WHERE Id=:type5";
            $statement=$connection->prepare($query);
            $statement->bindParam(":type",$user,PDO::PARAM_STR);
            $statement->bindParam(":type2",$nationality,PDO::PARAM_STR);
            $statement->bindParam(":type3",$gender,PDO::PARAM_STR);
            $statement->bindParam(":type4",$totalSpeedruns,PDO::PARAM_STR);
            $statement->bindParam(":type5",$runner,PDO::PARAM_STR);
            $statement->execute();

            if($statement->rowCount()==0)
                http_response_code(404);
            else
                http_response_code(200);
        }
        else
            http_response_code(404);
    }
    else if($method="DELETE"){
        if(count($url)==5 && $url[4]!="" && $url[3]=="DEL")
        {
            $id=$url[4];
            $query="DELETE FROM speedrunner WHERE Id=:type";
            $statement=$connection->prepare($query);
            $statement->bindParam(":type",$id,PDO::PARAM_STR);
            $statement->execute();

            if($statement->rowCount()==0)
                http_response_code(404);
            else
                http_response_code(200);
        }
        else
            http_response_code(404);
    }
?>