<?php
require_once __DIR__ . '/includes/header.php';

$peso = isset($_POST['peso']) ? (float)str_replace(',', '.', $_POST['peso']) : null;
$ml_por_kg = 35; // regra prática
$litros = null;
if ($peso) {
  $litros = ($peso * $ml_por_kg) / 1000;
}
?>
<section class="vz-form">
  <h1>Ingestão diária de água</h1>
  <form method="post">
    <div class="vz-form__row">
      <label>Peso (kg)</label>
      <input type="number" step="0.1" name="peso" value="<?php echo $peso ? h($peso) : ''; ?>" required>
    </div>
    <button class="vz-btn" type="submit">Calcular</button>
  </form>
<?php if ($litros): ?>
  <div class="vz-result">
    <p>Você deve beber aproximadamente <strong><?php echo number_format($litros, 2, ',', '.'); ?> L</strong> por dia.</p>
    <ul class="vz-tips">
      <li>Comece o dia com um copo de água.</li>
      <li>Mantenha uma garrafa por perto.</li>
      <li>Aromatize com fatias de limão ou hortelã.</li>
    </ul>
  </div>
<?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
