<?php 
include "inc/preheader.php";

if (isset($_GET['nom'])) {
    $_SESSION['nom'] = $_GET['nom'];
    header("location: vote.php");
}
if (!isset($_SESSION['nom'])) {
    header("location: index.php");
}
$joueur = $_SESSION['nom'];
$j = technify($joueur);
addData('joueurs', $j, $joueur);
$question = Cache::get('question');


if (isset($_GET['vote'])) {
    if (getData($_GET['vote'], $j) != $_GET['val']) {
        addData($_GET['vote'], $j, $_GET['val']);
    }
    header("location: vote.php");
}


include "inc/header.phtml";

if ($question) :
?>

<h2 style='text-align: center;'>A toi de jouer <?= htmlentities($joueur) ?></h2>
<h3 style='text-align: center;'>Question : <?= $question ?></h3>
    <?php foreach (Config::$votes as $v => $t) : ?>
    <form class="form-horizontal" method='get'>
        <input type="hidden" name='vote' id="vote" value="<?= htmlentities($v) ?>">
        <label for="<?= $v ?>" class="control-label"><?= htmlentities($t) ?></label>
        <div class="input-group">
            <input type="text" name='val' class="form-control" id='<?= $v ?>' value="<?= htmlentities(getData($v, $j)) ?>">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">VOTER</button>
            </span>
        </div>
    </form>
    <?php endforeach ?>

<?php else : ?>
<br />
<br />
<center>En attente du début de la partie ...</center>

<?php endif; ?>

<script>
    function checkQuestion() {
        getData(function(data){
           if (data.question != '<?= $question ?>') {
               window.location.reload();
           }
        });
    }
    
    function getData(handler) {
        $.ajax("data.php").done(handler);
    }
    
    setInterval(checkQuestion, 150);
</script>

<?php include "inc/footer.phtml" ?>