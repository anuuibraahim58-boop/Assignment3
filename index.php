<?php
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $d = $_POST;

  
    foreach ($d as $key => $val) {
        if (trim($val) === '') {
            $errors[$key] = "Field is required.";
        }
    }

  
    if (!empty($d['name']) && !preg_match("/^[a-zA-Z\s]+$/", $d['name'])) {
        $errors['name'] = "Only letters and spaces allowed.";
    }

  
    if (!empty($d['email']) && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

   
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'gif'])) {
            $errors['image'] = "Only PNG, JPG, JPEG, GIF allowed.";
        } else {
            if (!is_dir("uploads")) {
                mkdir("uploads");
            }
            $d['image_path'] = "uploads/" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $d['image_path']);
        }
    } else {
        $errors['image'] = "Image is required.";
    }

    if (empty($errors)) {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light container py-5">

<?php if ($success): ?>
   
    <div class="card shadow p-4">
        <h2 class="text-success text-center mb-4">Submitted Information</h2>
        <?php if (!empty($d['image_path'])): ?>
            <div class="text-center mb-3">
                <img src="<?php echo htmlspecialchars($d['image_path']); ?>" width="120" class="rounded-circle">
            </div>
        <?php endif; ?>
        <table class="table table-bordered table-striped">
            <tr class="table-dark"><th colspan="2">Personal Information</th></tr>
            <tr><td>Name</td><td><?php echo htmlspecialchars($d['name']); ?></td></tr>
            <tr><td>POB</td><td><?php echo htmlspecialchars($d['pob']); ?></td></tr>
            <tr><td>DOB</td><td><?php echo htmlspecialchars($d['dob']); ?></td></tr>
            <tr><td>Gender</td><td><?php echo htmlspecialchars($d['gender']); ?></td></tr>
            <tr><td>Address</td><td><?php echo htmlspecialchars($d['address']); ?></td></tr>
            <tr><td>Telephone</td><td><?php echo htmlspecialchars($d['telephone']); ?></td></tr>
            <tr><td>Email</td><td><?php echo htmlspecialchars($d['email']); ?></td></tr>

            <tr class="table-dark"><th colspan="2">Parent Information</th></tr>
            <tr><td>Parent Name</td><td><?php echo htmlspecialchars($d['parent_name']); ?></td></tr>
            <tr><td>Telephone</td><td><?php echo htmlspecialchars($d['parent_telephone']); ?></td></tr>
            <tr><td>Relationship</td><td><?php echo htmlspecialchars($d['relationship']); ?></td></tr>

            <tr class="table-dark"><th colspan="2">Academic Information</th></tr>
            <tr><td>Student ID</td><td><?php echo htmlspecialchars($d['student_id']); ?></td></tr>
            <tr><td>Faculty</td><td><?php echo htmlspecialchars($d['faculty']); ?></td></tr>
            <tr><td>Department</td><td><?php echo htmlspecialchars($d['department']); ?></td></tr>
            <tr><td>Class</td><td><?php echo htmlspecialchars($d['class']); ?></td></tr>
            <tr><td>Reg Date</td><td><?php echo htmlspecialchars($d['reg_date']); ?></td></tr>
        </table>
        <a href="index.php" class="btn btn-primary mt-3">Back to Form</a>
    </div>

<?php else: ?>
   
    <div class="card shadow p-4">
        <h2 class="text-primary mb-4">Student Registration</h2>
        <form method="POST" enctype="multipart/form-data">
            
            <h4>Personal Info</h4>
            <input class="form-control mb-2" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <small class="text-danger"><?php echo $errors['name'] ?? ''; ?></small>

            <input class="form-control mb-2" name="pob" placeholder="Place of Birth" value="<?php echo htmlspecialchars($_POST['pob'] ?? ''); ?>">
            <input type="date" class="form-control mb-2" name="dob" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>">
            <select class="form-control mb-2" name="gender">
                <option value="">Select Gender</option>
                <option <?php echo (($_POST['gender'] ?? '') == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option <?php echo (($_POST['gender'] ?? '') == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
            <textarea class="form-control mb-2" name="address" placeholder="Address"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
            <input class="form-control mb-2" name="telephone" placeholder="Telephone" value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>">
            <input class="form-control mb-2" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <small class="text-danger"><?php echo $errors['email'] ?? ''; ?></small>
            
            <label>Student Image:</label>
            <input type="file" class="form-control mb-3" name="image">
            <small class="text-danger"><?php echo $errors['image'] ?? ''; ?></small>

            <h4>Parent Info</h4>
            <input class="form-control mb-2" name="parent_name" placeholder="Parent Name" value="<?php echo htmlspecialchars($_POST['parent_name'] ?? ''); ?>">
            <input class="form-control mb-2" name="parent_telephone" placeholder="Parent Telephone" value="<?php echo htmlspecialchars($_POST['parent_telephone'] ?? ''); ?>">
            <input class="form-control mb-3" name="relationship" placeholder="Relationship (e.g. Father)" value="<?php echo htmlspecialchars($_POST['relationship'] ?? ''); ?>">

            <h4>Academic Info</h4>
            <input class="form-control mb-2" name="student_id" placeholder="Student ID" value="<?php echo htmlspecialchars($_POST['student_id'] ?? ''); ?>">
            <input class="form-control mb-2" name="faculty" placeholder="Faculty" value="<?php echo htmlspecialchars($_POST['faculty'] ?? ''); ?>">
            <input class="form-control mb-2" name="department" placeholder="Department" value="<?php echo htmlspecialchars($_POST['department'] ?? ''); ?>">
            <input class="form-control mb-2" name="class" placeholder="Class" value="<?php echo htmlspecialchars($_POST['class'] ?? ''); ?>">
            <input type="date" class="form-control mb-3" name="reg_date" value="<?php echo htmlspecialchars($_POST['reg_date'] ?? ''); ?>">

            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>