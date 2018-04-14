<?php
require_once "header.php";
require_once "conf.inc.php";
require_once "functions.php";


if(isset($_SESSION["connexionNeeded"])){
    echo "<center><h4 class=connexionNeeded>".$_SESSION["connexionNeeded"]."</h4></center>";
    unset($_SESSION["connexionNeeded"]);
}
?>
<!--has changed-->
<div class="row rowsignup">
    <div class="col-md-4 ml-auto mr-auto">
        <center><h2>S'inscrire</h2></center>

        <?php
        if (isset($_SESSION["errorForm"])) {
            foreach ($_SESSION["errorForm"] as $keyError) {
                echo "<li style = 'color:red'>" . $listOfErrors[$keyError] . "</li>";
            }
            unset($_SESSION["errorForm"]);
        }
        ?>

        <form method="POST" action="script/saveUser.php">

            <?php
            foreach ($listOfGender as $key => $value) { ?>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="gender"
                               value="<?php echo $key; ?>"

                            <?php
                            if (isset($_SESSION["postForm"]) && $_SESSION["postForm"]["gender"] == $key) {
                                echo 'checked="checked"';
                            } else if (!isset($_SESSION["postForm"]) && $key == $defaultGender) {
                                echo 'checked="checked"';
                            }
                            ?>>
                        <?php // echo ($key == $defaultGender)?'checked="checked"':""; ?>
                        <?php echo $value; ?>
                    </label>
                </div>

                <?php
            }
            ?>

            <div class="form-row">
                <div class=" form-group col">
                    <input type="text" class="form-control" placeholder="Prénom" name="firstname" required="required"
                           value="<?php echo isset($_SESSION['postForm']) ? $_SESSION['postForm']['firstname'] : '' ?>">
                </div>
                <div class="form-group col">
                    <input type="text" class="form-control" placeholder="Nom" name="lastname" required="required"
                           value="<?php echo isset($_SESSION['postForm']) ? $_SESSION['postForm']['lastname'] : '' ?>">
                </div>
            </div>


            <div class="form-row">
                <div class="form-group col">
                    <input type="date" class="form-control" placeholder="Date d'anniversaire" name="birthday"
                           required="required"
                           value="<?php echo isset($_SESSION['postForm']) ? $_SESSION['postForm']['birthday'] : '' ?>">
                </div>
                <div class="form-group col">
                    <input type="text" class="form-control" placeholder="Code postal" name="zipcode"
                           required="required"
                           value="<?php echo isset($_SESSION['postForm']) ? $_SESSION['postForm']['zipcode'] : '' ?>">
                </div>
            </div>

            <div class="form-group">
                <input type="email" class="form-control" id="emailLogin" aria-describedby="emailHelp"
                       placeholder="Votre email" name="email" required="required"
                       value="<?php echo isset($_SESSION['postForm']) ? $_SESSION['postForm']['email'] : '' ?>">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" aria-describedby="addressHelp"
                       placeholder="Votre adresse" name="address" required="required"
                       value="<?php echo isset($_SESSION['postForm']) ? $_SESSION['postForm']['address'] : '' ?>">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" id="emailLogin" aria-describedby="emailHelp"
                       placeholder="Mot de passe"
                       name="pwd" required="required">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" id="emailLogin" aria-describedby="emailHelp"
                       placeholder="Confirmation"
                       name="pwdConfirm" required="required">
            </div>

            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="cgu" required="required">
                    J'accepte les Conditions Générales d'Utilisation de ce site.
                </label>
            </div>

            <button type="submit" class="btn btn-primary">S'inscrire</button>

            <?php

            unset($_SESSION['postForm']);
            ?>
        </form>
    </div>

</div>

<?php
include "footer.php";
?>




