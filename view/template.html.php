<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex flex-col md:flex-row min-h-screen">
        <nav class="bg-indigo-600 text-white w-full md:w-64">
            <div class="p-4 text-xl font-bold">
                <a href="/" class="block text-white">Tableau de bord</a>
            </div>
            <ul>
                <li class="p-4 hover:bg-indigo-500">
                    <a href="/client" class="block">Liste des sessions</a>
                </li>
                <li class="p-4 hover:bg-indigo-500 ">
                <a href="/list/cours" class="block">Liste des Cours</a>
                </li>
                <li class="p-4 hover:bg-indigo-500 cursor-not-allowed">
                    <span class="block opacity-50">Ajouter une dette</span>
                </li>
                <li class="p-4 hover:bg-indigo-500 cursor-not-allowed">
                    <span class="block opacity-50">Liste des ?????</span>
                </li>
                <li class="p-4 hover:bg-indigo-500 cursor-not-allowed">
                    <span class="block opacity-50">Faire un ?????</span>
                </li>
                <li class="p-4 hover:bg-indigo-500 cursor-not-allowed">
                    <span class="block opacity-50">Détails des ????</span>
                </li>
            </ul>
            <div class="mt-auto p-4">
                <form action="/logout" method="post">
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Déconnexion</button>
                </form>
            </div>
        </nav>
        <main class="flex-1 p-6">
            <!-- Ici vous pouvez inclure le contenu dynamique de chaque page -->
            <?= $content ?>
        </main>
    </div>
    <script type="module" src="<?= $_ENV["WEBROOT"]; ?>dist/bundle.js" crossorigin="anonymous"></script>
</body>
</html>
