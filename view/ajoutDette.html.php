
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-md">
        <h1 class="text-2xl font-bold mb-6">Nouvelle Dette</h1>

        <?php
        // Simuler la récupération des informations du client
        // Remplacez ceci par votre propre logique pour récupérer les données du client
        $client=$clients;
        ?>

        <!-- Vérifiez si les informations du client sont disponibles -->
        <?php if (!empty($client)): ?>
            <!-- Afficher les informations du client -->
            <div id="client-info" class="bg-white shadow-md rounded p-4 mb-4">
                <p><strong>Nom:</strong> <?= $client->nom ?></p>
                <p><strong>Prénom:</strong> <?= $client->prenom ?></p>
                <p><strong>Téléphone:</strong> <?= $client->telephone ?></p>
            </div>
        <?php else: ?>
            <!-- Afficher le formulaire de recherche de client si les informations ne sont pas disponibles -->
            <div class="mb-4">
                <label for="client-search" class="block text-sm font-medium text-gray-700">Rechercher Client</label>
                <div class="flex">
                    <form action="?add" method="post">
                        <input type="text" id="client-search" name="client_search" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Rechercher...">
                        <button  class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">+</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Recherche d'Articles -->
        <div class="mb-4">
            <label for="article-search" class="block text-sm font-medium text-gray-700">Rechercher Articles</label>
            <form action="" method="post">
                <div class="flex">
                    <input type="text" id="article-search" name="article_search" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?= $article->libelle??"" ?>" placeholder="Rechercher...">
                    <button  name="searchProd" type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Rechercher</button>
                </div>
            </form>
        </div>
        <div id="article-info">
            <!-- Les résultats de la recherche d'articles apparaîtront ici -->
        </div>

        <!-- Quantité et Prix Unitaire -->
         <form action="" method="post">
         <?php if ($article): ?>
        <div class="mb-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantité</label>
                    <input type="number" name="quantity" value=1 id="quantity" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Quantité">
                </div>

                <div>
                    <label for="unit-price" class="block text-sm font-medium text-gray-700">Prix Unitaire</label>
                    
                    <input type="number" name="pu" value= <?= $article->pu ?>  id="unit-price" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Prix Unitaire">
                    <input type="hidden" name="id" value=<?= $article->id ?> >
                    <input type="hidden" name="qtstock" value=<?= $article->qt_stock ?>>
                </div>
            </div>
        </div>

        <?php endif ?>
        <!-- Bouton Ajouter -->
        <div class="mb-4">

            <button  name="addToCart" id="addToCart" type="<?php if (!$article){ echo "button";} ?>"  value="<?= $article->libelle??''?>" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Ajouter au Panier</button>
        </div>
        </form>
        <!-- Panier -->
        <div class="mb-4">
    <h2 class="text-xl font-semibold mb-2">Panier</h2>
    <div id="cart" class="bg-gray-50 p-4 rounded-md shadow-inner">
        <?php if (!empty($articles)): ?>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b-2">Libellé</th>
                        <th class="py-2 px-4 border-b-2">Quantité</th>
                        <th class="py-2 px-4 border-b-2">Prix Unitaire</th>
                        <th class="py-2 px-4 border-b-2">Total</th>
                        <th class="py-2 px-4 border-b-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $index => $article): ?>
                        <tr class="text-center">
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($article->libelle); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($article->quantitevendu); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($article->pu); ?> €</td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($article->quantitevendu * $article->pu); ?> €</td>
                            <td class="py-2 px-4 border-b">
                                <form method="POST" action="delete_cart_item.php">
                                    <input type="hidden" name="index" value="<?= $index; ?>">
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500">Votre panier est vide.</p>
        <?php endif; ?>
    </div>
</div>


        <!-- Bouton Enregistrer Dette -->
        <div class="mt-6">
            <form action="/dette/register" method="post">
            <button id="registerDebt" type="<?php  if (empty($articles)){ echo "button";} ?>" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Enregistrer Dette</button>

            </form>
        </div>
    </div>
</body>
</html>
