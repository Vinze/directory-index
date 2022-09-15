<?php
$baseUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/directory-index';
$path = $_SERVER['REQUEST_URI'];

$exclude = ['index.php', 'directory-index'];
$filelist = scandir('.');
$content = [];

natcasesort($filelist);
foreach ($filelist as $name) {
    if (in_array($name, $exclude) || substr($name, 0, 1) == '.') {
        continue;
    }
    $type = (is_dir($name) ? 'folder' : 'file');
    $sort = ($type == 'folder' ? '0-' : '1-').$name;
    $content[$sort] = ['name' => $name, 'type' => $type];
}

ksort($content);

$content = array_values($content);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Index of <?php echo $path ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="<?php echo $baseUrl ?>/stylesheet.min.css">
    <link rel="shortcut icon" href="<?php echo $baseUrl ?>/favicon.svg" type="image/x-icon">
</head>
<body class="min-h-screen py-4 px-2 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
    <div x-data="filelist" class="container mx-auto max-w-2xl p-4 pt-3 bg-white shadow dark:bg-gray-700 xl:max-w-4xl">
        <h1 class="mb-2 text-2xl font-bold">Index of <?php echo $path ?></h1>
        <input type="text" class="block w-full mb-2 py-2 px-3 border-2 border-gray-200" x-model="search" placeholder="Filter list" autofocus>
        <div class="md:columns-2 xl:columns-3">
            <template x-for="file in filteredItems">
                <a x-bind:href="file.name" class="block py-1 hover:opacity-75">
                    <svg xmlns="http://www.w3.org/2000/svg" x-show="file.type == 'folder'" class="inline-block h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" x-show="file.type == 'file'" class="inline-block h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                    <span x-text="file.name"></span>
                </a>
            </template>
        </div>
    </div>

    <script src="<?php echo $baseUrl ?>/alpine.min.js" defer></script>
    <script>
        window.addEventListener('alpine:init', () => {
            Alpine.data('filelist', () => ({
                content: <?php echo json_encode($content); ?>,
                search: '',

                get filteredItems() {
                    if ( ! this.search) {
                        return this.content;
                    }
                    return this.content.filter(item => {
                        return item.name.indexOf(this.search) !== -1;
                    });
                }
            }));
        })
    </script>
</body>
</html>
