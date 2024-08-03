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
                            <th class="py-2 px-4 border">Date</th>
                            <th class="py-2 px-4 border">Course</th>
                            <th class="py-2 px-4 border">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Replace this with actual database query to fetch sessions
                        $on="onclick=openModal('addProductModal')";
                        foreach ($absences as $absence) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border'>{$absence->id}</td>";
                            echo "<td class='py-2 px-4 border'>{$absence->date}</td>";
                            echo "<td class='py-2 px-4 border'>{$absence->heuredebut}</td>";
                            echo "<td class='py-2 px-4 border'>{$absence->libelle}</td>";
                            echo "<td class='py-2 px-4 border'><a ><button value='{$absence->id}' $on class='bg-gray-500 text-white px-4 py-2 rounded'>Details</button></a></td>";
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

    <div id="addProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden ">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
      <h2 class="text-2xl font-bold mb-4">Ajouter Produit</h2>
      <form id="productForm" action="/savejustif" method="post" enctype="multipart/form-data">

        <div id="step2" class="step ">
          <h3 class="text-xl font-bold mb-2">Informations sur l'expéditeur</h3>
          <div class="mb-4">
            <label for="motif" class="block text-gray-700">Motif</label>
            <input type="text" id="motif" name="motif" class=" mail w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div class="mb-4">
            <label for="file" class="block text-gray-700">Piece Jointe</label>
            <input type="file" id="file" name="justif" class=" numero w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div class="flex justify-between space-x-4">
            <button type="button" onclick="closeModal('addProductModal')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Cancel</button>
            <button type="submit" id="subjustif" name="session"  class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">justifier</button>
          </div>
        </div>
      </form>
     
    </div>
  </div>
<?php
    if($error){
?>

<div id="addProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center  ">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
      <h2 class="text-2xl font-bold mb-4">Ajouter Produit</h2>
      <form id="productForm" action="/savejustif" method="post" enctype="multipart/form-data">
      <small><?=$error["session"]?></small>
        <div id="step2" class="step ">
          <h3 class="text-xl font-bold mb-2">Informations sur l'expéditeur</h3>
          <div class="mb-4">
            <label for="motif" class="block text-gray-700">Motif</label>
            <input type="text" id="motif" name="motif" class=" mail w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <small><?=$error["motif"]?></small>
          </div>
          <div class="mb-4">
            <label for="file" class="block text-gray-700">Piece Jointe</label>
            <input type="file" id="file" name="justif" class=" numero w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <small><?=$error["justif"]?></small>
          </div>
          <div class="flex justify-between space-x-4">
            <button type="button" onclick="closeModal('addProductModal')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Cancel</button>
            <button type="submit" id="subjustif" name="session"  class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" value="<?=$error["id"]?>">justifier</button>
          </div>
        </div>
      </form>
     
    </div>
  </div>
<?php
    }
?>
  <script >
     function openModal(modalId) {
        console.log(event.target.value);
    document.getElementById(modalId).classList.remove('hidden');
   butsub= document.getElementById("subjustif")
    butsub.value=event.target.value;
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
  }
  </script>
</body>
</html>
