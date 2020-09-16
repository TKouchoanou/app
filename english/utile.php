<?php

function deepExplode(array $firstArray, string $delim1, string $delim2)
{
    $returnArray = array();

    if ($firstArray === [])
        return;

    $i = 0;
    foreach ($firstArray as $first) {

        $arrayExpld = explode($delim2, $first);
        // var_dump($arrayExpld);
        $returnArray[$i] = [
            "en" => $arrayExpld[0],
            "fr" => $arrayExpld[1]
        ];

        $i ++;
    }
    $i --;
    unset($returnArray[$i]);
    return $returnArray;
}
/**
 * 
 * @param unknown $filename
 * @param unknown $motEtTrad
 * @return unknown|boolean
 */
function ecrireMotEtTrad($filename, $motEtTrad)
{
    $monfichier = fopen($filename, 'a');
    

    if ($monfichier !== false) {

        $nbOctet = fputs($monfichier, $motEtTrad);
        fclose($monfichier);

        if ($nbOctect !== false)
            echo "vous avez rajouté $motEtTrad";

        return $nbOctet;
    } else {
        // On affiche un message d'erreur si on veut
        echo "Impossible d'ouvrir le fichier $filename ";
        return false;
    }
}

function creerFichier($filename)
{
    $monfichier = fopen($filename, 'x+');
    if ($monfichier != false)
        fclose($monfichier);
    return $monfichier;
}

function getFileContentInJson($filename)
{
    $filecontent = file_get_contents($filename);
    $wordarray = deepExplode(explode("\n", $filecontent), "*", ":");
    $jsonArray = json_encode($wordarray);

    return $jsonArray;
}

function getFileContentInArray($filename)
{
    $filecontent = file_get_contents($filename);
    $wordarray = deepExplode(explode("\n", $filecontent), "*", ":");

    return $wordarray;
}

function isAlreadyInDictionnary()
{}

function isAddingAction()
{

    // return isset($post['ajouter']) || ((isset($post['motAnglais']) && isset($post['traductionEnFrançais']));
    return isset($_POST['ajouter']) || (isset($_POST['motAnglais']) && isset($_POST['traductionEnFrançais']));
}
/**
 * 
 * @return boolean
 */
function isAddingPostAction()
{
    return (isset($_POST['motAnglais']) && isset($_POST['traductionEnFrançais']));
}

function isReadingAction()
{
    return isset($_POST['lire']);
}

function isPlayAction()
{
    return isset($_POST['jouer']);
}
/**
 * 
 */
function isPlayEvaluerAction(){
    
    return isset($_POST['jouerEvaluer']);   
}

?>
