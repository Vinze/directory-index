<?php
$baseUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/directory-index';
$path = $_SERVER['REQUEST_URI'];

$exclude = ['index.php', 'directory-index'];
$filelist = scandir('.');
$content = [];

foreach ($filelist as $name) {
    if (in_array($name, $exclude) || substr($name, 0, 1) == '.') {
        continue;
    }
    $type = (is_dir($name) ? 'folder' : 'file');
    $sort = ($type == 'folder' ? '0-' : '1-').$name;
    $content[$sort] = ['name' => $name, 'type' => $type];
}
ksort($content, SORT_NATURAL);
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
        <h1 class="mb-3 text-2xl font-bold">Index of <?php echo $path ?></h1>
        <div class="relative mb-2">
            <input type="text" x-model="search" x-on:keyup.enter="open" class="block w-full py-2 px-3 rounded border border-gray-300 bg-gray-50" placeholder="Filter list" autofocus>
            <button class="absolute top-0 right-0 py-1 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" x-show="search" x-on:click="search = ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="md:columns-2 xl:columns-3">
            <template x-for="file in filteredItems">
                <a x-bind:href="file.name" class="block py-1 hover:text-sky-600">
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
                open() {
                    if (this.filteredItems[0]) {
                        window.location.href = window.location.href + this.filteredItems[0].name;
                    }
                },
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
