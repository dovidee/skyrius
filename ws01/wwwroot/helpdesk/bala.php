<?php
if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    header("Location: /lookup.php");
    exit;
}
require_once __DIR__ . "/../includes/ldapconn.php";
$ldapBind = @ldap_bind($ldapConn, "svc_bala@lab.local", getenv("HELPDESK_LDAP_PASS"));
$error = "";
$success = "";
$attributes = [
    "dn" => "Directory",
    "thumbnailphoto" => "Photo",
    "physicaldeliveryofficename" => "Office",
    "telephonenumber" => "Phone",
    "whencreated" => "Joined",
    "name" => "Full name",
    "samaccountname" => "Username",
    "mail" => "Email",
];
if (!$ldapBind) {
    $error = "BALA is unavailable right now.";
} elseif (
    isset($_POST["bala"])
    && (in_array("DL-HelpDeskRestricted-RW", $groups, true)
        || in_array("DL-HRTicketing-RW", $groups, true))
) {
    $account = $_POST["bala"];
    $userSearch = ldap_search(
        $ldapConn,
        "DC=lab,DC=local",
        "(&(objectClass=user)(userPrincipalName=$account))",
        array_keys($attributes)
    );
    $userEntries = ldap_get_entries($ldapConn, $userSearch);
    if ($userEntries["count"] > 1) {
        $success = $userEntries["count"] . " users found.";
    } elseif ($userEntries["count"] > 0) {
        $success = $userEntries["count"] . " user found.";
    } else {
        $error = "No users found";
    }
}
?>
<?php if (!in_array("DL-SVCWeb-RW", $groups, true) && !in_array("DL-HelpDeskGeneral-RW", $groups, true)): ?>
    <main class="container">
        <?php if ($error): ?>
            <!-- Fake input for PicoCSS error. Only for visual. Don't use in actual production sites. -->
            <input type="text" value="<?php echo $error ?>" aria-invalid="true" readonly>
        <?php elseif ($success): ?>
            <input type="text" value="<?php echo $success ?>" aria-invalid="false" readonly>
        <?php endif; ?>
    </main>
    <form action="" method="post">
        <input type="search" name="bala" placeholder="account@lab.local">
    </form>
<?php else: ?>
    <main class="container">
        <!-- Fake input for PicoCSS error. Only for visual. Don't use in actual production sites. -->
        <input type="text" value="Sorry, you don't have access to this page." aria-invalid="true" readonly>
    </main>
<?php endif; ?>
<?php
if (isset($userEntries)) {
    for ($i = 0; $i < $userEntries["count"]; $i++) {
        if (isset($userEntries[$i]['thumbnailphoto'][0])) {
            echo "<br>" . '<img src="data:image/jpeg;base64,'
                . base64_encode($userEntries[$i]["thumbnailphoto"][0])
                . '" style="width: 96px; height: auto;">';
        }
        if (isset($userEntries[$i]["whencreated"][0])) {
            $created = DateTime::createFromFormat("YmdHis.0\Z", $userEntries[$i]["whencreated"][0], new DateTimeZone("UTC"));
            if ($created) {
                echo "<br>" . "<b>Joined</b> " . $created->format("j/n/Y G:i:s");
            }
        }
        for ($j = 0; $j < $userEntries[$i]["count"]; $j++) {
            $attrName = $userEntries[$i][$j];
            if ($attrName == "thumbnailphoto")
                continue;
            if ($attrName == "whencreated")
                continue;
            for ($k = 0; $k < $userEntries[$i][$attrName]["count"]; $k++) {
                echo "<br>" . "<b>$attributes[$attrName]</b> " . $userEntries[$i][$attrName][$k];
            }
        }
    }
}
?>