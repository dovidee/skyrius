<?php
require_once "ldapconn.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginUPN = $_POST['email'] ?? "";
    $ldapPass = $_POST['password'] ?? "";

    if (empty(trim($ldapPass))) {
        $error = "Invalid username or password";
        return $error;
    }

    if ($ldapConn) {
        $ldapBind = @ldap_bind($ldapConn, $loginUPN, $ldapPass);
        if ($ldapBind) {
            $userSearch = @ldap_search($ldapConn, "DC=lab,DC=local", sprintf("(userPrincipalName=%s)", ldap_escape($loginUPN, "", LDAP_ESCAPE_FILTER)), ['dn']);
            $userEntries = $userSearch ? ldap_get_entries($ldapConn, $userSearch) : ["count" => 0];
            if ($userEntries["count"] !== 1) {
                $error = "Invalid username or password";
                return $error;
            }
            $userDN = $userEntries[0]['dn'];
            $domainLocalSearch = @ldap_search($ldapConn, "DC=lab,DC=local", sprintf("(&(groupType:%s:=4)(member:%s:=%s))", BIT_AND, IN_CHAIN, ldap_escape($userDN, "", LDAP_ESCAPE_FILTER)), ['dn']);
            $domainLocal = $domainLocalSearch ? ldap_get_entries($ldapConn, $domainLocalSearch) : ["count" => 0];
            $ldapGroups = array_map(fn($dn) => ldap_explode_dn($dn, 1)[0], array_column($domainLocal, 'dn'));
            session_regenerate_id(true);
            $_SESSION['user'] = $loginUPN;
            $_SESSION['groups'] = $ldapGroups;
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password";
            return $error;
        }
    }
}
?>