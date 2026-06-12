<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance()->getConnection();

// Alter events table to add missing columns without dropping it
$db->exec("
ALTER TABLE events 
    ADD COLUMN IF NOT EXISTS slug VARCHAR(255) NULL AFTER title, 
    ADD COLUMN IF NOT EXISTS start_date DATE NULL AFTER location, 
    ADD COLUMN IF NOT EXISTS end_date DATE NULL AFTER start_date, 
    ADD COLUMN IF NOT EXISTS image VARCHAR(255) NULL AFTER end_date, 
    ADD COLUMN IF NOT EXISTS category VARCHAR(100) NULL AFTER image,
    MODIFY organizer_id INT(11) NULL,
    MODIFY max_tickets INT(11) NULL,
    MODIFY available_tickets INT(11) NULL,
    MODIFY time TIME NULL,
    MODIFY date DATE NULL,
    MODIFY description TEXT NULL,
    MODIFY location VARCHAR(150) NULL,
    MODIFY image_url VARCHAR(255) NULL;
");

// Seed data
$events = [
    ['Chhath Puja', 'chhath-puja', 'The most revered ancient Hindu Vedic festival historically native to Bihar.', 'Ganga Ghats, Patna', '2026-11-15', '2026-11-18', 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=800', 'Religious'],
    ['Sonepur Mela', 'sonepur-mela', 'Asia\'s largest cattle fair held on Kartik Poornima.', 'Sonepur, Saran', '2026-11-20', '2026-12-05', 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?w=800', 'Cultural'],
    ['Rajgir Mahotsav', 'rajgir-mahotsav', 'A festival of dance and music with performances by renowned artists.', 'Rajgir, Nalanda', '2026-12-25', '2026-12-27', 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=800', 'Festival'],
    ['Pitrapaksha Mela', 'pitrapaksha-mela', 'A Hindu ritual to pay homage to ancestors.', 'Gaya', '2026-09-15', '2026-09-30', 'https://images.unsplash.com/photo-1600096194534-95cf5ece04cf?w=800', 'Religious'],
    ['Sama Chakeva', 'sama-chakeva', 'A traditional festival celebrating brother-sister bonds.', 'Mithilanchal', '2026-11-01', '2026-11-15', 'https://images.unsplash.com/photo-1514222709107-a180c68d72b4?w=800', 'Cultural'],
    ['Madhubani Art Festival', 'madhubani-art-festival', 'Showcasing the world-famous Mithila paintings and artisans.', 'Madhubani', '2026-02-10', '2026-02-15', 'https://images.unsplash.com/photo-1579783900862-c0f25c7bc8aa?w=800', 'Art'],
    ['Bihula Festival', 'bihula-festival', 'A prominent festival of the Bhagalpur district praying for family welfare.', 'Bhagalpur', '2026-08-15', '2026-08-18', 'https://images.unsplash.com/photo-1623062831201-1b94b150c9dc?w=800', 'Religious'],
    ['Makar Sankranti Festival', 'makar-sankranti', 'Celebrated with traditional fairs and holy dips in the Ganges.', 'Across Bihar', '2026-01-14', '2026-01-15', 'https://images.unsplash.com/photo-1549420042-4fdfa069ba1e?w=800', 'Festival'],
    ['Buddha Purnima', 'buddha-purnima', 'Celebrating the birth, enlightenment, and death of Lord Buddha.', 'Bodh Gaya', '2026-05-01', '2026-05-03', 'https://images.unsplash.com/photo-1515542622106-78b28af7815b?w=800', 'Religious'],
    ['Eid Celebrations', 'eid-celebrations', 'A major festival of harmony and feasts.', 'Patna', '2026-03-20', '2026-03-22', 'https://images.unsplash.com/photo-1564630560244-a1a7c5c2fc7d?w=800', 'Religious'],
    ['Guru Gobind Singh Jayanti', 'guru-gobind-singh-jayanti', 'Prakash Parv celebrated grandly at Takht Sri Patna Sahib.', 'Patna Sahib', '2026-01-05', '2026-01-07', 'https://images.unsplash.com/photo-1588693959306-b511872bd78c?w=800', 'Religious']
];

$stmt = $db->prepare("INSERT IGNORE INTO events (title, slug, description, location, start_date, end_date, image, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($events as $event) {
    // Basic check to avoid duplicates if run multiple times
    $check = $db->prepare("SELECT id FROM events WHERE slug = ?");
    $check->execute([$event[1]]);
    if (!$check->fetch()) {
        $stmt->execute($event);
    }
}

echo "Database events table created and seeded.\n";
