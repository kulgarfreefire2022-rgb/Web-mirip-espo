<?php
require 'db_config.php';

// Fitur Tambah Saldo (Logika)
if (isset($_POST['add_balance'])) {
    $user_id = $_POST['user_id'];
    $amount = (int)$_POST['amount'];
    
    mysqli_query($conn, "UPDATE users SET balance = balance + $amount WHERE id = $user_id");
    $msg = "Berhasil menambah saldo!";
}

// Fitur Ubah Status VIP
if (isset($_POST['toggle_vip'])) {
    $user_id = $_POST['user_id'];
    mysqli_query($conn, "UPDATE users SET is_vip = NOT is_vip WHERE id = $user_id");
}

// Ambil Data Semua User
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - AWIMEDAN STORE</title>
    <style>
        body { font-family: sans-serif; background: #1a1a1a; color: white; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #2d2d2d; }
        th, td { border: 1px solid #444; padding: 12px; text-align: left; }
        th { background: #3d3d3d; }
        input[type="number"] { width: 80px; padding: 5px; }
        button { cursor: pointer; padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 3px; }
        .btn-vip { background: #ffc107; color: black; }
        .msg { color: #00ff00; margin-bottom: 10px; }
    </style>
</head>
<body>

    <h2>🛠 Admin Panel AWIMEDAN STORE</h2>
    <?php if(isset($msg)) echo "<p class='msg'>$msg</p>"; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Saldo (IDR)</th>
                <th>Status VIP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td>Rp <?php echo number_format($row['balance']); ?></td>
                <td><?php echo $row['is_vip'] ? '✅ VIP' : '❌ Biasa'; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="amount" placeholder="Jumlah" required>
                        <button type="submit" name="add_balance">Tambah Saldo</button>
                    </form>

                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="toggle_vip" class="btn-vip">Ubah VIP</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>

