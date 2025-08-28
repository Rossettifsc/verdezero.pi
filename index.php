<?php
require __DIR__ . '/includes/header.php';
?>
<section class="vz-hero" aria-labelledby="hero-title">
  <div class="vz-hero__content">
    <h1 id="hero-title">Transforme sua vida com <span>conhecimento</span> e <span>prática</span></h1>
    <p>Descubra receitas saudáveis, exercícios personalizados e ferramentas científicas para uma vida mais equilibrada e saudável.</p>
    <div class="vz-hero__actions">
      <a class="vz-btn" href="<?php echo h(BASE_URL); ?>ebooks.php">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
        </svg>
        Explorar Ebooks
      </a>
      <a class="vz-btn-outline" href="<?php echo h(BASE_URL); ?>receitas.php">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 12l2 2 4-4"></path>
          <path d="M21 12c-1 0-2-1-2-2s1-2 2-2 2 1 2 2-1 2-2 2z"></path>
          <path d="M3 12c1 0 2-1 2-2s-1-2-2-2-2 1-2 2 1 2 2 2z"></path>
        </svg>
        Ver Receitas
      </a>
    </div>
  </div>
  <div class="vz-hero__image">
    <img src="<?php echo h(BASE_URL); ?>assets/img/img.banner2.jpg" alt="Ilustração representando saúde, bem-estar e conhecimento" loading="lazy">
  </div>
</section>

<section class="vz-quick-tools" aria-labelledby="tools-title">
  <h2 id="tools-title">Ferramentas Rápidas</h2>
  <p class="vz-section-subtitle">Acesse instantaneamente as ferramentas mais utilizadas para monitorar sua saúde</p>
  <div class="vz-cards">
    <a class="vz-card" href="<?php echo h(BASE_URL); ?>imc.php">
      <div class="vz-card__icon">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
          <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
          <path d="M9 14h6"></path>
          <path d="M9 18h6"></path>
          <path d="M9 10h6"></path>
        </svg>
      </div>
      <h3>Calculadora de IMC</h3>
      <p>Calcule seu Índice de Massa Corporal e receba orientações personalizadas para sua saúde.</p>
      <span class="vz-card__cta">Calcular IMC →</span>
    </a>
    
    <a class="vz-card" href="<?php echo h(BASE_URL); ?>agua.php">
      <div class="vz-card__icon">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
          <path d="M12 8v8"></path>
          <path d="M8 12h8"></path>
        </svg>
      </div>
      <h3>Hidratação Diária</h3>
      <p>Descubra exatamente quanta água você precisa beber por dia baseado no seu perfil.</p>
      <span class="vz-card__cta">Calcular Hidratação →</span>
    </a>
  </div>
</section>

<section class="vz-featured" aria-labelledby="featured-title">
  <h2 id="featured-title">Conteúdo em Destaque</h2>
  <p class="vz-section-subtitle">Explore nossa biblioteca completa de conhecimento prático</p>
  <div class="vz-featured__banners">
    <a class="vz-banner" href="<?php echo h(BASE_URL); ?>receitas.php">
      <img src="<?php echo h(BASE_URL); ?>assets/img/banner.receitas.jpg" alt="Ebooks de Receitas Saudáveis" loading="lazy">
      <div class="vz-banner__content">
        <span class="vz-banner__title">Receitas Saudáveis</span>
        <p class="vz-banner__subtitle">Mais de 100 receitas nutritivas e deliciosas</p>
      </div>
    </a>
    
    <a class="vz-banner" href="<?php echo h(BASE_URL); ?>exercicios.php">
      <img src="<?php echo h(BASE_URL); ?>assets/img/exercicios.banner.jpg" alt="Ebooks de Exercícios e Treinos" loading="lazy">
      <div class="vz-banner__content">
        <span class="vz-banner__title">Exercícios & Treinos</span>
        <p class="vz-banner__subtitle">Programas personalizados para todos os níveis</p>
      </div>
    </a>
  </div>
</section>

<section class="vz-stats" aria-labelledby="stats-title">
  <h2 id="stats-title" class="vz-visually-hidden">Estatísticas</h2>
  <div class="vz-stats__grid">
    <div class="vz-stat">
      <div class="vz-stat__number">500+</div>
      <div class="vz-stat__label">Receitas Testadas</div>
    </div>
    <div class="vz-stat">
      <div class="vz-stat__number">200+</div>
      <div class="vz-stat__label">Exercícios</div>
    </div>
    <div class="vz-stat">
      <div class="vz-stat__number">50+</div>
      <div class="vz-stat__label">Ebooks</div>
    </div>
    <div class="vz-stat">
      <div class="vz-stat__number">10k+</div>
      <div class="vz-stat__label">Usuários Ativos</div>
    </div>
  </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
