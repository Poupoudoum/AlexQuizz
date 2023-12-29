<?php
include "inc/preheader.php";

if (isset($_GET['del'])) {
    
    $data = Cache::get($_GET['del']);
    unset($data[$_GET['val']]);
    Cache::save($_GET['del'],$data);
    header("location: admin.php");
    
}

if (isset($_GET['set'])) {
    
    $data = Cache::save($_GET['set'], $_GET['val']);
    header("location: admin.php");
    
}

$question = Cache::get('question');

include "inc/header.phtml";
?>

<div class="panel panel-default">
    <div class="panel-heading">Joueurs</div>
    <table class="table">
        <thead>
            <tr>
                <th>Joueur</th>
                <th>Points</th>
                <th> </th>
            </tr>
        </thead>
        <?php foreach (Cache::get('joueurs') ?? array() as $j => $joueur) : ?>
        <tr>
            <td><?= htmlentities($joueur) ?></td>
            <td><?= htmlentities(getData('points', $j)) ?></td>
            <td><a class='btn btn-danger' href='?del=joueurs&val=<?= urlencode($j) ?>'><i class='glyphicon glyphicon-trash'></i></a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Questions (actuellement : <?= $question ?>) </div>
    <table class="table">
        <tr>
            <td>ATTENTE</td>
            <td><a class='btn btn-success' href='?set=question&val='><i class='glyphicon glyphicon-ok-sign'></i></a></td>
        </tr>
        <?php foreach (getQuestions() as $q) : ?>
        <tr>
            <td><?= htmlentities($q) ?></td>
            <td><a class='btn btn-success' href='?set=question&val=<?= urlencode($q) ?>'><i class='glyphicon glyphicon-ok-sign'></i></a></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td>RESULTS</td>
            <td><a class='btn btn-success' href='?set=question&val=RESULTS'><i class='glyphicon glyphicon-ok-sign'></i></a></td>
        </tr>
    </table>
</div>


<?php include "inc/footer.phtml" ?>