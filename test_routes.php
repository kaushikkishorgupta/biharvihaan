<?php
$urls = [
    'http://localhost/biharvihaan/',
    'http://localhost/biharvihaan/tourism',
    'http://localhost/biharvihaan/directory',
    'http://localhost/biharvihaan/gallery',
    'http://localhost/biharvihaan/services',
    'http://localhost/biharvihaan/about',
    'http://localhost/biharvihaan/contact',
    'http://localhost/biharvihaan/shop',
    'http://localhost/biharvihaan/login',
    'http://localhost/biharvihaan/superadmin',
    'http://localhost/biharvihaan/superadmin/dashboard',
    'http://localhost/biharvihaan/admin'
];

foreach ($urls as $url) {
    $headers = @get_headers($url);
    if ($headers && isset($headers[0])) {
        echo "$url : " . $headers[0] . "\n";
    } else {
        echo "$url : Failed\n";
    }
}
