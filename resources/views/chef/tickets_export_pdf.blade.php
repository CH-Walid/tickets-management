<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Export Tickets PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #2c3e50;
            background-color: #fff;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #34495e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background-color: #ecf0f1;
        }

        th, td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: #ffffff;
            font-weight: bold;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h2>Liste des Tickets</h2>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Assigné à</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Créé le</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                @php
                   $technicienName = ($ticket->technicien && $ticket->technicien->user)
        ? $ticket->technicien->user->nom . ' ' . $ticket->technicien->user->prenom
        : 'Non assigné';
                @endphp
                <tr>
                    <td>{{ $ticket->titre }}</td>
                    <td>{{ $technicienName }}</td>
                    <td>{{ ucfirst($ticket->priorite) }}</td>
                    <td>{{ ucfirst($ticket->status) }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
