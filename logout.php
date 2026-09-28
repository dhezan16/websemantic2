```php
<?php

session_start();

// Hapus seluruh data session
$_SESSION = [];

// Hapus session dari server
session_destroy();

// Kembali ke halaman login
header("Location: index.php");
exit();

?>
```
