@extends('layouts.gestionnairev2.master')

@section('content')
<style>
    /* Conteneur principal */
    main {
        background-color: #f4f6f9;
        padding: 20px;
        min-height: 100vh;
    }

    /* En-tête */
    .head-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .head-title .left h1 {
        font-size: clamp(1.25rem, 4vw, 1.5rem);
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .breadcrumb {
        list-style: none;
        padding: 0;
        margin: 0.5rem 0 0;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .breadcrumb li a {
        font-size: 14px;
        color: #666;
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb li a.active {
        color: #007bff;
        font-weight: 600;
    }

    .breadcrumb li i.bx-chevron-right {
        font-size: 14px;
        color: #999;
    }

    /* Sections opérateurs */
    .operator-section {
        margin-bottom: 40px;
    }

    .operator-title {
        font-size: clamp(1.125rem, 3vw, 1.25rem);
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .operator-orange { color: #FF6B00; }
    .operator-mtn { color: #FFCC00; }

    .section-subtitle {
        font-size: clamp(1rem, 2.5vw, 1.125rem);
        font-weight: 600;
        color: #333;
        margin: 30px 0 15px;
    }

    /* Boîtes de statistiques */
    .box-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        list-style: none;
        padding: 0;
    }

    .box-info li {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .box-info li:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .box-info li i {
        font-size: clamp(2rem, 5vw, 2.25rem);
        padding: 12px;
        border-radius: 10px;
        flex-shrink: 0;
    }

    .box-info li .text {
        flex: 1;
        min-width: 0;
    }

    .box-info li .text h3 {
        font-size: clamp(1.25rem, 3vw, 1.375rem);
        font-weight: 700;
        margin: 0;
        word-break: break-word;
    }

    .box-info li .text p {
        font-size: 14px;
        color: #555;
        margin: 4px 0 0;
    }

    /* Dernières transactions */
    .table-data {
        margin-bottom: 30px;
    }

    .order {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .order .head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        flex-wrap: wrap;
        gap: 15px;
    }

    .order .head h3 {
        font-size: clamp(1rem, 2.5vw, 1.125rem);
        font-weight: 600;
        color: #fff;
        margin: 0;
    }

    .table-container {
        overflow-x: auto;
        padding: 20px;
        -webkit-overflow-scrolling: touch;
    }

    .order table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .order table th,
    .order table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
        white-space: normal;
        word-wrap: break-word;
    }

    .order table th {
        font-size: 14px;
        font-weight: 600;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .order table td {
        font-size: 14px;
        color: #555;
    }

    .order table tbody tr {
        transition: background-color 0.2s;
    }

    .order table tbody tr:hover {
        background-color: #f8fafc;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .no-transactions {
        font-size: 15px;
        color: #999;
        text-align: center;
        padding: 30px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .box-info {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .box-info {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        main {
            padding: 15px;
        }

        .box-info {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .box-info li {
            padding: 15px;
        }

        .operator-section {
            margin-bottom: 30px;
        }

        .section-subtitle {
            margin: 20px 0 10px;
        }

        .order .head {
            padding: 15px;
        }

        .table-container {
            padding: 10px;
        }

        .order table {
            min-width: 500px;
            font-size: 12px;
        }

        .order table th,
        .order table td {
            padding: 10px 8px;
            font-size: 12px;
        }

        .badge {
            font-size: 10px;
            padding: 3px 6px;
        }
    }

    @media (max-width: 576px) {
        main {
            padding: 10px;
        }

        .head-title {
            gap: 10px;
        }

        .box-info li i {
            font-size: 1.75rem;
            padding: 10px;
        }

        .table-container {
            padding: 5px;
            margin: 0 -5px;
        }

        .order table {
            min-width: 450px;
            font-size: 11px;
        }

        .order table th,
        .order table td {
            padding: 8px 6px;
            font-size: 11px;
        }

        .badge {
            font-size: 9px;
            padding: 2px 5px;
        }
    }

    @media (max-width: 400px) {
        .box-info li {
            padding: 12px;
            gap: 10px;
        }

        .order table {
            min-width: 400px;
        }

        .order table th,
        .order table td {
            padding: 6px 4px;
            font-size: 10px;
        }
    }
</style>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Tableau de bord Agent</h1>
            <ul class="breadcrumb">
                <li><a href="#">Tableau de bord</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Accueil</a></li>
            </ul>
        </div>
    </div>

    <!-- Orange Money -->
    <div class="operator-section">
        <div class="operator-title operator-orange">
            <i class='bx bx-mobile-alt'></i> Orange Money
        </div>
        <ul class="box-info">
            <li>
                <i class='bx bx-transfer' style='color: #FF6B00; background: rgba(255,107,0,0.1);'></i>
                <span class="text">
                    <h3>{{ $orangeStats['transfere']['count'] ?? 0 }}</h3>
                    <p>Transferts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-download' style='color: #10b981; background: rgba(16,185,129,0.1);'></i>
                <span class="text">
                    <h3>{{ $orangeStats['depot']['count'] ?? 0 }}</h3>
                    <p>Dépôts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-upload' style='color: #ef4444; background: rgba(239,68,68,0.1);'></i>
                <span class="text">
                    <h3>{{ $orangeStats['retrait']['count'] ?? 0 }}</h3>
                    <p>Retraits</p>
                </span>
            </li>
        </ul>

        <h4 class="section-subtitle">Totaux Montants Orange</h4>
        <ul class="box-info">
            <li>
                <i class='bx bx-transfer' style='color: #FF6B00; background: rgba(255,107,0,0.1);'></i>
                <span class="text">
                    <h3>{{ number_format($orangeStats['transfere']['total_amount'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <p>Total Transferts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-download' style='color: #10b981; background: rgba(16,185,129,0.1);'></i>
                <span class="text">
                    <h3>{{ number_format($orangeStats['depot']['total_amount'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <p>Total Dépôts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-upload' style='color: #ef4444; background: rgba(239,68,68,0.1);'></i>
                <span class="text">
                    <h3>{{ number_format($orangeStats['retrait']['total_amount'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <p>Total Retraits</p>
                </span>
            </li>
        </ul>
    </div>

    <!-- MTN MoMo -->
    <div class="operator-section">
        <div class="operator-title operator-mtn">
            <i class='bx bx-mobile'></i> MTN MoMo
        </div>
        <ul class="box-info">
            <li>
                <i class='bx bx-transfer' style='color: #FFCC00; background: rgba(255,204,0,0.1);'></i>
                <span class="text">
                    <h3>{{ $mtnStats['transfere']['count'] ?? 0 }}</h3>
                    <p>Transferts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-download' style='color: #00A859; background: rgba(0,168,89,0.1);'></i>
                <span class="text">
                    <h3>{{ $mtnStats['depot']['count'] ?? 0 }}</h3>
                    <p>Dépôts / Reçus</p>
                </span>
            </li>
            <li>
                <i class='bx bx-upload' style='color: #ef4444; background: rgba(239,68,68,0.1);'></i>
                <span class="text">
                    <h3>{{ $mtnStats['retrait']['count'] ?? 0 }}</h3>
                    <p>Retraits</p>
                </span>
            </li>
        </ul>

        <h4 class="section-subtitle">Totaux Montants MTN</h4>
        <ul class="box-info">
            <li>
                <i class='bx bx-transfer' style='color: #FFCC00; background: rgba(255,204,0,0.1);'></i>
                <span class="text">
                    <h3>{{ number_format($mtnStats['transfere']['total_amount'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <p>Total Transferts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-download' style='color: #00A859; background: rgba(0,168,89,0.1);'></i>
                <span class="text">
                    <h3>{{ number_format($mtnStats['depot']['total_amount'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <p>Total Dépôts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-upload' style='color: #ef4444; background: rgba(239,68,68,0.1);'></i>
                <span class="text">
                    <h3>{{ number_format($mtnStats['retrait']['total_amount'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <p>Total Retraits</p>
                </span>
            </li>
        </ul>
    </div>

    <!-- Dernières Transactions (combinées) -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Dernières Transactions (Orange & MTN)</h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Opérateur</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Expéditeur</th>
                            <th>Date</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($latestTransactions->isEmpty())
                            <tr>
                                <td colspan="6" class="no-transactions">Aucune transaction récente.</td>
                            </tr>
                        @else
                            @foreach($latestTransactions as $transaction)
                                <tr>
                                    <td>
                                        @if($transaction->operator == 'orange')
                                            <span class="badge" style="background: #FF6B00; color: white;">Orange</span>
                                        @else
                                            <span class="badge" style="background: #00A859; color: white;">MTN</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($transaction->type) }}</td>
                                    <td>{{ is_numeric($transaction->montant) ? number_format((float)$transaction->montant, 0, ',', ' ') : $transaction->montant }} FCFA</td>
                                    <td>{{ $transaction->expediteur ?? 'N/A' }}</td>
                                    <td>{{ $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $transaction->reference ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
