<?php
require __DIR__ . "/../includes/init.php";
include __DIR__ . "/../includes/header.php";
?>

<?php if (in_array("DL-HelpDeskRestricted-RW", $groups, true) || in_array("DL-HRTicketing-RW", $groups, true)): ?>
    <main class="container">
        <hgroup>
            <h3>BALA</h3>
            <p>Base Active Lookup Agent</p>
        </hgroup>
        <?php include __DIR__ . "/bala.php"; ?>
    </main>
<?php else: ?>
    <main class="container">
        <!-- Fake input for PicoCSS error. Only for visual. Don't use in actual production sites. -->
        <input type="text" value="Sorry, you don't have access to this page." aria-invalid="true" readonly>
    </main>
<?php endif; ?>

<?php include __DIR__ . "/../includes/footer.php"; ?>