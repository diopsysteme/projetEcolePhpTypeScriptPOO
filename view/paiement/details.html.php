<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-3xl font-bold mb-6">Payments List</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Payment ID</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paiement as $payment): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($payment->id) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($payment->montantverse) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($payment->created_at) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
