<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            background-color: #f8fafc;
            color: #1f2937;
            padding: 20px;
        }
        h2 {
            text-align: center;
            font-size: 20px;
            color: #4f46e5; /* Indigo-600 */
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #e0e7ff; /* Indigo-100 */
            color: #1e40af; /* Indigo-800 */
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #cbd5e1;
        }
        td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f1f5f9; /* Slate-100 */
        }
    </style>
</head>
<body>
    <h2>Liste des Techniciens</h2>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Service</th>
                <th>Date de création</th>
            </tr>
        </thead>
        <tbody>
            @foreach($techniciens as $tech)
                <tr>
                    <td>{{ $tech->user->nom ?? '' }}</td>
                    <td>{{ $tech->user->prenom ?? '' }}</td>
                    <td>{{ $tech->user->email ?? '' }}</td>
                    <td>{{ $tech->service->titre ?? '' }}</td>
                    <td>{{ optional($tech->user->created_at)->format('d/m/Y') ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
