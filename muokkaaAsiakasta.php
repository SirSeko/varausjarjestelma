<?php
// EI KÄYTÖSSÄ //
if (isset($_POST['SubmitButton'])) {

    $asiakas = new Asiakkaat("", "", "", "", "", "", "");

    $asiakas->setNimi($_POST["nimi"]);
    $asiakas->setLahiosoite($_POST["lahiosoite"]);
    $asiakas->setPostitoimipaikka($_POST["postitoimipaikka"]);
    $asiakas->setPostinro($_POST["postinro"]);
    $asiakas->setEmail($_POST["email"]);
    $asiakas->setPuhelinnro($_POST["puhelinnro"]);

    // jos ei ole uusi asiakas, päivitetään vanhaa
    if ($_POST['id'] != 0) {
        $asiakas->setAsiakasId($_POST["id"]);
        $message = $tk->PaivitaAsiakas($asiakas);
    } else {
        $asiakas->setasiakasId(0);
        $message = $tk->lisaaAsiakas($asiakas);
    }

} else if ($_GET['id'] == 0) {
    $asiakas = new Asiakkaat("", "", "", "", "", "", "");
} else {
    $asiakas = $tk->HaeAsiakas($_GET['id']);
}

?>

<div class="form-container">
    <h1>Asiakkaan tiedot</h1>
    <?php 
        if (isset($_POST['SubmitButton'])) {
            if ($asiakas->getAsiakasId() != "") {
                echo "<p>" . $message . "</p><br><p><a href='laskutusA.php?sivu=tiedot&id=" . $asiakas->getAsiakasId() . "'>Palaa</a></p>";
            } else {
                echo "<p>" . $message . "</p><br><p><a href='laskutusA.php'>Palaa</a></p>";
            }
            
        } else if ($asiakas != null) {
        ?>

            <form action="" method="post">

            <div class="grid-container">
                <p id="p-nimi">Nimi</p>
                <input type="hidden" name="id" value=<?php echo "'" . $asiakas->getAsiakasId() . "'" ?>>
                <input type="text" name="nimi" class="textinput" id="textinput-nimi" value=<?php echo "'" . $asiakas->getEtunimi() . "'" ?>>
                <p id="p-lahiosoite">Lähiosoite</p>
                <input type="text" name="lahiosoite" class="textinput" id="textinput-lahiosoite" value=<?php echo "'" . $asiakas->getLahiosoite() . "'" ?>>
                <p id="p-postitoimipaikka">Postitoimipaikka</p>
                <input type="text" name="postitoimipaikka" class="textinput" id="textinput-postitoimipaikka" value=<?php echo "'" . $asiakas->getPostitoimipaikka() . "'" ?>>
                <p id="p-postinro">Postinumero</p>
                <input type="text" name="postinro" class="textinput" id="textinput-postinro" value=<?php echo "'" . $asiakas->getPostinro() . "'" ?>>
                <p id="p-email">Sähköposti</p>
                <input type="text" name="email" class="textinput" id="textinput-email" value=<?php echo "'" . $asiakas->getEmail() . "'" ?>>
                <p id="p-puhelinnro">Puhelinnumero</p>
                <input type="text" name="puhelinnro" class="textinput" id="textinput-puhelinnro" value=<?php echo "'" . $asiakas->getPuhelinnro() . "'" ?>>
            </div>

            <input type="submit" name="SubmitButton" value="Tallenna">

        <?php
        } else {
            echo "<p>ei näytettäviä tietoja</p><p><a href='asiakaset.php'>Palaa</a></p>";
        }
        ?>
    
    

    </form>
</div>
