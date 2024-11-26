<?php
session_start();
include 'koneksi.php';

$limit = 5;
$search = isset($_GET['search']) ? strtolower($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

$sql = "SELECT * FROM pendaftar";
if ($search) {
    $sql .= " WHERE LOWER(name) LIKE ? OR LOWER(email) LIKE ? OR LOWER(phone) LIKE ?";
    $searchTerm = "%" . $search . "%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$total_records = $result->num_rows;

$sql .= " LIMIT ?, ?";
if ($search) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $start_from, $limit);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $start_from, $limit);
}

// Execute the paginated query
$stmt->execute();
$currentData = $stmt->get_result();

// Calculate total pages
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/dashboard.css">
    <title>CRUD System</title>
</head>
<body>
    <div class="container">
        <h2>Pendaftar Anggota Klub Voli UIN</h2>
        <form method="GET" action="" class="search-form">
            <input type="text" name="search" placeholder="Cari anggota..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Cari</button>
            <button>
                <a href="index.php" class="btn-reset">Reset</a>
            </button>
            <button>
                <a href="logout.php" class="btn-logout">Logout</a>
            </button>
        </form>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Tim</th>
                        <th>Posisi</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($currentData->num_rows > 0) : ?>
                    <?php while ($user = $currentData->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['position']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td>
                                <a href="update.php?id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                                <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn-delete">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr><td colspan="6">Tidak ada data</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1&search=<?php echo urlencode($search); ?>" class="page-link">&#171;</a>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link prev">&#8592;</a>
            <?php endif; ?>

            <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                for ($i = $start_page; $i <= $end_page; $i++) {
                    echo "<a href='?page=$i&search=" . urlencode($search) . "' class='page-link " . ($i == $page ? 'active' : '') . "'>$i</a>";
                }
            ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link next">&#8594;</a>
                <a href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>" class="page-link">&#187;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
