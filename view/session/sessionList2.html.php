<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<?php
// Assume $client is the client object and $sessions is an array of session objects
$client = $clients;
?>
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded my-6 p-6">
            <div class="flex flex-col md:flex-row md:justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Prof Information</h1>
                    <p><strong>Name:</strong> <?= $client->prenom . " " . $client->nom ?></p>
                    <p><strong>Phone:</strong> <?= $client->telephone ?></p>
                </div>
                <div class="mt-4 md:mt-0">
                    <form method="post" action="">
                        <label for="filter" class="mr-2">Filter:</label>
                        <select id="filter" name="filter" class="border rounded p-2">
                            <option value="all">All Sessions</option>
                            <option value="upcoming">Upcoming Sessions</option>
                            <option value="past">Past Sessions</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply</button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border-collapse">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border">ID</th>
                            <th class="py-2 px-4 border">Heure Totale</th>
                            <th class="py-2 px-4 border">Course</th>
                            <th class="py-2 px-4 border">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Replace this with actual database query to fetch sessions

                        foreach ($courses as $course) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border'>{$course->id}</td>";
                            echo "<td class='py-2 px-4 border'>{$course->nbrheure}</td>";
                            echo "<td class='py-2 px-4 border'>{$course->libelle}</td>";
                            echo "<td class='py-2 px-4 border'><a href=''><button class='bg-gray-500 text-white px-4 py-2 rounded'>Details</button></a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6">
                <form action="" method="post">
                    <button name="page" value="<?= $prev ?? 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Previous</button>
                </form>
                <span>Page 1 of 10</span>
                <form action="" method="post">
                    <button name="page" value="<?= $suiv ?? 2 ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Next</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
