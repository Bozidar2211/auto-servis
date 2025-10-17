<?php
$request_id = $_GET['id'] ?? null;
?>

<h2>Odgovor na zahtev</h2>
<form method="POST" action="reply.php">
  <input type="hidden" name="request_id" value="<?= htmlspecialchars($request_id) ?>">
  
  <label>Cena:</label><br>
  <input type="number" name="price" required><br><br>
  
  <label>Datum:</label><br>
  <input type="date" name="date" required><br><br>
  
  <label>Napomena:</label><br>
  <textarea name="note"></textarea><br><br>
  
  <button type="submit">Pošalji odgovor</button>
</form>
