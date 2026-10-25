<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Skyrius</title>
</head>

<body>
    <header class="container">
        <nav>
            <ul>
                <li><strong>Skyrius</strong></li>
            </ul>
            <ul>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><b><?php echo $_SESSION['user']; ?></b></li>
 			<?php if (!in_array("DL-SVCWeb-RW", $groups, true) 
				&& !in_array("DL-HelpDeskGeneral-RW", $groups, true)): ?>
			 <li><a href="//helpdesk.lab.local/lookup.php">BALA</a></li>
			<?php endif; ?>
 			<?php if (!in_array("DL-SVCWeb-RW", $groups, true)): ?>
			 <li><a href="//helpdesk.lab.local/ticket.php">Tickets</a></li>
			<?php endif; ?>
                    <li><a href="//skyrius.lab.local/logout.php" name="logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="//skyrius.lab.local/login.php">Login</a></li>
                <?php endif; ?>
                <li><a href="//skyrius.lab.local/index.php">Home</a></li>
            </ul>
        </nav>
    </header>