<?php
$oldData = $_POST ?? [];
if (isset($client)) {
    die();
    // echo $client;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Ajoutez ici tout CSS personnalisé pour ajuster les styles */
    </style>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-500">LOY DIEUND</h1>
            <div class="flex items-center">
                <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-full mr-4">
                <div class="text-right">
                    <p class="text-gray-800">Lamine DIALLO</p>
                    <p class="text-gray-600">diallolamine@gmail.com</p>
                </div>
                <a href="#" class="ml-4 text-red-500">Logout</a>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <!-- Formulaire Nouveau Client -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-blue-500 mb-4">NOUVEAU CLIENT</h2>
                <form action="<?= $_ENV["WEBROOT"] . 'client' ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="nom" class="block text-gray-700">Nom:</label>
                        <input type="text" id="nom" name="nom"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($oldData['nom'] ?? '') ?>">
                        <?php if (isset($errors['nom'])): ?>
                            <p class="text-red-500 text-sm"><?= $errors['nom'][0] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="prenom" class="block text-gray-700">Prenom:</label>
                        <input type="text" id="prenom" name="prenom"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($oldData['prenom'] ?? '') ?>">
                        <?php if (isset($errors['prenom'])): ?>
                            <p class="text-red-500 text-sm"><?= $errors['prenom'][0] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="mail" class="block text-gray-700">Email:</label>
                        <input type="mail" id="mail" name="mail"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($oldData['mail'] ?? '') ?>">
                        <?php if (isset($errors['mail'])): ?>
                            <p class="text-red-500 text-sm"><?= $errors['mail'][0] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="telephone" class="block text-gray-700">Telephone:</label>
                        <input type="text" id="telephone" name="telephone"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($oldData['telephone'] ?? '') ?>">
                        <?php if (isset($errors['telephone'])): ?>
                            <p class="text-red-500 text-sm"><?= $errors['telephone'][0] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label for="photo" class="block text-gray-700">Photo:</label>
                        <input type="file" id="filephoto" name="filephoto"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($oldData['photo'] ?? '') ?>">
                        <?php if (isset($errors['photo'])): ?>
                            <p class="text-red-500 text-sm"><?= $errors['photo'][0] ?></p>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="register" value="register"
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Enregistrer</button>
                </form>
            </div>

            <!-- Suivi Dette -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-blue-500 mb-4">SUIVI DETTE</h2>
                <div class="mb-4">
                    <form action="<?= $_ENV["WEBROOT"] . 'client' ?>" method="post">
                        <input type="text" name="telephone" placeholder="Search"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= htmlspecialchars($oldData['telephone'] ?? '') ?>">
                        <button name="searchClient"
                            class="w-full mt-2 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Search
                            by phone</button>
                    </form>
                </div>
                <div class="tabs flex space-x-4 mb-4">






                    <a href="/ajoutDette/<?= $datad[0]->telephone ?>"
                        class="<?= $datad[0]->telephone ? '' : 'hidden ' ?>">
                        <button class="rounded-lg text-white bg-blue-500 w-24" name="ajoutDette"
                            value="<?= $datad[0]->telephone ?>">Nouvelle</button>
                    </a>

                    <a href="/dette/list/<?= $datad[0]->telephone ?>"
                        class="<?= $datad[0]->telephone ? '' : 'hidden' ?>">
                        <button name="listDette" class="rounded-lg bg-blue-500 w-24 text-white tab"
                            value="<?= $datad[0]->telephone ?>">Dette</button>
                    </a>


                </div>
                <?php if (isset($datad)):
                    // var_dump($datad);
                    $client = $datad[0];

                    ?>
                    <div class="tab-content">

                        <div class="flex items-center mb-4">
                            <img src="<?= $client->photo ?>" alt="Client" class="rounded-full mr-4 w-24 h-24">
                            <div>
                                <p><strong>Nom:</strong> <?= $client->id ?></p>
                                <p><strong>Prénom:</strong> <?= $client->prenom ?></p>
                                <p><strong>Email:</strong> <?= $client->mail ?></p>
                                <p><strong>Téléphone:</strong> <?= $client->telephone ?></p>
                            </div>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <p><strong>Total Dette:</strong>
                                <?= $client->totalDette !== null ? $client->totalDette : "---" ?></p>
                            <p><strong>Montant Versé:</strong>
                                <?= $client->montantVerse !== null ? $client->montantVerse : "---" ?></p>
                            <p><strong>Montant Restant:</strong>
                                <?= $client->totalDette !== null && $client->montantVerse !== null ? $client->totalDette - $client->montantVerse : "---" ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <p>Aucun client trouvé avec ce numéro de téléphone.</p>
                    <?php endif; ?>
                </div>
            </div>


        </div>
    </div>
</body>

</html>