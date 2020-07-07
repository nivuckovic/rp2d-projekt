<?php require_once __DIR__ . '/_header.php'; ?>
<div class='mid'>
<p>Ime</p> <input type="text" id="ime"> <br>
<p>Prezime</p> <input type="text" id="prezime"> <br>
<p>Godine</p> <input type="number" id="godine"> <br>

<p>Spol:</p>
<input type="radio" id="musko" name="spol" value="musko">
<label for="musko">Muško</label>
<input type="radio" id="zensko" name="spol" value="zensko" checked>
<label for="zensko">Žensko</label><br>

<p>Slika:</p>
<div id="slike_box"> </div>

<p>Lokacija:</p>
<input type="text" id="lokacija"> <br>

<p>O meni:</p>
<textarea id="o_meni" rows = "5", cols="30"></textarea> <br>
<!---input type="button" value="Spremi" -->
<button id="spremi_profil" >Spremi</button>
<div>
<?php require_once __DIR__ . '/_footer.php'; ?>