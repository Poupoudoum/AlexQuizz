<?php 
include "inc/preheader.php";

$question = Cache::get('question');
$joueurs = Cache::get('joueurs') ?? array();

if (isset($_GET["add"])) { 
    foreach ($joueurs as $j => $joueur) {
        foreach (Config::$votes as $v => $t) {
            addData('points', $j, intval(getData('points', $j)) + ($_GET['add'][$v][$j] ?? 0));
        }
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
        <td class='points'>
            <?php $iid = "points-$vote-$j-"; ?>
            <input type='radio' id='<?= $iid ?>ok'  name='add[<?= $vote ?>][<?= $j ?>]' value='<?= Config::RESPONSE_OK_POINTS ?>'  />
            <label for='<?= $iid ?>ok'>+<?= Config::RESPONSE_OK_POINTS  ?></label>
            &nbsp;
            <input type='radio' id='<?= $iid ?>bof' name='add[<?= $vote ?>][<?= $j ?>]' value='<?= Config::RESPONSE_BOF_POINTS ?>' />
            <label for='<?= $iid ?>bof'>+<?= Config::RESPONSE_BOF_POINTS ?></label>
            &nbsp;
            <input type='radio' id='<?= $iid ?>ko'  name='add[<?= $vote ?>][<?= $j ?>]' value='0'  />
            <label for='<?= $iid ?>ko'><i class='glyphicon glyphicon-thumbs-down'></i></label>
        </td>
     </tr>
    <?php
    }
    die();
}

$questions = getQuestions();
$nextQuestion = false;
$questionIndex = 0;
$questionsCount = count($questions);

foreach ($questions as $q) {
    if (strstr($q, "EXEMPLE") !== false) {
        //si une question contient Exemple elle ne "compte pas"
        $questionIndex--;
        $questionsCount--;
    }
    if ($nextQuestion === true) {
        $nextQuestion = $q;
        break;
    }
    if ($q === $question) {
        $nextQuestion = true;
    }
    $questionIndex++;
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
<form method='GET' id='votes'>
<div id='response'>
    
<div class='navbar-buttons'>
    <a href='#votes' class="btn btn-info showvotes"><i class='glyphicon glyphicon-eye-open'></i></a> 
    <a href='#novotes' class="btn btn-primary hidevotes"><i class='glyphicon glyphicon-eye-close'></i></a> 

    <input type='checkbox' id='refresh' checked='checked'> 
    <label for='refresh' class="btn btn-warning"><i class='glyphicon glyphicon-refresh'></i></label> 
    <label for='refresh' class="btn btn-danger"><i class='glyphicon glyphicon-stop'></i></label> 
</div>

     
<div class="col-xs-9 question-container">
<?php if (!$videoMode) : ?>
    <img src='Q/<?= $question ?>/Q.png' />
    <figure>
        <figcaption>
                Question <?= $question ?> <small>( <?= $questionIndex ?> / <?= $questionsCount ?> )</small>
        </figcaption>
        <audio
            autoplay
            controls
            src="Q/<?= $question ?>/Q.mp3">
                Your browser does not support the
                <code>audio</code> element.
        </audio>
    </figure>
<?php else: ?>
    <figure>
        <figcaption>
            <h2>
                Question <?= $question ?> <small>( <?= $questionIndex ?> / <?= $questionsCount ?> )</small>
                <?php if (is_file("Q/$question/R.mp4")) :?>
                    <a class="btn btn-info btn-indice" role="button" onclick='$("#player").attr("src", "Q/<?= $question ?>/R.mp4"); $(this).hide();'>
                        Indice / Suite / Bonus <i class='glyphicon glyphicon-info-sign'></i>
                    </a>
                <?php endif; ?>
            </h2>
        </figcaption>
        <video
            id='player'

            autoplay
            controls
            src="Q/<?= $question ?>/Q.mp4">
                Your browser does not support the
                <code>audio</code> element.
        </video>
    </figure>
<?php endif; ?>

<div class="well well-bg well-response">
        <?php echo nl2br(@file_get_contents("Q/$question/A.txt")); ?>
</div>
<center>
    <a class="btn btn-primary btn-response" href='#response' onclick="$('#refresh').prop('checked', false); ">
        Réponse <i class='glyphicon glyphicon-share-alt'></i>
    </a>
</center>
        
</div>
<div>
    <?php foreach (Config::$votes as $v => $t) : ?>
    <div class='col-xs-3'>
        <div class="panel panel-default" style='overflow: hidden;'>
            <div class="panel-heading"><?= ucfirst($t) ?></div>
            <div style='overflow: auto; height: calc(<?= round(100 / count(Config::$votes)) ?>vh - 100px);'>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Joueur</th>
                        <th class='vote'>Vote</th>
                        <th class='points'> </th>
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
    <script>
        if (window.location.hash == "#response") {
            setTimeout(function() {
                $('#refresh').prop('checked', false);
            }, 300);
        }
    </script>
</div>
<div class='clearfix'></div>


<?php else : ?>
<h1>RESULTATS ! </h1>
<?php endif ?>
<br />
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
<!--        <tr>
            <td>Ajouter : </td>
            <?php foreach ($joueurs as $j => $joueur) : ?>
            <td><input type='number' id='add_<?= $j ?>' name='add[<?= $j ?>]' value='0'></td>
            <?php endforeach; ?>
        </tr>-->
        <?php endif ?>
    </table>
</div>
<?php if ($question != "RESULTS") : ?>
<input type='hidden' name='q' value='<?= $nextQuestion ?>' />
<center>
    <button type="submit" class='btn btn-success'>Question suivante ! <i class='glyphicon glyphicon-chevron-right'></i></button>
</center>
<br />
<br >

<?php else : ?>
<h2><?= Config::CONCLUSION_MESSAGE ?></h2>
<?php endif ?>
</div>
</form>
    
    
    
<?php else : ?>

<center>EN ATTENTE ...</center>
<br />
<center><a href='question.php?q=<?= $nextQuestion ?>'>Commencer</a></center>

<?php endif ?>

<?php include "inc/footer.phtml" ?>


