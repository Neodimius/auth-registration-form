<?php
require_once 'layout/header.php';

$errors = isset($user) ? $user->errors : [];

?>

    <h1>Registration</h1>

    <form method="POST" action="/registration">
        <input type="hidden" name="csrfToken" value="<?= $csrfToken; ?>">
        <div class="mb-4 row">
            <label for="firstName" class="col-sm-5 col-form-label">First Name:</label>
            <div class="col-sm-7">
                <input type="text" id="firstName" name="firstName" class="form-control <?= isset($errors['firstName']) ? 'is-invalid' : '' ?>" value="<?= $_POST['firstName'] ?? '' ?>" required>

                <?php if(isset($errors['firstName'])): ?>
                    <div class="invalid-feedback">
                        <?php foreach ($errors['firstName'] as $key => $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="lastName" class="col-sm-5 col-form-label">Last Name:</label>
            <div class="col-sm-7">
                <input type="text" id="lastName" name="lastName" class="form-control <?= isset($errors['lastName']) ? 'is-invalid' : '' ?>" value="<?= $_POST['lastName'] ?? '' ?>" required>

                <?php if(isset($errors['lastName'])): ?>
                    <div class="invalid-feedback">
                        <?php foreach ($errors['lastName'] as $key => $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="email" class="col-sm-5 col-form-label">E-mail:</label>
            <div class="col-sm-7">
                <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= $_POST['email'] ?? '' ?>" required>

                <?php if(isset($errors['email'])): ?>
                    <div class="invalid-feedback">
                        <?php foreach ($errors['email'] as $key => $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="phone" class="col-sm-5 col-form-label">Mobile Phone:</label>
            <div class="col-sm-7">
                <input type="text" id="phone" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" value="<?= $_POST['phone'] ?? '' ?>" required>

                <?php if(isset($errors['phone'])): ?>
                    <div class="invalid-feedback">
                        <?php foreach ($errors['phone'] as $key => $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="password" class="col-sm-5 col-form-label">Password:</label>
            <div class="col-sm-7">
                <input type="password" id="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" value="<?= $_POST['password'] ?? '' ?>" required>

                <?php if(isset($errors['password'])): ?>
                    <div class="invalid-feedback">
                        <?php foreach ($errors['password'] as $key => $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <input type="submit" class="btn btn-success" value="Register">
    </form>



<?php
require_once 'layout/footer.php';
?>