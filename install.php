<?php
session_start();

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    // Prevent re-running if setup is already complete
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_name = $_POST['db_name'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';

    $admin_name = $_POST['admin_name'] ?? 'Super Admin';
    $admin_email = $_POST['admin_email'] ?? 'admin@biharvihaan.com';
    $admin_pass = $_POST['admin_pass'] ?? 'password';

    if (empty($db_name) || empty($db_user)) {
        $error = 'Database Name and Database User are required.';
    } else {
        try {
            // Test PDO connection
            $dsn = "mysql:host=$db_host;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Create database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$db_name`");

            // Load schema file
            $sqlFile = __DIR__ . '/database.sql';
            if (!file_exists($sqlFile)) {
                throw new Exception("database.sql file not found in the root directory!");
            }

            $sql = file_get_contents($sqlFile);
            
            // Execute database.sql
            $pdo->exec($sql);

            // Update default admin password if provided
            $hashedPass = password_hash($admin_pass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = 1");
            $stmt->execute([$admin_name, $admin_email, $hashedPass]);

            // Generate .env file
            $envContent = "APP_ENV=production\n" .
                          "APP_DEBUG=false\n" .
                          "APP_URL=http://localhost/biharvihaan\n\n" .
                          "DB_HOST=$db_host\n" .
                          "DB_PORT=3306\n" .
                          "DB_DATABASE=$db_name\n" .
                          "DB_USERNAME=$db_user\n" .
                          "DB_PASSWORD=$db_pass\n\n" .
                          "# Mock Integrations Settings\n" .
                          "RAZORPAY_KEY_ID=rzp_test_BiharVihaan\n" .
                          "RAZORPAY_KEY_SECRET=bihar_vihaan_secret_key\n" .
                          "RECAPTCHA_SITE_KEY=recaptcha_site_key\n" .
                          "GOOGLE_MAPS_KEY=google_maps_key\n";

            file_put_contents($envFile, $envContent);
            $success = 'Database installed successfully! Setup is now complete.';
        } catch (Exception $e) {
            $error = 'Installation failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bihar Vihaan Enterprise - Database Installer</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0f172a;
            --card-dark: #1e293b;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #14b8a6;
            --primary-glow: rgba(20, 184, 166, 0.15);
            --secondary: #f97316;
            --error: #ef4444;
            --success: #10b981;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: 
                radial-gradient(at 0% 0%, rgba(20, 184, 166, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(249, 115, 22, 0.1) 0px, transparent 50%);
        }

        .installer-container {
            width: 100%;
            max-width: 550px;
            padding: 2.5rem;
            background: var(--card-dark);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .subtitle {
            color: var(--text-muted);
            margin-top: 0.5rem;
            font-size: 0.95rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid var(--error);
            color: #fca5a5;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid var(--success);
            color: #a7f3d0;
        }

        .form-section {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 1.5rem;
            margin-top: 1.5rem;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 0.95rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .btn {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, var(--primary), #0d9488);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, filter 0.2s;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            filter: brightness(1.1);
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #059669);
        }
    </style>
</head>
<body>

<div class="installer-container">
    <div class="header">
        <h1 class="logo">Bihar Vihaan Enterprise</h1>
        <div class="subtitle">Production-Ready Installer Portal</div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <strong>Setup Error:</strong><br>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <strong>Success:</strong><br>
            <?= htmlspecialchars($success) ?>
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="index.php" class="btn btn-success">Open Application Home Page</a>
        </div>
    <?php else: ?>
        <form method="POST" action="">
            <div class="section-title">
                <span>⚡</span> Database Configuration
            </div>
            
            <div class="form-group">
                <label for="db_host">MySQL Host Name</label>
                <input type="text" id="db_host" name="db_host" value="localhost" placeholder="e.g. localhost" required>
            </div>

            <div class="form-group">
                <label for="db_name">MySQL Database Name</label>
                <input type="text" id="db_name" name="db_name" placeholder="e.g. biharvihaan_db" required>
            </div>

            <div class="form-group">
                <label for="db_user">MySQL Username</label>
                <input type="text" id="db_user" name="db_user" placeholder="e.g. root" required>
            </div>

            <div class="form-group">
                <label for="db_pass">MySQL Password</label>
                <input type="password" id="db_pass" name="db_pass" placeholder="Database password (leave blank if none)">
            </div>

            <div class="form-section">
                <div class="section-title">
                    <span>👤</span> Super Administrator Setup
                </div>

                <div class="form-group">
                    <label for="admin_name">Admin Full Name</label>
                    <input type="text" id="admin_name" name="admin_name" value="Super Admin Vihaan" required>
                </div>

                <div class="form-group">
                    <label for="admin_email">Admin Email Address</label>
                    <input type="email" id="admin_email" name="admin_email" value="admin@biharvihaan.com" required>
                </div>

                <div class="form-group">
                    <label for="admin_pass">Admin Login Password</label>
                    <input type="password" id="admin_pass" name="admin_pass" value="password" required>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn">Configure &amp; Initialize System</button>
            </div>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
