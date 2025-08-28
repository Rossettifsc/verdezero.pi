<?php
require_once __DIR__ . '/includes/header.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$ebooks = get_ebooks(null);
if ($q !== '') {
    $q_lower = mb_strtolower($q);
    $ebooks = array_values(array_filter($ebooks, function($e) use($q_lower) {
        return str_contains(mb_strtolower($e['title']), $q_lower)
            || str_contains(mb_strtolower($e['author']), $q_lower)
            || str_contains(mb_strtolower($e['category']), $q_lower);
    }));
}
?>
<section class="vz-page-title"><h1>Ebooks</h1></section>

<section class="vz-tabs">
  <a class="vz-tab" href="<?php echo h(BASE_URL); ?>ebooks.php">Todos</a>
  <a class="vz-tab" href="<?php echo h(BASE_URL); ?>receitas.php">Receitas</a>
  <a class="vz-tab" href="<?php echo h(BASE_URL); ?>exercicios.php">Exercícios</a>
</section>

<section class="vz-grid">
<?php foreach ($ebooks as $e): ?>
  <article class="vz-ebook">
    <img src="<?php echo h(BASE_URL . $e['cover_path']); ?>" alt="Capa: <?php echo h($e['title']); ?>">
    <div class="vz-ebook__body">
      <h3><?php echo h($e['title']); ?></h3>
      <p class="vz-meta">por <?php echo h($e['author']); ?> • <?php echo h(ucfirst($e['category'])); ?></p>
      <div class="vz-ebook__actions">
        <span class="vz-price"><?php echo h(cents_to_brl((int)$e['price_cents'])); ?></span>
        <button class="vz-fav" data-id="<?php echo (int)$e['id']; ?>" aria-label="Favoritar">❤</button>
        <button class="vz-btn">Comprar</button>
      </div>
    </div>
  </article>
<?php endforeach; ?>
<?php if (empty($ebooks)): ?>
  <p>Nenhum ebook encontrado. Tente outra busca.</p>
<?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
