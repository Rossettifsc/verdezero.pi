<?php
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['logout'])) {
    $_SESSION['user'] = null;
    unset($_SESSION['user']);
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'register') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['password'] ?? '';
        if ($name === '' || $email === '' || $pass === '') {
            $errors[] = 'Preencha todos os campos.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        } else {
            $exists = find_user_by_email($email);
            if ($exists) {
                $errors[] = 'E-mail já cadastrado.';
            } else {
                if (!create_user($name, $email, $pass)) {
                    $errors[] = 'Não foi possível registrar. Verifique a conexão com o banco.';
                } else {
                    $success = 'Conta criada com sucesso! Faça login para continuar.';
                }
            }
        }
    } elseif ($action === 'login') {
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['password'] ?? '';
        $u = find_user_by_email($email);
        if (!$u || !password_verify($pass, $u['password_hash'])) {
            $errors[] = 'Credenciais inválidas.';
        } else {
            $_SESSION['user'] = ['id'=>$u['id'], 'name'=>$u['name'], 'email'=>$u['email']];
            header('Location: ' . BASE_URL . 'login.php');
            exit;
        }
    } elseif ($action === 'update' && !empty($_SESSION['user'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $newPass = $_POST['password'] ?? null;
        if ($name === '' || $email === '') {
            $errors[] = 'Nome e e-mail são obrigatórios.';
        } else {
            $ok = update_user((int)$_SESSION['user']['id'], $name, $email, $newPass ? $newPass : null);
            if ($ok) {
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
                $success = 'Perfil atualizado com sucesso!';
            } else {
                $errors[] = 'Falha ao atualizar. Banco indisponível?';
            }
        }
    }
}
?>

<?php if (!empty($_SESSION['user'])): ?>
<!-- PÁGINA DE PERFIL -->
<section class="vz-profile-page">
  <div class="vz-profile-hero">
    <div class="vz-profile-hero__content">
      <div class="vz-profile-avatar">
        <div class="vz-profile-avatar__image">
          <img src="<?php echo h(BASE_URL); ?>assets/img/avatar.svg" alt="Avatar do usuário" loading="lazy">
          <div class="vz-profile-avatar__edit">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
          </div>
        </div>
        <div class="vz-profile-info">
          <h1>Olá, <?php echo h($_SESSION['user']['name']); ?>! 👋</h1>
          <p class="vz-profile-email"><?php echo h($_SESSION['user']['email']); ?></p>
          <div class="vz-profile-stats">
            <div class="vz-profile-stat">
              <span class="vz-profile-stat__number">12</span>
              <span class="vz-profile-stat__label">Ebooks Lidos</span>
            </div>
            <div class="vz-profile-stat">
              <span class="vz-profile-stat__number">8</span>
              <span class="vz-profile-stat__label">Receitas Testadas</span>
            </div>
            <div class="vz-profile-stat">
              <span class="vz-profile-stat__number">15</span>
              <span class="vz-profile-stat__label">Dias Ativo</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="vz-profile-content">
    <div class="vz-profile-grid">
      <!-- Formulário de Perfil -->
      <div class="vz-profile-section">
        <div class="vz-profile-section__header">
          <h2>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Informações do Perfil
          </h2>
          <p>Atualize suas informações pessoais e senha</p>
        </div>
        
        <?php if ($errors): ?>
          <div class="vz-alert vz-alert--danger">
            <div class="vz-alert__icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
              </svg>
            </div>
            <div class="vz-alert__content">
              <?php foreach ($errors as $e) echo '<p>' . h($e) . '</p>'; ?>
            </div>
          </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="vz-alert vz-alert--success">
            <div class="vz-alert__icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22,4 12,14.01 9,11.01"></polyline>
              </svg>
            </div>
            <div class="vz-alert__content">
              <p><?php echo h($success); ?></p>
            </div>
          </div>
        <?php endif; ?>

        <form class="vz-profile-form" method="post">
          <input type="hidden" name="action" value="update">
          
          <div class="vz-form__row">
            <label for="profile-name">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              Nome Completo
            </label>
            <input type="text" id="profile-name" name="name" value="<?php echo h($_SESSION['user']['name']); ?>" required>
          </div>
          
          <div class="vz-form__row">
            <label for="profile-email">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
              E-mail
            </label>
            <input type="email" id="profile-email" name="email" value="<?php echo h($_SESSION['user']['email']); ?>" required>
          </div>
          
          <div class="vz-form__row">
            <label for="profile-password">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <circle cx="12" cy="16" r="1"></circle>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              Nova Senha <span class="vz-form__optional">(opcional)</span>
            </label>
            <input type="password" id="profile-password" name="password" placeholder="Deixe em branco para manter a atual">
            <div class="vz-form__help">Mínimo 6 caracteres para maior segurança</div>
          </div>
          
          <div class="vz-form__actions">
            <button class="vz-btn" type="submit">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17,21 17,13 7,13 7,21"></polyline>
                <polyline points="7,3 7,8 15,8"></polyline>
              </svg>
              Salvar Alterações
            </button>
          </div>
        </form>
      </div>

      <!-- Atividades Recentes -->
      <div class="vz-profile-section">
        <div class="vz-profile-section__header">
          <h2>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12,6 12,12 16,14"></polyline>
            </svg>
            Atividades Recentes
          </h2>
          <p>Seu histórico de uso da plataforma</p>
        </div>
        
        <div class="vz-activity-list">
          <div class="vz-activity-item">
            <div class="vz-activity-item__icon vz-activity-item__icon--ebook">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
            </div>
            <div class="vz-activity-item__content">
              <div class="vz-activity-item__title">Ebook "Receitas Veganas" lido</div>
              <div class="vz-activity-item__time">Há 2 horas</div>
            </div>
          </div>
          
          <div class="vz-activity-item">
            <div class="vz-activity-item__icon vz-activity-item__icon--recipe">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4"></path>
                <path d="M21 12c-1 0-2-1-2-2s1-2 2-2 2 1 2 2-1 2-2 2z"></path>
                <path d="M3 12c1 0 2-1 2-2s-1-2-2-2-2 1-2 2 1 2 2 2z"></path>
              </svg>
            </div>
            <div class="vz-activity-item__content">
              <div class="vz-activity-item__title">Receita "Quinoa Colorida" salva</div>
              <div class="vz-activity-item__time">Ontem às 15:30</div>
            </div>
          </div>
          
          <div class="vz-activity-item">
            <div class="vz-activity-item__icon vz-activity-item__icon--exercise">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 4v16"></path>
                <path d="M18 4v16"></path>
                <path d="M2 12h20"></path>
              </svg>
            </div>
            <div class="vz-activity-item__content">
              <div class="vz-activity-item__title">Treino "Yoga Matinal" iniciado</div>
              <div class="vz-activity-item__time">2 dias atrás</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Ações Rápidas -->
      <div class="vz-profile-section">
        <div class="vz-profile-section__header">
          <h2>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
            </svg>
            Ações Rápidas
          </h2>
          <p>Acesse rapidamente as funcionalidades principais</p>
        </div>
        
        <div class="vz-quick-actions">
          <a href="<?php echo h(BASE_URL); ?>ebooks.php" class="vz-quick-action">
            <div class="vz-quick-action__icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
            </div>
            <div class="vz-quick-action__content">
              <h3>Explorar Ebooks</h3>
              <p>Descubra novos conhecimentos</p>
            </div>
            <div class="vz-quick-action__arrow">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
              </svg>
            </div>
          </a>
          
          <a href="<?php echo h(BASE_URL); ?>receitas.php" class="vz-quick-action">
            <div class="vz-quick-action__icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4"></path>
                <path d="M21 12c-1 0-2-1-2-2s1-2 2-2 2 1 2 2-1 2-2 2z"></path>
                <path d="M3 12c1 0 2-1 2-2s-1-2-2-2-2 1-2 2 1 2 2 2z"></path>
              </svg>
            </div>
            <div class="vz-quick-action__content">
              <h3>Ver Receitas</h3>
              <p>Receitas saudáveis e deliciosas</p>
            </div>
            <div class="vz-quick-action__arrow">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
              </svg>
            </div>
          </a>
          
          <a href="<?php echo h(BASE_URL); ?>exercicios.php" class="vz-quick-action">
            <div class="vz-quick-action__icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 4v16"></path>
                <path d="M18 4v16"></path>
                <path d="M2 12h20"></path>
              </svg>
            </div>
            <div class="vz-quick-action__content">
              <h3>Exercícios</h3>
              <p>Treinos personalizados</p>
            </div>
            <div class="vz-quick-action__arrow">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
              </svg>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php else: ?>
<!-- PÁGINA DE LOGIN/REGISTRO -->
<section class="vz-auth-page">
  <div class="vz-auth-hero">
    <div class="vz-auth-hero__content">
      <h1>Bem-vindo ao VerdeZero</h1>
      <p>Entre ou crie sua conta para acessar todo o conteúdo exclusivo e personalizar sua experiência</p>
      <div class="vz-auth-features">
        <div class="vz-auth-feature">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 12l2 2 4-4"></path>
            <path d="M21 12c-1 0-2-1-2-2s1-2 2-2 2 1 2 2-1 2-2 2z"></path>
            <path d="M3 12c1 0 2-1 2-2s-1-2-2-2-2 1-2 2 1 2 2 2z"></path>
          </svg>
          <span>Acesso a 500+ receitas</span>
        </div>
        <div class="vz-auth-feature">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 4v16"></path>
            <path d="M18 4v16"></path>
            <path d="M2 12h20"></path>
          </svg>
          <span>200+ exercícios</span>
        </div>
        <div class="vz-auth-feature">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
          </svg>
          <span>50+ ebooks exclusivos</span>
        </div>
      </div>
    </div>
  </div>

  <div class="vz-auth-content">
    <?php if ($errors): ?>
      <div class="vz-alert vz-alert--danger">
        <div class="vz-alert__icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
          </svg>
        </div>
        <div class="vz-alert__content">
          <?php foreach ($errors as $e) echo '<p>' . h($e) . '</p>'; ?>
        </div>
      </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="vz-alert vz-alert--success">
        <div class="vz-alert__icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22,4 12,14.01 9,11.01"></polyline>
          </svg>
        </div>
        <div class="vz-alert__content">
          <p><?php echo h($success); ?></p>
        </div>
      </div>
    <?php endif; ?>

    <div class="vz-auth-grid">
      <!-- Formulário de Login -->
      <div class="vz-auth-card">
        <div class="vz-auth-card__header">
          <div class="vz-auth-card__icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
              <polyline points="10,17 15,12 10,7"></polyline>
              <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
          </div>
          <h2>Entrar na Conta</h2>
          <p>Faça login para acessar seu perfil personalizado</p>
        </div>
        
        <form class="vz-auth-form" method="post">
          <input type="hidden" name="action" value="login">
          
          <div class="vz-form__row">
            <label for="login-email">E-mail</label>
            <input type="email" id="login-email" name="email" required placeholder="seu@email.com">
          </div>
          
          <div class="vz-form__row">
            <label for="login-password">Senha</label>
            <input type="password" id="login-password" name="password" required placeholder="••••••••">
          </div>
          
          <div class="vz-form__actions">
            <button class="vz-btn vz-btn--full" type="submit">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10,17 15,12 10,7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
              </svg>
              Entrar na Conta
            </button>
          </div>
        </form>
      </div>

      <!-- Formulário de Registro -->
      <div class="vz-auth-card">
        <div class="vz-auth-card__header">
          <div class="vz-auth-card__icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="8.5" cy="7" r="4"></circle>
              <line x1="20" y1="8" x2="20" y2="14"></line>
              <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
          </div>
          <h2>Criar Nova Conta</h2>
          <p>Junte-se à comunidade VerdeZero gratuitamente</p>
        </div>
        
        <form class="vz-auth-form" method="post">
          <input type="hidden" name="action" value="register">
          
          <div class="vz-form__row">
            <label for="register-name">Nome Completo</label>
            <input type="text" id="register-name" name="name" required placeholder="Seu nome completo">
          </div>
          
          <div class="vz-form__row">
            <label for="register-email">E-mail</label>
            <input type="email" id="register-email" name="email" required placeholder="seu@email.com">
          </div>
          
          <div class="vz-form__row">
            <label for="register-password">Senha</label>
            <input type="password" id="register-password" name="password" required placeholder="••••••••">
            <div class="vz-form__help">Mínimo 6 caracteres</div>
          </div>
          
          <div class="vz-form__actions">
            <button class="vz-btn vz-btn--full" type="submit">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
              </svg>
              Criar Conta
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
