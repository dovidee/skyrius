# The site

Source code: [`ws01/wwwroot/`](ws01/wwwroot/)

I was inspired by HTB Flight for the Responder capture and HTB Tombwatcher for the recycle bin. The rest was my contribution. I chose to use `include` on the server image instead of the `?view=` parameter. I changed `svc_apache` to `svc_skyrius`. For `d.audra`, I made it so that you had to be logged in as `b.vytas` to send a ticket. I separated them into a general and restricted queue, and made it so that the remote assist type would send the ticket to `d.audra`. Without write access to the share and `getimagesize` reading the file header, you have to get past the filter via magic bytes (`GIF89a`) in order to send the ntlm_theft `.url` to `d.audra`. I built Skyrius in PHP and PicoCSS, and added an intentionally vulnerable blocklist and XSS in `description`.

## Skyrius Misconfig

Only misconfigurations that do not break the chain.

- XSS in `/helpdesk/ticket.php`
```
<textarea name="description" rows="10" cols="50"><?php echo $_POST['description'] ?? '' ?></textarea>
</textarea><script>alert('XSS')</script>
```

- Blocklist misconfiguration in `includes/queue.php`. Original allow list:
```
if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
) {
    $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
```
