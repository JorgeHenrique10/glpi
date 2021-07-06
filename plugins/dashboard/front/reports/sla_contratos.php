<?php

include("../../../../inc/includes.php");
include("../../../../inc/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body onload="jorgeFunction()">
    <h5  id="jorgeId"></h5>
</body>


<script>
    var cont = 0;
    var h5 = document.getElementById("jorgeId");
    var teste = " "
    function jorgeFunction(){
do{
    teste  += "jorge ";
   h5.innerHTML = teste;
   cont++;
   
}while(cont<10000);
    }
</script>

</html>