<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-3xl font-bold mb-6">Articles List</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Article ID</th>
                        <th class="py-2">Name</th>
                        <th class="py-2">Quantity</th>
                        <th class="py-2">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($article->id) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($article->libelle) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($article->quantitevendu) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($article->pu) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
