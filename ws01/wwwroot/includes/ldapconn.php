<?php
const BIT_AND = "1.2.840.113556.1.4.803";
const IN_CHAIN = "1.2.840.113556.1.4.1941";
$ldapConn = ldap_connect("ldap://lab.local");
if ($ldapConn) {
    ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, false);
}