<?php
if (isset($_POST['output'])) {
    $output = stripslashes($_POST['output']);
    file_put_contents('speedtest.txt', $output);
}
?>
<!DOCTYPE html><html><head><title>Speedtest</title></head><body>
<pre><?php
if (file_exists('speedtest.txt')) {
    echo htmlspecialchars(file_get_contents('speedtest.txt'));
} else {
    echo 'No data yet.';
}
?></pre>
</body></html>
