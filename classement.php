<?php
// tableau de classement par vitesse decroissante

$servername = "172.16.133.2";
$username = "radar";
$password = "radar";
$db = "projetradar";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$sql = "SELECT eleve.nom, eleve.prenom, participation.vitesse FROM participation, eleve WHERE eleve.idEleve = participation.idEleve ORDER BY participation.vitesse DESC";
$result = $conn->query($sql);

// echo gettype($result); pour avoir le type d'une variable

/*
    Function : result_sql
    @params : $result (objet), $tab_noms_col (tableau)
    @return : résultat de la requete sql dans un tableau
*/
function result_sql(object $result, array $tab_noms_col) : array
{
    if ($result->num_rows > 0)
    {   
        // input data in table of each row
        $table_lien = array();
        while($row = $result->fetch_assoc()) 
        {
            $table_prenomModele = array();
            
            $compt = 0;
            while($compt < count($tab_noms_col))
            {
                array_push($table_prenomModele, $row[$tab_noms_col[$compt]]);  
                $compt ++; 
            }               
            array_push($table_lien,$table_prenomModele);
        }
        return $table_lien;
    } 
    else 
    {
        return "0 results";
    }
}

/*
    Function : tab_html
    @params : $tab_noms_col (tableau), $table_lien (tableau)
    @return : un tableau mise en forme html et qui affiche les donné de la requete sql
*/
function tab_html(array $tab_noms_col, array $table_lien) : string
{
    // Les colonnes et leurs noms dans le tableau
    $tab = ["<table style=\"width:100%; border: 1px solid black;\">\n","  <tr>\n"];

    $compt = 0;
    while($compt < count($tab_noms_col))
    {
        array_push($tab,"    <th style=\" border: 1px solid black\">$tab_noms_col[$compt]</th>\n");
        $compt ++;
    }
    array_push($tab, "  </tr>\n");

    // Insertion de chaque lignes dans le tableau  
    $compt = 0;
    while($compt < count($table_lien))
    {
        $ligne = ["  <tr>\n"];
        $comptbis = 0;
        while($comptbis < count($tab_noms_col))
        {
            array_push($ligne, "    <td style=\" border: 1px solid black\">".$table_lien[$compt][$comptbis]."</td>\n");
            $comptbis ++;
        }
        $compt ++;
        array_push($ligne, "  </tr>\n");
        $lignecarac = implode($ligne);

        array_push($tab, $lignecarac);   
    }
    array_push($tab, "</table>");
    return implode($tab);
}

$x = array("nom", "prenom", "vitesse");
$table_lien = result_sql($result, $x);
$y = $table_lien;
echo tab_html($x,$y);