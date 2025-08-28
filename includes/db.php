<?php
// includes/db.php
// Conexão MySQL robusta + utilitários usados em todo o site.

if (!defined('VERDEZERO_BOOTSTRAP')) {
    define('VERDEZERO_BOOTSTRAP', true);
}

define('DB_HOST', getenv('VERDEZERO_DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('VERDEZERO_DB_NAME') ?: 'verdezero');
define('DB_USER', getenv('VERDEZERO_DB_USER') ?: 'root');
define('DB_PASS', getenv('VERDEZERO_DB_PASS') ?: '');

// URL base (ajuste se necessário, ex.: '/verdezero' quando estiver em htdocs/verdezero)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
    define('BASE_URL', (BASE_PATH === '' ? '/' : BASE_PATH . '/'));
}

function db() {
    static $mysqli = null;
    if ($mysqli !== null) return $mysqli;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $mysqli->set_charset('utf8mb4');
        return $mysqli;
    } catch (Throwable $e) {
        // Fallback: sem DB. Retornamos null e o site funciona com dados mock.
        return null;
    }
}

// Sanitização simples para saída HTML
function h($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Recupera lista de ebooks; se o DB falhar, usa dados de exemplo.
function get_ebooks(?string $category = null): array {
    $conn = db();
    if ($conn) {
        if ($category) {
            $stmt = $conn->prepare('SELECT id, title, author, price_cents, category, cover_path, featured, promo FROM ebooks WHERE category = ? ORDER BY featured DESC, id DESC');
            $stmt->bind_param('s', $category);
        } else {
            $stmt = $conn->prepare('SELECT id, title, author, price_cents, category, cover_path, featured, promo FROM ebooks ORDER BY featured DESC, id DESC');
        }
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    // Fallback estático
    $fallback = [
        ['id'=>1,'title'=>'Cozinha Verde Essencial','author'=>'Equipe VerdeZero','price_cents'=>4900,'category'=>'receitas','cover_path'=>'assets/img/cover_receitas_1.svg','featured'=>1,'promo'=>0],
        ['id'=>2,'title'=>'Treinos para Iniciantes','author'=>'Ana Lima','price_cents'=>3900,'category'=>'exercicios','cover_path'=>'assets/img/cover_exercicios_1.svg','featured'=>1,'promo'=>1],
        ['id'=>3,'title'=>'Sopas Funcionais','author'=>'Luís Prado','price_cents'=>2900,'category'=>'receitas','cover_path'=>'assets/img/cover_receitas_2.svg','featured'=>0,'promo'=>0],
        ['id'=>4,'title'=>'Mobilidade e Alongamento','author'=>'Rafael Souza','price_cents'=>3500,'category'=>'exercicios','cover_path'=>'assets/img/cover_exercicios_2.svg','featured'=>0,'promo'=>0],
    ];
    if ($category) {
        $fallback = array_values(array_filter($fallback, fn($e) => $e['category'] === $category));
    }
    return $fallback;
}

function cents_to_brl(int $cents): string {
    return 'R$ ' . number_format($cents / 100, 2, ',', '.');
}

// Auth helpers
function find_user_by_email(string $email) {
    $conn = db();
    if (!$conn) return null;
    $stmt = $conn->prepare('SELECT id, name, email, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function create_user(string $name, string $email, string $password): bool {
    $conn = db();
    if (!$conn) return false;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $name, $email, $hash);
    return $stmt->execute();
}

function update_user(int $id, string $name, string $email, ?string $newPassword): bool {
    $conn = db();
    if (!$conn) return false;
    if ($newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('UPDATE users SET name=?, email=?, password_hash=? WHERE id=?');
        $stmt->bind_param('sssi', $name, $email, $hash, $id);
    } else {
        $stmt = $conn->prepare('UPDATE users SET name=?, email=? WHERE id=?');
        $stmt->bind_param('ssi', $name, $email, $id);
    }
    return $stmt->execute();
}
