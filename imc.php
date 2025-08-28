<?php
require_once __DIR__ . '/includes/header.php';

$peso = isset($_POST['peso']) ? (float)str_replace(',', '.', $_POST['peso']) : null;
$altura = isset($_POST['altura']) ? (float)str_replace(',', '.', $_POST['altura']) : null;
$imc = null;
$class = '';
if ($peso && $altura) {
    if ($altura > 3) { $altura = $altura / 100; } // aceita cm
    if ($altura > 0) {
        $imc = $peso / ($altura * $altura);
        if ($imc < 18.5) $class = 'Abaixo do peso';
        elseif ($imc < 25) $class = 'Peso normal';
        elseif ($imc < 30) $class = 'Sobrepeso';
        else $class = 'Obesidade';
    }
}
?>
<section class="vz-form">
  <h1>Calculadora de IMC</h1>
  <form method="post">
    <div class="vz-form__row">
      <label>Peso (kg)</label>
      <input type="number" step="0.1" name="peso" value="<?php echo $peso ? h($peso) : ''; ?>" required>
    </div>
    <div class="vz-form__row">
      <label>Altura (m ou cm)</label>
      <input type="number" step="0.01" name="altura" value="<?php echo $altura ? h($altura) : ''; ?>" required>
    </div>
    <button class="vz-btn" type="submit">Calcular</button>
  </form>
<?php if ($imc): ?>
  <div class="vz-result">
    <p><strong>IMC:</strong> <?php echo number_format($imc, 2, ',', '.'); ?> • <strong><?php echo h($class); ?></strong></p>
    <p>Sugestões:</p>
    <?php if ($class === 'Abaixo do peso' || $class === 'Peso normal'): ?>
      <a class="vz-chip" href="<?php echo h(BASE_URL); ?>receitas.php">Ver receitas</a>
    <?php else: ?>
      <a class="vz-chip" href="<?php echo h(BASE_URL); ?>exercicios.php">Ver exercícios</a>
    <?php endif; ?>
  </div>
<?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
