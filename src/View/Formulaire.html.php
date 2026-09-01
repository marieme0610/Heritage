<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrer une commande</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-md">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Enregistrer une commande
        </h1>

        <form method="POST" action="">

            <!-- Prix final -->
            <div class="mb-4">
                <label for="prix_final" class="block text-sm font-medium text-gray-700 mb-1">
                    Prix final
                </label>

                <input
                    type="number"
                    name="prix_final"
                    id="prix_final"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ex : 25000"
                >
            </div>

            <!-- Réduction appliquée -->
            <div class="mb-6">
                <label for="reduction_appliquee" class="block text-sm font-medium text-gray-700 mb-1">
                    Réduction appliquée
                </label>

                <select
                    name="reduction_appliquee"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">-- Sélectionner --</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition"
            >
                Enregistrer la commande
            </button>

        </form>

    </div>

</body>
</html>