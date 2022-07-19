<?php
$exclude = ['index.php', 'adminer', 'phpmyadmin', 'sqlbuddy'];
$filelist = scandir('.');
$content = ['folders' => [], 'files' => []];

natcasesort($filelist);
foreach ($filelist as $item) {
    if (in_array($item, $exclude) || substr($item, 0, 1) == '.') {
        continue;
    }
    $type = (is_dir($item) ? 'folder' : 'file');
    $content[$type][] = $item;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Index of <?= $_SERVER['REQUEST_URI'] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="http://localhost/directory-index/stylesheet.min.css">
</head>
<body>
    <div class="container mx-auto max-w-2xl py-6 px-2">
        <h1 class="mb-2 text-xl font-bold">Index of <?= $_SERVER['REQUEST_URI'] ?></h1>
        <div class="md:columns-2">
            <?php foreach($content as $type => $files): ?>
                <?php foreach($files as $file): ?>
                    <a href="<?php echo $file; ?>" class="block py-1 hover:text-blue-600">
                        <?php if ($type == 'folder'): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                            </svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                        <?php endif ?>
                        <?php echo $file; ?>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>



