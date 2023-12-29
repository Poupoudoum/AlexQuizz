<?php include "inc/preheader.php" ?>
<?php include "inc/header.phtml" ?>

<center>
<form action='vote.php' method='get' style='max-width: 400px; margin: 50px auto;'>
  <div class="form-group">
    <label for="nom">Votre Nom</label>
    <input type="text" class="form-control" name='nom' id="nom" placeholder="" style='text-align:center;' required=required pattern="[A-Z].*" title='Doit commencer par une majuscule'>
  </div>
<!--  <div class="form-group">
    <label for="nom">Votre Couleur</label>
    <input type="color" class="form-control" name='color' id="color" placeholder="" style='text-align:center;' required=required >
  </div>-->
  <div class="form-group">
    <button type="submit" class="btn btn-success"><i class='glyphicon glyphicon-sunglasses'></i> Jouer !</button>
  </div>
</form>
</center>


<?php include "inc/footer.phtml" ?>