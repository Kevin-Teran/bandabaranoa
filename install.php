<?php
/**
 * @file install.php
 * @route /install.php
 * @description Script de Instalación Automática.
 * ¡Ejecutar una sola vez!
 * @author Kevin Mariano
 * @version 1.0.0
 * @since 1.0.0
 * @copyright Banda de Baranoa 2025
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'banda_baranoa');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<div style='font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background:#f9f9f9; border:1px solid #ddd;'>";
    echo "<h1>🚀 Instalando Sistema Banda de Baranoa...</h1>";

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✅ Base de datos lista.</p>";
    $pdo->exec("USE `" . DB_NAME . "`");

    $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'editor') DEFAULT 'admin',
        status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlUsers);
    echo "<p>✅ Tabla <b>users</b> creada (con estado).</p>";

    $sqlNews = "CREATE TABLE IF NOT EXISTS news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        summary TEXT,
        content LONGTEXT,
        image_path VARCHAR(255),
        views INT DEFAULT 0,
        status ENUM('published', 'draft', 'archived') DEFAULT 'published',
        featured BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlNews);
    echo "<p>✅ Tabla <b>news</b> creada.</p>";

    $sqlNewsGallery = "CREATE TABLE IF NOT EXISTS news_gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        news_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        caption VARCHAR(255) NULL,
        sort_order INT DEFAULT 0,
        FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlNewsGallery);
    echo "<p>✅ Tabla <b>news_gallery</b> creada.</p>";

    $sqlEvents = "CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        start_date DATETIME NOT NULL,
        end_date DATETIME NULL,
        location VARCHAR(255),
        description TEXT,
        image_path VARCHAR(255),
        map_url TEXT NULL,
        status ENUM('published', 'draft', 'cancelled', 'completed') DEFAULT 'published',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlEvents);
    echo "<p>✅ Tabla <b>events</b> creada.</p>";

    $sqlGallery = "CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        description TEXT,
        image_path VARCHAR(255) NOT NULL,
        category VARCHAR(50) DEFAULT 'General',
        status ENUM('published', 'draft') DEFAULT 'published',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlGallery);
    echo "<p>✅ Tabla <b>gallery</b> creada.</p>";

    $sqlMessages = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100),
        phone VARCHAR(20),
        subject VARCHAR(150),
        message TEXT,
        is_read BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlMessages);
    echo "<p>✅ Tabla <b>messages</b> creada.</p>";

    $sqlSettings = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(50) UNIQUE NOT NULL,
        setting_value TEXT,
        description VARCHAR(255)
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlSettings);
    echo "<p>✅ Tabla <b>settings</b> creada.</p>";

    $sqlAudit = "CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(50),
        description TEXT,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($sqlAudit);
    echo "<p>✅ Tabla <b>audit_logs</b> creada.</p>";

    $adminUser = 'admin';
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$adminUser]);
    if ($stmt->rowCount() == 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, 'admin', 'active')")
            ->execute([$adminUser, 'admin@bandadebaranoa.com', $hash]);
        echo "<p>👤 Usuario Admin creado.</p>";
    }

    // 2. Configuración Base
    $settings = [
        ['site_name', 'Banda de Baranoa', 'Nombre del sitio'],
        ['contact_email', 'info@bandadebaranoa.com', 'Email contacto'],
        ['contact_phone', '+57 300 123 4567', 'Teléfono'],
        ['facebook_url', 'https://facebook.com/bandadebaranoa', 'Facebook'],
        ['instagram_url', 'https://instagram.com/bandadebaranoa', 'Instagram'],
        ['youtube_url', 'https://youtube.com', 'Youtube'],
        ['address', 'Baranoa, Atlántico, Colombia', 'Dirección física']
    ];
    $stmtSet = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
    foreach ($settings as $s) $stmtSet->execute($s);
    echo "<p>⚙️ Configuración base insertada.</p>";

    // 3. DATOS DE PRUEBA: NOTICIAS
    // Solo insertamos si la tabla está vacía
    $countNews = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
    if ($countNews == 0) {
        $dummyNews = [
            [
                'Gran Presentación en el Carnaval de Barranquilla',
                'gran-presentacion-carnaval-2025',
                'La Banda de Baranoa brilló con luz propia en la Batalla de Flores, deleitando a miles de espectadores con su repertorio.',
                '<p>La Banda Departamental de Baranoa fue protagonista en el Carnaval de Barranquilla. Con más de 300 músicos en escena, la agrupación interpretó clásicos del folclor caribeño...</p><p>El público ovacionó cada interpretación, confirmando por qué somos embajadores de la cultura.</p>',
                1 // Featured
            ],
            [
                'Abiertas las Inscripciones 2025',
                'abiertas-inscripciones-2025',
                'Si tienes talento y pasión por la música, esta es tu oportunidad de ser parte de nuestra familia.',
                '<p>Invitamos a todos los niños y jóvenes del departamento a participar en las audiciones anuales. Buscamos talentos en percusión, vientos y danza.</p><ul><li>Fecha: 15 de Marzo</li><li>Lugar: Sede Principal</li></ul>',
                1 // Featured
            ],
            [
                'Gira Internacional Confirmada: Destino Europa',
                'gira-internacional-europa',
                'Nos preparamos para llevar nuestro folclor al viejo continente en el verano de 2025.',
                '<p>Con gran orgullo anunciamos nuestra participación en festivales de España y Francia. Un intercambio cultural que enriquecerá a nuestros integrantes.</p>',
                0 // Not Featured
            ]
        ];
        
        $stmtNews = $pdo->prepare("INSERT INTO news (title, slug, summary, content, featured, status, image_path) VALUES (?, ?, ?, ?, ?, 'published', '')");
        foreach ($dummyNews as $n) {
            $stmtNews->execute($n);
        }
        echo "<p>📰 3 Noticias de prueba creadas.</p>";
    }

    // 4. DATOS DE PRUEBA: EVENTOS
    $countEvents = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
    if ($countEvents == 0) {
        $dummyEvents = [
            ['Concierto de Gala', 'concierto-gala-2025', date('Y-m-d H:i:s', strtotime('+1 week')), 'Teatro Amira de la Rosa', 'Noche de gala con repertorio sinfónico.'],
            ['Desfile de la Hispanidad', 'desfile-hispanidad', date('Y-m-d H:i:s', strtotime('+1 month')), 'New York, USA', 'Representando a Colombia en la 5ta Avenida.'],
            ['Ensayo General Abierto', 'ensayo-general-abril', date('Y-m-d H:i:s', strtotime('+3 days')), 'Sede Banda Baranoa', 'Ven y conoce cómo nos preparamos.']
        ];

        $stmtEv = $pdo->prepare("INSERT INTO events (title, slug, start_date, location, description, status, image_path) VALUES (?, ?, ?, ?, ?, 'published', '')");
        foreach ($dummyEvents as $e) {
            $stmtEv->execute($e);
        }
        echo "<p>📅 3 Eventos de prueba creados.</p>";
    }

    echo "<hr><h3 style='color:green'>🎉 ¡Instalación Completada con Éxito!</h3>";
    echo "<p>Ya puedes borrar este archivo e ir al <a href='admin/'>Panel de Administración</a>.</p>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<h2 style='color:red'>❌ Error Fatal</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>