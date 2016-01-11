<h2>Gästbok</h2>
<p>
Välkommen att skriva i vår gästbok. Eftersom det varit en del bekymmer med automatgenererade strunt inlägg, så måste man nu svara på en enkel fråga för att kunna göra ett inlägg.
</p>			
<form role="form" id="gb" action="<?php echo $root; ?>newpost.php">
  <div class="form-group">
    <label for="name">Namn</label>
    <input type="text" class="form-control" name="gbname" id="gbname" placeholder="Skriv namn">
  </div>

  <div class="form-group">
    <label for="message">Meddelande</label>
    <textarea class="form-control" name="gbmessage" id="gbmessage"> </textarea>
  </div>

  <button type="submit" class="btn btn-primary">Skicka</button>
</form>

<br><br><br><hr>