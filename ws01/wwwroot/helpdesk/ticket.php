<?php
require __DIR__ . "/../includes/init.php";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/queue.php";
?>

<?php if (in_array("DL-TicketingRDP-Allow", $groups, true) || in_array("DL-HRTicketing-RW", $groups, true)): ?>
    <main class="container">
	<h3>Tickets</h3>
        <form method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Submit a ticket <strong style="color: oklch(0.65 0.2 25)">*</strong></legend>
                <textarea name="description" rows="10" cols="50"><?php echo $_POST['description'] ?? '' ?></textarea>
            </fieldset>
            <fieldset>
                <legend>Where should we assist you? <strong style="color: oklch(0.65 0.2 25)">*</strong></legend>
                <input type="radio" id="onsite" value="onsite" name="assist" checked required />
                <label for="onsite">On-site</label>
                <input type="radio" id="remote" value="remote" name="assist" required />
                <label for="remote">Remote</label>
            </fieldset>
            <fieldset>
                <legend>Attach a screenshot of the problem</legend>
                <input type="file" name="screenshotUpload" />
            </fieldset>

            <input type="submit" name="submit" value="Submit">

            <?php if (!empty($errors)): ?>
                <input type="text" value="<?php echo implode(" ", $errors); ?>" aria-invalid="true" readonly>
            <?php elseif ($confirmation): ?>
                <input type="text" value="<?php echo implode(" ", $confirmation); ?>" aria-invalid="false" readonly>
            <?php endif; ?>
        </form>
    </main>

<?php else: ?>

    <main class="container">
	    <!-- Fake input for PicoCSS error. Only for visual. Don't use in actual production sites. -->
        <input type="text" value="Sorry, you don't have access to this page."
            aria-invalid="true" readonly>
    </main>

<?php endif; ?>
<?php include __DIR__ . "/../includes/footer.php"; ?>