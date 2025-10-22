<?php
require_once __DIR__ . '/includes/header.php';

// Verificar se o usuário está logado
if (empty($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$errors = [];
$success = '';
$user_id = $_SESSION['user']['id'];
$conn = db();

// Processar formulário de adição de ebook
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_ebook') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $file = $_FILES["file"] ?? null;
        $cover_image = $_FILES["cover_image"] ?? null;
        
        // Validação básica dos campos de texto e presença dos arquivos
        if ($title === '' || $description === '' || $author === '' || $category === '') {
            $errors[] = 'Preencha todos os campos obrigatórios.';
        } else {
            // Validação do arquivo do e-book
            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Erro ao fazer upload do arquivo do e-book. Tente novamente.';
            } else {
                $file_type = mime_content_type($file['tmp_name']);
                if ($file_type !== 'application/pdf') {
                    $errors[] = 'Apenas arquivos PDF são permitidos para o e-book.';
                } elseif ($file['size'] > 50 * 1024 * 1024) { // 50MB
                    $errors[] = 'O arquivo do e-book não pode exceder 50MB.';
                }
            }

            // Validação da imagem de capa
            if (!$cover_image || $cover_image['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Erro ao fazer upload da imagem de capa. Tente novamente.';
            } else {
                $cover_type = mime_content_type($cover_image['tmp_name']);
                if (!in_array($cover_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                    $errors[] = 'Apenas imagens (JPG, PNG, GIF, WEBP) são permitidas para a capa.';
                } elseif ($cover_image['size'] > 5 * 1024 * 1024) { // 5MB
                    $errors[] = 'A imagem de capa não pode exceder 5MB.';
                }
            }

            // Se não houver erros, prosseguir com o upload e salvamento no DB
            if (empty($errors)) {
                // Criar diretórios de uploads se não existirem
                $upload_dir = __DIR__ . '/uploads/ebooks/';
                $cover_upload_dir = __DIR__ . '/uploads/covers/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                if (!is_dir($cover_upload_dir)) {
                    mkdir($cover_upload_dir, 0755, true);
                }
                
                // Gerar nome único para o arquivo do e-book
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file_name = uniqid('ebook_', true) . '.' . $file_extension;
                $file_path_full = $upload_dir . $file_name;

                // Gerar nome único para o arquivo de capa
                $cover_extension = pathinfo($cover_image['name'], PATHINFO_EXTENSION);
                $cover_name = uniqid('cover_', true) . '.' . $cover_extension;
                $cover_path_full = $cover_upload_dir . $cover_name;
                
                // Mover arquivo do e-book e capa
                if (move_uploaded_file($file['tmp_name'], $file_path_full) && move_uploaded_file($cover_image['tmp_name'], $cover_path_full)) {
                    // Inserir no banco de dados
                    if ($conn) {
                        $stmt = $conn->prepare('
                            INSERT INTO user_ebooks (user_id, title, description, author, category, file_path, cover_path, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                        ');
                        if ($stmt) {
                            $full_file_path_for_db = 'uploads/ebooks/' . $file_name;
                            $full_cover_path_for_db = 'uploads/covers/' . $cover_name;
                            $stmt->bind_param('issssss', $user_id, $title, $description, $author, $category, $full_file_path_for_db, $full_cover_path_for_db);
                            if ($stmt->execute()) {
                                $success = 'Ebook postado com sucesso!';
                            } else {
                                $errors[] = 'Erro ao salvar no banco de dados: ' . $stmt->error;
                                unlink($file_path_full); // Remover arquivo se falhar
                                unlink($cover_path_full); // Remover capa se falhar
                            }
                        } else {
                            $errors[] = 'Erro ao preparar a consulta: ' . $conn->error;
                            unlink($file_path_full);
                            unlink($cover_path_full);
                        }
                    } else {
                        $errors[] = 'Conexão com banco de dados não disponível.';
                        unlink($file_path_full);
                        unlink($cover_path_full);
                    }
                } else {
                    $errors[] = 'Erro ao fazer upload do arquivo ou da capa.';
                }
            }
        }
    } elseif ($action === 'delete_ebook') {
        $ebook_id = (int)($_POST['ebook_id'] ?? 0);
        
        if ($ebook_id > 0 && $conn) {
            // Buscar ebook para verificar propriedade e obter caminho do arquivo
            $stmt = $conn->prepare('SELECT file_path, cover_path FROM user_ebooks WHERE id = ? AND user_id = ?');
            if ($stmt) {
                $stmt->bind_param('ii', $ebook_id, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $ebook = $result->fetch_assoc();
                
                if ($ebook) {
                    // Deletar arquivo
                    $file_path = __DIR__ . '/' . $ebook['file_path'];
                    $cover_path = __DIR__ . '/' . $ebook['cover_path'];

                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                    if (file_exists($cover_path)) {
                        unlink($cover_path);
                    }
                    
                    // Deletar do banco de dados
                    $stmt = $conn->prepare('DELETE FROM user_ebooks WHERE id = ? AND user_id = ?');
                    if ($stmt) {
                        $stmt->bind_param('ii', $ebook_id, $user_id);
                        if ($stmt->execute()) {
                            $success = 'Ebook removido com sucesso!';
                        } else {
                            $errors[] = 'Erro ao deletar ebook: ' . $stmt->error;
                        }
                    }
                } else {
                    $errors[] = 'Ebook não encontrado ou você não tem permissão para deletá-lo.';
                }
            }
        }
    }
}

// Buscar ebooks do usuário
$user_ebooks = [];
if ($conn) {
    $stmt = $conn->prepare('
        SELECT id, title, description, author, category, created_at, file_path, cover_path
        FROM user_ebooks
        WHERE user_id = ?
        ORDER BY created_at DESC
    ');
    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $user_ebooks[] = $row;
        }
    }
}
?>

<?php if (!empty($_SESSION['user'])): ?>
<!-- PÁGINA DE POSTAGEM DE EBOOKS -->
<section class="vz-ebook-page">
  <div class="vz-ebook-hero">
    <div class="vz-ebook-hero__content">
      <h1>Área do Criador</h1>
      <p>Compartilhe seus conhecimentos com a comunidade VerdeZero</p>
    </div>
  </div>

  <div class="vz-ebook-content">
    <div class="vz-ebook-grid">
      <!-- Formulário de Adição de Ebook -->
      <div class="vz-ebook-section">
        <div class="vz-ebook-section__header">
          <h2>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 5v14"></path>
              <path d="M5 12h14"></path>
            </svg>
            Postar Novo Ebook
          </h2>
          <p>Adicione um novo ebook à plataforma</p>
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

        <form class="vz-ebook-form" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="add_ebook">

          <div class="vz-form__row">
            <label for="title">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
              Título do Ebook
            </label>
            <input type="text" id="title" name="title" placeholder="Digite o título do seu ebook" required>
          </div>

          <div class="vz-form__row">
            <label for="author">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              Autor
            </label>
            <input type="text" id="author" name="author" placeholder="Digite o nome do autor" required>
          </div>

          <div class="vz-form__row">
            <label for="category">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
              </svg>
              Categoria
            </label>
            <select id="category" name="category" required>
              <option value="">Selecione uma categoria</option>
              <option value="receitas">Receitas</option>
              <option value="exercicios">Exercícios</option>
            </select>
          </div>

          <div class="vz-form__row">
            <label for="description">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
              Descrição
            </label>
            <textarea id="description" name="description" placeholder="Descreva o conteúdo do seu ebook" rows="5" required></textarea>
            <div class="vz-form__help">Máximo 500 caracteres</div>
          </div>

          <div class="vz-form__row">
            <label for="file">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
              </svg>
              Arquivo PDF
            </label>
            <div class="vz-file-upload">
              <input type="file" id="file" name="file" accept=".pdf" required>
              <div class="vz-file-upload__hint">Máximo 50MB • Apenas PDF</div>
            </div>
          </div>

          <div class="vz-form__row">
            <label for="cover_image">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="17 8 12 3 7 8"></polyline>
                <line x1="12" y1="3" x2="12" y2="15"></line>
              </svg>
              Imagem de Capa (JPG, PNG, GIF, WEBP)
            </label>
            <div class="vz-file-upload">
              <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/gif,image/webp" required>
              <div class="vz-file-upload__hint">Máximo 5MB</div>
            </div>
          </div>

          <div class="vz-form__actions">
            <button class="vz-btn" type="submit">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17,21 17,13 7,13 7,21"></polyline>
                <polyline points="7,3 7,8 15,8"></polyline>
              </svg>
              Postar Ebook
            </button>
          </div>
        </form>
      </div>

      <!-- Ebooks Postados -->
      <div class="vz-ebook-section">
        <div class="vz-ebook-section__header">
          <h2>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="4 7 4 4 20 4 20 7"></polyline>
              <line x1="9" y1="20" x2="15" y2="20"></line>
              <line x1="12" y1="17" x2="12" y2="20"></line>
              <path d="M18 20v-2a3 3 0 0 0-3-3H9a3 3 0 0 0-3 3v2"></path>
              <path d="M7 4v20h10V4"></path>
              <line x1="12" y1="12" x2="12" y2="12"></line>
            </svg>
            Meus Ebooks Postados
          </h2>
          <p>Gerencie os ebooks que você compartilhou</p>
        </div>

        <?php if (!empty($user_ebooks)): ?>
          <div class="vz-ebook-list">
            <?php foreach ($user_ebooks as $ebook): ?>
              <div class="vz-ebook-item">
                <div class="vz-ebook-item__cover">
                  <img src="<?php echo h(BASE_URL . $ebook['cover_path']); ?>" alt="Capa: <?php echo h($ebook['title']); ?>">
                </div>
                <div class="vz-ebook-item__details">
                  <h3><?php echo h($ebook['title']); ?></h3>
                  <p><strong>Autor:</strong> <?php echo h($ebook['author']); ?></p>
                  <p><strong>Categoria:</strong> <?php echo h(ucfirst($ebook['category'])); ?></p>
                  <p class="vz-meta">Postado em: <?php echo date('d/m/Y', strtotime($ebook['created_at'])); ?></p>
                  <div class="vz-ebook-item__actions">
                    <a href="<?php echo h(BASE_URL . $ebook['file_path']); ?>" class="vz-btn vz-btn--small" download>Baixar PDF</a>
                    <form action="" method="post" onsubmit="return confirm('Tem certeza que deseja deletar este ebook?');">
                      <input type="hidden" name="action" value="delete_ebook">
                      <input type="hidden" name="ebook_id" value="<?php echo (int)$ebook['id']; ?>">
                      <button type="submit" class="vz-btn vz-btn--small vz-btn--danger">Deletar</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p>Você ainda não postou nenhum ebook.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>