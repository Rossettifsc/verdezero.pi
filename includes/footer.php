<?php
// includes/footer.php
?>
</main>
<footer class="vz-footer">
  <div class="vz-footer__content">
    <div class="vz-footer__main">
      <div class="vz-footer__brand">
        <div class="vz-footer__logo">
          <img src="<?php echo h(BASE_URL); ?>assets/img/verdezero.logo.png" alt="VerdeZero" width="auto" height="40">
        </div>
        <p>Transformando vidas através do conhecimento e prática de hábitos saudáveis. Sem promessas milagrosas, apenas ciência e dedicação.</p>
        <div class="vz-footer__social">
          <a href="#" aria-label="Siga-nos no Instagram" class="vz-social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
            </svg>
          </a>
          <a href="#" aria-label="Siga-nos no YouTube" class="vz-social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
              <polygon points="9.75,15.02 15.5,11.75 9.75,8.48 9.75,15.02"></polygon>
            </svg>
          </a>
          <a href="#" aria-label="Siga-nos no LinkedIn" class="vz-social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
              <rect x="2" y="9" width="4" height="12"></rect>
              <circle cx="4" cy="4" r="2"></circle>
            </svg>
          </a>
        </div>
      </div>
      
      <div class="vz-footer__links">
        <div class="vz-footer__section">
          <h3>Ferramentas</h3>
          <ul>
            <li><a href="<?php echo h(BASE_URL); ?>imc.php">Calculadora IMC</a></li>
            <li><a href="<?php echo h(BASE_URL); ?>agua.php">Hidratação Diária</a></li>
          </ul>
        </div>
        
        <div class="vz-footer__section">
          <h3>Conteúdo</h3>
          <ul>
            <li><a href="<?php echo h(BASE_URL); ?>ebooks.php">Ebooks</a></li>
            <li><a href="<?php echo h(BASE_URL); ?>receitas.php">Receitas Saudáveis</a></li>
            <li><a href="<?php echo h(BASE_URL); ?>exercicios.php">Programas de Treino</a></li>
          </ul>
        </div>
        
        <div class="vz-footer__section">
          <h3>Suporte</h3>
          <ul>
            <li><a href="#">Central de Ajuda</a></li>
            <li><a href="#">Contato</a></li>
            <li><a href="#">Política de Privacidade</a></li>
            <li><a href="#">Termos de Uso</a></li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="vz-footer__bottom">
      <div class="vz-footer__divider"></div>
      <div class="vz-footer__credits">
        <p>&copy; 2025 VerdeZero. Feito com <span class="vz-heart">❤️</span>para promover saúde e bem-estar.</p>
        <p class="vz-footer__disclaimer">Créditos e imagens meramente ilustrativas. Consulte sempre um profissional de saúde.</p>
      </div>
    </div>
  </div>
</footer>
<script src="<?php echo h(BASE_URL); ?>assets/js/main.js"></script>
</body>
</html>
