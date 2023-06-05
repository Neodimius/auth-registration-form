<?php
require_once 'layout/header.php';
?>

    <h1>Login</h1>

    <form method="POST" action="/login">
        <div class="mb-4 row">
            <label for="email" class="col-sm-5 col-form-label">E-mail:</label>
            <div class="col-sm-7">
                <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= $_POST['email'] ?? '' ?>" required>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="password" class="col-sm-5 col-form-label">Password:</label>
            <div class="col-sm-7">
                <input type="password" id="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" value="<?= $_POST['password'] ?? '' ?>" required>
            </div>
        </div>
        <input type="hidden" name="csrfToken" value="<?= $csrfToken; ?>">


        <input type="submit" class="btn btn-success" value="LogIn">
    </form>

<?php
require_once 'layout/footer.php';
?>