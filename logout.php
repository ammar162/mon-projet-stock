<?php
session_start();          // بدء الجلسة
session_unset();          // إزالة جميع متغيرات الجلسة
session_destroy();        // تدمير الجلسة
header('Location: login.php'); // إعادة التوجيه إلى صفحة تسجيل الدخول
exit;
?>
