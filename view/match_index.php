<?php require_once __DIR__ . '/_header.php'; ?>
<?php require_once __DIR__ . '/_menu.php'; ?>

<div class='filter'>
Spol: <br>
<input type="radio" id="musko" name="spol" value="musko">
<label for="musko">Muško</label>
<input type="radio" id="zensko" name="spol" value="zensko">
<label for="zensko">Žensko</label>
<input type="radio" id="oba" name="spol" value="oba" checked>
<label for="oba">Oba</label><br>

<div>
    <div id="min_godine_text"> </div>
    <input type="range" id="min_godine_slider" name="min_godine" min="18" max="99" value="18"> 

    <div id="max_godine_text"> </div>
    <input type="range" id="max_godine_slider" name="max_godine" min="18" max="99" value="99">
</div>
   <!---input type="button" value="Filtriraj!" value="Svi profili!"-->
<button id="match_trazi" >Filtriraj!</button>
<button id="match_svi" >Svi profili!</button>
</div>
<hr>

<div id="match_box">
    <div id="match_profiles_box"> </div>
</div>


<?php require_once __DIR__ . '/_footer.php'; ?>