<?php
require "includes/init.php";
include "includes/ldaplogin.php";
include "includes/header.php";
?>

    <main class="container">
        <article>
            <h2>Login</h2>
            <form method="post">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" name="button" class="outline secondary">Submit</button>
                <?php if (!empty($error)): ?>
                    <!-- Fake input for PicoCSS error. Only for visual. Don't use in actual production sites. -->
                    <input type="text" value="<?php echo $error; ?>" aria-invalid="true" readonly>
                <?php endif; ?>

            </form>
        </article>
    </main>

<?php include "includes/footer.php"; ?>