<?php

include "header.php";

require_once "functions.php";

if (!empty($_SESSION["token"])) {
    $db = connectDB();
    $query = $db->prepare("SELECT member_lastname AS NOM, member_firstname AS PRENOM, member_email AS EMAIL,
                                           member_address AS ADRESSE, member_zip_code AS  FROM member WHERE member_token = :token;");
    $query->execute([
        "token" => $_SESSION["token"]
    ]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
}


?>
<div class="container">
    <div id="profile-picture" class="">
        <img class="profile-picture" src="img/paris1.jpg" alt="profile picture">
        <br>
        <button style="margin-top: 10px" class="btn btn-primary">Modifier</button>
    </div>
</div>

<div class="container container-fluid">
    <div id="information">
        <h2>Information</h2>
        <div class="mr-auto ml-auto">
            <form method="POST">
                <table class="mr-auto ml-auto">
                    <?php foreach ($result as $row) { ?>
                        <tr>
                            <td>
                                <div class="form-group row ml-auto mr-auto">
                                    <label>NOM</label>
                                    <input type="text" class="form-control align-content-center" disabled="disabled"
                                           value="">
                                    <input type="text" class="form-control" name="lastname" value=<?php echo isset($_POST["lastname"]) ? $_POST["lastname"]: '' ?>>
                                </div>
                            </td>
                            <?php } ?>
                            <td>
                                <button type="submit" style="margin-left: 20%" class="btn btn-primary">Modifier</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group row ml-auto mr-auto">
                                    <label>Prénom</label>
                                    <input type="text" class="form-control align-content-center" disabled="disabled"
                                           value=<?php echo $value ?>>
                                    <input type="text" class="form-control" name="firstname"
                                           value="">
                                </div>
                            </td>
                            <td>
                                <button type="submit" style="margin-left: 20%" class="btn btn-primary">Modifier</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group row ml-auto mr-auto">
                                    <label>Adresse</label>
                                    <input type="text" class="form-control align-content-center" disabled="disabled"
                                           value=<?php echo $value ?>>
                                    <input type="text" class="form-control" name="address"
                                           value="">
                                </div>
                            </td>
                            <td>
                                <button type="submit" style="margin-left: 20%" class="btn btn-primary">Modifier</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group row ml-auto mr-auto">
                                    <label>Code postal</label>
                                    <input type="text" class="form-control align-content-center" disabled="disabled"
                                           value=<?php echo $value ?>>
                                    <input type="text" class="form-control" name="zipcode"
                                           value="<?php echo isset($_POST["zipcode"]) ? $_POST["zipcode"] : '' ?>">
                                </div>
                            </td>
                            <td>
                                <button type="submit" style="margin-left: 20%" class="btn btn-primary">Modifier</button>
                            </td>
                        </tr>
                        <?php
                        if (!empty($_POST["lastname"])) {
                            $query = $db->prepare("UPDATE member SET member_lastname = :lastname");
                            $query->execute([
                                "lastname" => $_POST["lastname"]
                            ]);
                        }
                        ?>
                </table>
            </form>
        </div>
    </div>
</div>