<?php

class Config {
 
    CONST TITLE_ICON="glyphicon glyphicon-question-sign";
    CONST TITLE="Quizz du nouvel an 2024 !";
    
    CONST CONCLUSION_MESSAGE="ET BONNE ANNEE 2024 !!!";
    
    CONST RESPONSE_OK_POINTS=5;
    CONST RESPONSE_BOF_POINTS=2;
    
    //décrire les différentes questions/votes possibles ici
    public static $votes = array("film" => "Le Film", "acteur" => "Nom de famille de l'Acteur(tice) pincipal(e)");
    
    //mise en forme des scores
    public static $positionsStyles = array(
        array(
            'class' => "success",
            'icon' => "king",
            'styles' => "",
        ),
        array(
            'class' => "info",
            'icon' => "queen",
            'styles' => "",
        ),
        array(
            'class' => "primary",
            'icon' => "knight",
            'styles' => "",
        ),
        array(
            'class' => "warning",
            'icon' => "bishop",
            'styles' => "",
        ),
        array(
            'class' => "danger",
            'icon' => "pawn",
            'styles' => "",
        ),
    );
    
    
    
}