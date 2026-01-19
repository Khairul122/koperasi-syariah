<?php
require_once 'config/koneksi.php';

$database = new Database();
$pdo = $database->getConnection();

if (!$pdo) {
    die("Koneksi database gagal!");
}

echo "<h3>Struktur Tabel tb_anggota:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";

// Ambil struktur tabel
$stmt = $pdo->query('DESCRIBE tb_anggota');
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

foreach ($columns as $column) {
    echo "<tr>";
    echo "<td><strong>{$column['Field']}</strong></td>";
    echo "<td>{$column['Type']}</td>";
    echo "<td>{$column['Null']}</td>";
    echo "<td>{$column['Key']}</td>";
    echo "<td>{$column['Default']}</td>";
    echo "<td>{$column['Extra']}</td>";
    echo "</tr>";
}

echo "</table>";

// Cek apakah field email ada
$hasEmail = false;
$hasFoto = false;
$hasUpdatedAt = false;

foreach ($columns as $column) {
    if ($column['Field'] === 'email') $hasEmail = true;
    if ($column['Field'] === 'foto') $hasFoto = true;
    if ($column['Field'] === 'updated_at') $hasUpdatedAt = true;
}

echo "<br><h3>Status Field:</h3>";
echo "Field 'email': " . ($hasEmail ? "<span style='color: green;'>✓ ADA</span>" : "<span style='color: red;'>✗ TIDAK ADA</span>") . "<br>";
echo "Field 'foto': " . ($hasFoto ? "<span style='color: green;'>✓ ADA</span>" : "<span style='color: red;'>✗ TIDAK ADA</span>") . "<br>";
echo "Field 'updated_at': " . ($hasUpdatedAt ? "<span style='color: green;'>✓ ADA</span>" : "<span style='color: red;'>✗ TIDAK ADA</span>") . "<br>";

if (!$hasEmail || !$hasFoto || !$hasUpdatedAt) {
    echo "<br><strong style='color: red;'>Beberapa field belum ditambahkan!</strong>";
}
?>
