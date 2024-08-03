<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-3xl font-bold mb-6">Make a Payment</h1>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Client Information</h2>
            <p><strong>Name:</strong> <?=$client->nom." ".$client->prenom?></p>
            <p><strong>Email:</strong> <?=$client->mail?></p>
            <p><strong>Phone:</strong> <?=$client->telephone?></p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Debt Information</h2>
            <p><strong>Total Debt Amount:</strong> <?=$dette->montant?></p>
            <p><strong>Amount Paid:</strong> <?=$dette->montantverse?></p>
            <p><strong>Remaining Amount:</strong> <?=floatval($dette->montant)-floatval($dette->montantverse)?></p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="" method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <?php if ($error): ?>
                            <p class="text-red-500 text-sm"><?= $error ?></p>
                        <?php endif; ?>
                </div>
                <input type="hidden" name="ramount" value="<?=floatval($dette->montant)-floatval($dette->montantverse)?>">
                <input type="hidden" name="amountp" value="<?=floatval($dette->montantverse)?>">
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Pay
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
