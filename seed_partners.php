<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance()->getConnection();

// Create partners table
$db->exec("
CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(100),
    short_description TEXT,
    description TEXT,
    mission TEXT,
    vision TEXT,
    logo VARCHAR(255),
    website VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Create partner_gallery table
$db->exec("
CREATE TABLE IF NOT EXISTS partner_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    partner_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (partner_id) REFERENCES partners(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Seed data
$partners = [
    [
        'Bihar Tourism', 'bihar-tourism', 'Tourism Promotion Partner', 'Collaborating to elevate Bihar\'s tourism landscape on a global stage.', 
        'The Department of Tourism, Bihar is responsible for the development and promotion of tourism in the state. We collaborate to bring Bihar\'s rich history and culture to the world.',
        'To promote sustainable tourism and showcase the state\'s heritage.', 'To make Bihar the most preferred tourist destination in India.',
        'https://ui-avatars.com/api/?name=BT&background=E5E7EB&color=1F2937&rounded=true', 'https://tourism.bihar.gov.in', 'contact@tourism.bihar.gov.in', '+91 612 2222622', 'Old Secretariat, Patna, Bihar'
    ],
    [
        'Nalanda University', 'nalanda-university', 'Education & Heritage Partner', 'Reviving the ancient academic legacy and research methodologies.',
        'Nalanda University is an international and research-intensive university established to recreate the glory of ancient Nalanda.',
        'To build an institution of higher learning that fosters intellectual curiosity.', 'To be a global center for knowledge and cultural exchange.',
        'https://ui-avatars.com/api/?name=NU&background=E5E7EB&color=1F2937&rounded=true', 'https://nalandauniv.edu.in', 'info@nalandauniv.edu.in', '+91 6112 255330', 'Rajgir, District Nalanda, Bihar'
    ],
    [
        'Bihar Museum', 'bihar-museum', 'Cultural Preservation Partner', 'Preserving artifacts and chronicling the grand history of Bihar.',
        'A world-class museum in Patna showcasing the history of Bihar and the Indian subcontinent.',
        'To preserve the rich cultural heritage and artifacts of Bihar.', 'To educate and inspire generations through historical exhibits.',
        'https://ui-avatars.com/api/?name=BM&background=E5E7EB&color=1F2937&rounded=true', 'https://biharmuseum.org', 'info@biharmuseum.org', '+91 612 2235555', 'Jawaharlal Nehru Marg, Patna, Bihar'
    ],
    [
        'Patna Sahib Management Board', 'patna-sahib-management-board', 'Spiritual Partner', 'Managing the sacred Takht Sri Patna Sahib for global pilgrims.',
        'The board manages the affairs of Takht Sri Harmandir Ji Patna Sahib, the birthplace of Guru Gobind Singh Ji.',
        'To serve the pilgrims and maintain the sanctity of the Gurdwara.', 'To spread the teachings of Sikh Gurus globally.',
        'https://ui-avatars.com/api/?name=PSMB&background=E5E7EB&color=1F2937&rounded=true', 'https://takhtpatnasahib.in', 'admin@takhtpatnasahib.in', '+91 612 2641400', 'Patna Sahib, Patna, Bihar'
    ],
    [
        'Madhubani Art Collective', 'madhubani-art-collective', 'Cultural Partner', 'Preserving and commercializing authentic Mithila artwork internationally.',
        'A collective of traditional artists from the Mithila region focused on preserving Madhubani painting.',
        'To empower local artisans and preserve traditional art forms.', 'To bring Madhubani art into the global contemporary art space.',
        'https://ui-avatars.com/api/?name=MAC&background=E5E7EB&color=1F2937&rounded=true', 'https://madhubaniart.org', 'contact@madhubaniart.org', '+91 99999 88888', 'Madhubani, Bihar'
    ],
    [
        'Bihar Heritage Foundation', 'bihar-heritage-foundation', 'Heritage Preservation Partner', 'Restoring monuments and documenting the historical artifacts of Pataliputra.',
        'An NGO dedicated to the restoration and preservation of Bihar\'s physical and cultural heritage.',
        'To protect, restore, and promote Bihar\'s ancient sites.', 'A Bihar where every historical monument is preserved for posterity.',
        'https://ui-avatars.com/api/?name=BHF&background=E5E7EB&color=1F2937&rounded=true', 'https://biharheritage.org', 'hello@biharheritage.org', '+91 88888 77777', 'Patna, Bihar'
    ],
    [
        'IIT Patna', 'iit-patna', 'Technology & Research Partner', 'Fostering innovation and technological advancement in the region.',
        'Indian Institute of Technology Patna is one of the new IITs established by an Act of the Indian Parliament.',
        'To generate new knowledge through research.', 'To be a premier center of technical education and research.',
        'https://ui-avatars.com/api/?name=IITP&background=E5E7EB&color=1F2937&rounded=true', 'https://iitp.ac.in', 'info@iitp.ac.in', '+91 6115 233000', 'Bihta, Patna, Bihar'
    ],
    [
        'Bihar State Tourism Development Corporation', 'bstdc', 'Tourism Operations Partner', 'Operating tourist bungalows and managing infrastructural tourism assets.',
        'BSTDC develops tourism infrastructure and operates properties across Bihar to facilitate tourists.',
        'To provide affordable and quality infrastructure to tourists.', 'To make Bihar a world-class tourism hub.',
        'https://ui-avatars.com/api/?name=BSTDC&background=E5E7EB&color=1F2937&rounded=true', 'https://bstdc.bihar.gov.in', 'contact@bstdc.bihar.gov.in', '+91 612 2225411', 'Bir Chand Patel Path, Patna, Bihar'
    ]
];

$stmt = $db->prepare("INSERT IGNORE INTO partners (name, slug, category, short_description, description, mission, vision, logo, website, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($partners as $partner) {
    $stmt->execute($partner);
}

echo "Database partners tables created and seeded.\n";
