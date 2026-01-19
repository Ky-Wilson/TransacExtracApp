<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Dashboard Orange Money</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 11pt;
            line-height: 1.4;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #FF6600;
        }
        
        .header h1 {
            color: #FF6600;
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header .date {
            color: #666;
            font-size: 9pt;
        }
        
        /* Section Title */
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #333;
            margin: 15px 0 10px 0;
            padding-left: 10px;
            border-left: 4px solid #FF6600;
        }
        
        /* KPI Grid */
        .kpi-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-spacing: 12px 0;
        }
        
        .kpi-row {
            display: table-row;
        }
        
        .kpi-card {
            display: table-cell;
            width: 33.333%;
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #FF6600;
            vertical-align: top;
        }
        
        .kpi-card.transfert { border-left-color: #3B82F6; }
        .kpi-card.depot { border-left-color: #10B981; }
        .kpi-card.retrait { border-left-color: #EF4444; }
        
        .kpi-header {
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .kpi-value {
            font-size: 20pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .kpi-label {
            font-size: 8pt;
            color: #999;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        
        thead {
            background: #FF6600;
            color: white;
        }
        
        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9pt;
        }
        
        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .badge.transfere {
            background: #DBEAFE;
            color: #1E40AF;
        }
        
        .badge.depot {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .badge.retrait {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-muted {
            color: #999;
        }
        
        /* Footer */
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ORANGE MONEY - DASHBOARD</h1>
        <div class="date">Rapport genere le {{ now()->format('d/m/Y a H:i') }}</div>
    </div>

    <!-- KPI Section - Nombre de transactions -->
    <div class="section-title">NOMBRE DE TRANSACTIONS</div>
    <div class="kpi-grid">
        <div class="kpi-row">
            <div class="kpi-card transfert">
                <div class="kpi-header">Transferts</div>
                <div class="kpi-value">{{ $transactionStats['transfere']['count'] }}</div>
                <div class="kpi-label">transactions</div>
            </div>
            <div class="kpi-card depot">
                <div class="kpi-header">Depots</div>
                <div class="kpi-value">{{ $transactionStats['depot']['count'] }}</div>
                <div class="kpi-label">transactions</div>
            </div>
            <div class="kpi-card retrait">
                <div class="kpi-header">Retraits</div>
                <div class="kpi-value">{{ $transactionStats['retrait']['count'] }}</div>
                <div class="kpi-label">transactions</div>
            </div>
        </div>
    </div>

    <!-- KPI Section - Montants totaux -->
    <div class="section-title">MONTANTS TOTAUX</div>
    <div class="kpi-grid">
        <div class="kpi-row">
            <div class="kpi-card transfert">
                <div class="kpi-header">Total Transferts</div>
                <div class="kpi-value">{{ number_format($transactionStats['transfere']['total_amount'], 0, ',', ' ') }}</div>
                <div class="kpi-label">FCFA</div>
            </div>
            <div class="kpi-card depot">
                <div class="kpi-header">Total Depots</div>
                <div class="kpi-value">{{ number_format($transactionStats['depot']['total_amount'], 0, ',', ' ') }}</div>
                <div class="kpi-label">FCFA</div>
            </div>
            <div class="kpi-card retrait">
                <div class="kpi-header">Total Retraits</div>
                <div class="kpi-value">{{ number_format($transactionStats['retrait']['total_amount'], 0, ',', ' ') }}</div>
                <div class="kpi-label">FCFA</div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="section-title">DERNIERES TRANSACTIONS</div>
    
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th class="text-right">Montant</th>
                <th>Expediteur</th>
                <th>Reference</th>
                <th class="text-right">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($latestTransactions as $t)
            <tr>
                <td>
                    <span class="badge {{ $t->type }}">
                        {{ ucfirst($t->type) }}
                    </span>
                </td>
                <td class="text-right"><strong>{{ $t->montant }}</strong></td>
                <td>{{ $t->expediteur ?? 'N/A' }}</td>
                <td class="text-muted">{{ $t->reference ?? 'N/A' }}</td>
                <td class="text-right">{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px;">
                    Aucune transaction disponible
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>