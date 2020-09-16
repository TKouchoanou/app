<!DOCTYPE html>

<?php

// use foo\utile;
if (isset($_COOKIE['nbVisite'])) {
    setcookie("nbVisite", $_COOKIE['nbVisite'] + 1, time() + 3600);
} else {
    setcookie("nbVisite", 1, time() + 3600); /* expire dans 10 heure */
}

?>

<html>
<head>
<meta charset="utf-8" />
<title>Learn English</title>

<style type="text/css">
.error-message {
	font-size: 15px;
	color: red;
}

.error {
	border: 1px solid red;
	color: red;
}

.list-mots-row {
	padding: 10px 10px 10px 10px;
}
.eno{

  text-align: center;

}

td{

  text-align: center;

}



table {
border: medium solid #6495ed;
border-collapse: collapse;
width: 50%;
}
th {
font-family: monospace;
border: thin solid #6495ed;
width: 50%;
padding: 5px;
background-color: #D0E3FA;
background-image: url(sky.jpg);
}
td {
font-family: sans-serif;
border: thin solid #6495ed;
width: 50%;
padding: 5px;
text-align: center;
background-color: #ffffff;
}




#paragraphe-id {
	font-weight: bold;
	color: green;
}
</style>
</head>

<body>
	<h2>Bienvennue sur English To French</h2>
	<p>Enrichissons notre vocabulaire Anglais</p>
	<span>* visite <?php echo $_COOKIE['nbVisite']; ?></span>

  <?php

 //  var_dump($_GET); 
//var_dump($_POST['traductionFrançais']); 
$filename = '/var/www/html/app/english/dico.txt';
include ('/var/www/html/app/english/utile.php');

if (file_exists($filename)) {

    $fileContentArray = getFileContentInArray($filename);
    include ('/var/www/html/app/english/WhatActionForm.html');
    if (isAddingAction()) {

        if (isAddingPostAction()) {

            $motEtTrad = $_POST['motAnglais'] . ":" . $_POST['traductionEnFrançais'] . "\n";

            $monfichier = fopen($filename, 'a');

            ecrireMotEtTrad($filename, $motEtTrad);
        } else {

            include ('/var/www/html/app/english/form.html');
        }
    } else if (isReadingAction()) {

        include ('/var/www/html/app/english/list.phtml');
        
    } else if (isPlayEvaluerAction()) {
        
        include ('/var/www/html/app/english/jouerEvaluer.php');
        
        
      } else if (isPlayAction()) {

        include ('/var/www/html/app/english/jouer.php');
        //
    }
} else {
    creerFichier($filename);
}

?>
<script>
 var words=<?php echo json_encode($fileContentArray) ; ?>;

 function getNExtWord(words){
     do{
          let j = getRandomArbitrary(0,words.length);
           
          let word= words[j]; 
  
          if(!isReadedWord(word)){  
              return word; } 

        }while(isNewWordToRead(words)); 
       
     return false; 

}
 function getRandomArbitrary(min, max) {
	   return Math.trunc((Math.random() * (max - min) + min));
	 }
 /**
  * Renvoie vraie s'il y a toujour un nouveau mot à lire dans le tableau
   c-a-d tout les mots ne son pas encore lue pour cette partie de jeu
  */
 function isNewWordToRead(words){

	 for (let i = 0; i < words.length; i++) {
	   let word= words[i]; 
	      if(!isReadedWord(word)){
	        return true; 
	       }  
	    }
	   
	   return false; 
	 }

 /**
  * Est-ce un mot déjà lu ? renvoie true si oui et false sinon
  */
 function isReadedWord(word){
 	return (word.read!==undefined && word.read===true); 
 }
 console.log(words); 

</script>
</body>
</html>
