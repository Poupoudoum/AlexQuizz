<?php 
include "inc/preheader.php";

$question = Cache::get('question');
$joueurs = Cache::get('joueurs') ?? array();

if (isset($_GET["add"])) { 
    foreach ($joueurs as $j => $joueur) {
        addData('points', $j, intval(getData('points', $j)) + ($_GET['add'][$j] ?? 0));
    }
}

if (isset($_GET["q"])) {
    if ($question != $_GET["q"]) {
        clearVotes();
        Cache::save('question', $_GET["q"]);
    }
    header("location: question.php#novotes");
}

uasort($joueurs, function($a, $b) {
   return intval(getData('points', technify($b))) - intval(getData('points', technify($a)));
});


if (isset($_GET["v"])) {
    $vote = $_GET["v"];
    
    foreach (Cache::get($vote) ?? array() as $j => $v) { ?>
    <tr>
        <td><?= htmlentities(getData('joueurs', $j)) ?></td>
        <td class='vote'><?= htmlentities($v) ?></td>
        <td class='vote'><a class='btn btn-sm btn-success' onclick="$('#add_<?= $j ?>').val(parseInt($('#add_<?= $j ?>').val()) + 5)">+5</a></td>
        <td class='vote'><a class='btn btn-sm btn-info   ' onclick="$('#add_<?= $j ?>').val(parseInt($('#add_<?= $j ?>').val()) + 2)">+2</a></td>
     </tr>
    <?php
    }
    die();
}

$questions = getQuestions();
$nextQuestion = false;

foreach ($questions as $q) {
    if ($nextQuestion === true) {
        $nextQuestion = $q;
        break;
    }
    if ($q === $question) {
        $nextQuestion = true;
    }
}
if ($nextQuestion === false) {
    $nextQuestion = current($questions); //first
}
if ($nextQuestion === true) {
    $nextQuestion = "RESULTS"; // last question reached
}


include "inc/header.phtml";


if ($question) :
    if ($question != "RESULTS") :
    
    $videoMode = is_file("Q/$question/Q.mp4");
?>
    <div style='text-align: right'>
        <a href='#votes'>Voir les votes</a> 
        <a href='#novotes'>Cacher les votes</a> 
        <input type='checkbox' id='refresh' checked='checked'> <label for='refresh'>Rafraichir</label>
    </div>
<div class='clearfix'></div>
<?php if (!$videoMode) : ?>
    <div class="col-xs-9">
        <img src='Q/<?= $question ?>/Q.png' />
        <figure>
            <figcaption>Question <?= $question ?> :</figcaption>
            <audio
                autoplay
                controls
                src="Q/<?= $question ?>/Q.mp3">
                    Your browser does not support the
                    <code>audio</code> element.
            </audio>
        </figure>
    </div>
<?php else: ?>
    <div class="col-xs-9">
        <figure>
            <figcaption><h2>Question <?= $question ?></h2></figcaption>
            <video
                id='player'
                
                autoplay
                controls
                src="Q/<?= $question ?>/Q.mp4">
                    Your browser does not support the
                    <code>audio</code> element.
            </video>
        </figure>
    </div>
<?php endif; ?>
<div id='votes'>
    <?php foreach (Config::$votes as $v => $t) : ?>
    <div class='col-xs-3'>
        <div class="panel panel-default" style='overflow: hidden;'>
            <div class="panel-heading"><?= ucfirst($t) ?></div>
            <div style='overflow: auto; height: <?= round(70 / count(Config::$votes)) - 5 ?>vh;'>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Joueur</th>
                        <th>Vote</th>
                        <th> </th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody id='votes_<?= $v ?>'>
                </tbody>
            </table>
            </div>
            <script>
                setInterval(function() {
                    if ($('#refresh').is(':checked')) {
                        $('#votes_<?= $v ?>').load('question.php?v=<?= $v ?>');
                    }
                }, 250);
            </script>
        </div>
    </div>
    <?php endforeach ?>
</div>
<div class='clearfix'></div>

<?php if ($videoMode && is_file("Q/$question/R.mp4")) :?>
<center>
    <a class="btn btn-info" role="button" onclick='$("#player").attr("src", "Q/<?= $question ?>/R.mp4"); $(this).hide();'>
        Indice / Suite / Bonus
    </a>
</center>
<?php endif; ?>
<br>
<div class="collapse" id="collapseExample">
    <div class="well well-bg" style='font-size: 140%; text-align: center; max-width: 500px; margin: 20px auto;'>
            <?php echo nl2br(@file_get_contents("Q/$question/A.txt")); ?>
    </div>
</div>
<center>
    <a class="btn btn-primary" role="button" onclick="$(this).hide();" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Réponse
    </a>
</center>

<?php else : ?>
<h1>RESULTATS ! </h1>
<?php endif ?>
<br />
<form method='GET'>
<div class="panel panel-default panel-warning">
    <div class="panel-heading">Scores</div>
    <table class="table centered">
        <thead>
            <tr>
                <td>Joueur : </td>
                <?php foreach ($joueurs as $j => $joueur) : ?>
                    <th><?= htmlentities($joueur) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tr>
            <td>Score : </td>
            <?php foreach ($joueurs as $j => $joueur) : ?>
                <td><?= htmlentities(getData('points', $j) ?? '0') ?></td>
            <?php endforeach; ?>
        </tr>
        <?php if ($question != "RESULTS") : ?>
        <tr>
            <td>Ajouter : </td>
            <?php foreach ($joueurs as $j => $joueur) : ?>
            <td><input type='number' id='add_<?= $j ?>' name='add[<?= $j ?>]' value='0'></td>
            <?php endforeach; ?>
        </tr>
        <?php endif ?>
    </table>
</div>
<?php if ($question != "RESULTS") : ?>
<input type='hidden' name='q' value='<?= $nextQuestion ?>' />
<button type="submit" style='float: right;' class='btn btn-sm btn-default'>Question suivante</button>
<?php else : ?>
<h2>ET BONNE ANNEE 2024 !!!</h2>
<?php endif ?>
</form>
    
    
    
<?php else : ?>

<center>EN ATTENTE ...</center>
<br />
<center><a href='question.php?q=<?= $nextQuestion ?>'>Commencer</a></center>

<?php endif ?>

<?php include "inc/footer.phtml" ?>


