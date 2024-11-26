<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="update.css">
    <title>Update Pendaftar</title>
</head>
<body>
    <?php
        $conn = new mysqli("localhost", "root", "", "voli_db");
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM pendaftar WHERE id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row['name'];
                $email = $row['email'];
                $phone = $row['phone'];
                $gender = $row['gender'];
                $date_of_birth = $row['date_of_birth'];
                $position = $row['position'];  // Ensure you include position here
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $gender = $_POST['gender'];
            $date_of_birth = $_POST['date_of_birth'];
            $position = $_POST['position'];  // Include position update here

            // Update query adjusted to exclude address column
            $sql = "UPDATE pendaftar SET 
                        name='$name', 
                        email='$email', 
                        phone='$phone', 
                        gender='$gender', 
                        date_of_birth='$date_of_birth', 
                        position='$position'  // Update the position field
                    WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }

        $conn->close();
    ?>

    <div class="container">
        <div class="form-container">
            <h2>Update Data Pendaftar</h2>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <label for="name">Nama:</label>
                <input type="text" name="name" value="<?php echo $name; ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required><br>

                <label for="phone">Telepon:</label>
                <input type="text" name="phone" value="<?php echo $phone; ?>" required><br>

                <label for="position">Posisi di Tim:</label>
                <select name="position" required>
                    <option value="Setter" <?php echo ($position == 'Setter') ? 'selected' : ''; ?>>Setter</option>
                    <option value="Libero" <?php echo ($position == 'Libero') ? 'selected' : ''; ?>>Libero</option>
                    <option value="Outside hitter" <?php echo ($position == 'Outside hitter') ? 'selected' : ''; ?>>Outside hitter</option>
                    <option value="Opposite hitter" <?php echo ($position == 'Opposite hitter') ? 'selected' : ''; ?>>Opposite hitter</option>
                    <option value="Middle blocker" <?php echo ($position == 'Middle blocker') ? 'selected' : ''; ?>>Middle blocker</option>
                </select><br>

                <label for="gender">Jenis Kelamin:</label>
                <select name="gender" required>
                    <option value="Laki-laki" <?php echo ($gender == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($gender == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select><br>

                <label for="date_of_birth">Tanggal Lahir:</label>
                <input type="date" name="date_of_birth" value="<?php echo $date_of_birth; ?>" required><br>

                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
