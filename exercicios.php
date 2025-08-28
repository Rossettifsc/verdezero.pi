<?php
require_once __DIR__ . '/includes/header.php';
$ebooks = get_ebooks('exercicios');
?>
<section class="vz-page-title"><h1>Ebooks de Exercícios</h1></section>
<section class="vz-grid">
<?php foreach ($ebooks as $e): ?>
  <article class="vz-ebook">
    <img src="<?php echo h(BASE_URL . $e['cover_path']); ?>" alt="Capa: <?php echo h($e['title']); ?>">
    <div class="vz-ebook__body">
      <h3><?php echo h($e['title']); ?></h3>
      <p class="vz-meta">por <?php echo h($e['author']); ?></p>
      <div class="vz-ebook__actions">
        <span class="vz-price"><?php echo h(cents_to_brl((int)$e['price_cents'])); ?></span>
        <button class="vz-fav" data-id="<?php echo (int)$e['id']; ?>">❤</button>
        <button class="vz-btn">Comprar</button>
      </div>
    </div>
  </article>
<?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
