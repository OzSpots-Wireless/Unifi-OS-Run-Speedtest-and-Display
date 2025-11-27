<?php
#display.php
header('Content-Type: text/html; charset=utf-8');
$file = 'speedtest.txt';
$data = '';
$download = $upload = $ping = 'N/A';
$last_run = 'Never';
if (file_exists($file)) {
    $data = file_get_contents($file);
    $last_run = date('Y-m-d H:i:s', filemtime($file));
    
    // Parse (case-insensitive, robust)
    if (preg_match('/Download:\s*([\d.]+)\s*Mbit\/s/i', $data, $m)) $download = $m[1] . ' Mbit/s';
    if (preg_match('/Upload:\s*([\d.]+)\s*Mbit\/s/i', $data, $m)) $upload = $m[1] . ' Mbit/s';
    if (preg_match('/Hosted by.*?: (\d+(?:\.\d+)?)\s*ms/i', $data, $m)) $ping = $m[1] . ' ms';
    elseif (preg_match('/(\d+(?:\.\d+)?)\s*ms/i', $data, $m)) $ping = $m[1] . ' ms'; // Fallback
}

// After the existing parsing block (after the elseif for ping), add:
$status = '';
if (isset($_POST['run_test'])) {
    shell_exec('bash ./ssh.sh');
    $status = '✅ Speedtest completed!';
    // Refresh data after run
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $last_run = date('Y-m-d H:i:s', filemtime($file));
        // Re-parse
        if (preg_match('/Download:\s*([\d.]+)\s*Mbit\/s/i', $data, $m)) $download = $m[1] . ' Mbit/s';
        if (preg_match('/Upload:\s*([\d.]+)\s*Mbit\/s/i', $data, $m)) $upload = $m[1] . ' Mbit/s';
        if (preg_match('/Hosted by.*?: (\d+(?:\.\d+)?)\s*ms/i', $data, $m)) $ping = $m[1] . ' ms';
        elseif (preg_match('/(\d+(?:\.\d+)?)\s*ms/i', $data, $m)) $ping = $m[1] . ' ms';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>&#128640; Speedtest Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; text-align: center; }
        .container { max-width: 800px; margin: 0 auto; }
        .stats { display: flex; justify-content: center; gap: 20px; margin: 40px 0; flex-wrap: wrap; }
        .stat { background: rgba(255,255,255,0.2); padding: 30px; border-radius: 20px; backdrop-filter: blur(10px); min-width: 150px; }
        .value { font-size: 2.5em; font-weight: bold; margin: 10px 0; }
        .label { font-size: 1.1em; opacity: 0.9; }
        pre { background: rgba(0,0,0,0.3); padding: 20px; border-radius: 10px; text-align: left; max-height: 400px; overflow: auto; font-size: 0.9em; }
        .footer { margin-top: 30px; opacity: 0.8; }
        @media (max-width: 600px) { .stats { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>&#128640; Speedtest Results</h1>
        
<!-- After <h1>&#128640; Speedtest Results</h1>, add: -->
<?php if ($status): ?>
<div style="background: rgba(16,185,129,0.3); padding: 15px; border-radius: 10px; margin: 20px 0; font-size: 1.2em;">
    <?php echo $status; ?>
</div>
<?php endif; ?>

<!-- Before <div class="stats">, add: -->
<form method="post" style="margin: 20px 0;">
    <input type="submit" name="run_test" value="🚀 Run Speedtest Now" 
           style="padding: 15px 40px; font-size: 1.3em; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 50px; cursor: pointer; box-shadow: 0 4px 15px rgba(16,185,129,0.4); transition: transform 0.2s;">
</form>



        <div class="stats">
            <div class="stat">
                <div class="label">&#128225; Download</div>
                <div class="value" style="color: #4ade80;"><?php echo $download; ?></div>
            </div>
            <div class="stat">
                <div class="label">&#128226; Upload</div>
                <div class="value" style="color: #3b82f6;"><?php echo $upload; ?></div>
            </div>
            <div class="stat">
                <div class="label">&#9889; Ping</div>
                <div class="value" style="color: #f59e0b;"><?php echo $ping; ?></div>
            </div>
        </div>
        
        <pre><?php echo htmlspecialchars($data); ?></pre>
        
        <div class="footer">
<!-- Optional: Update footer link if desired (replace existing): -->
<div class="footer">
    Last run: <?php echo $last_run; ?> | <a href="speedtest.php" style="color: #fbbf24;">Raw</a> | <a href="display.php" style="color: #fbbf24;">Refresh</a>
</div>        </div>
    </div>
</body>
</html>
