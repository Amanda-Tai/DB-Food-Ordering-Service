<!DOCTYPE html>
<html>
<body>
<script>
alert("Log out success!");
window.location.replace("index.html");
</script>

<?php
session_start();
session_destroy();
?>

</body>
</html>