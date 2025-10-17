<?php
// Pretpostavka: $requests je već definisan u kontroleru
?>

<h2>Pristigli zahtevi</h2>
<table border="1">
  <tr>
    <th>Korisnik</th>
    <th>Vozilo</th>
    <th>Opis</th>
    <th>Status</th>
    <th>Akcija</th>
  </tr>
  <?php foreach ($requests as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['user_name']) ?></td>
      <td><?= htmlspecialchars($r['car_model']) ?></td>
      <td><?= htmlspecialchars($r['description']) ?></td>
      <td><?= htmlspecialchars($r['status']) ?></td>
      <td>
        <?php if ($r['status'] == 'pending'): ?>
          <a href="reply.php?id=<?= $r['id'] ?>">Odgovori</a>
        <?php else: ?>
          <em>Odgovoreno</em>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
