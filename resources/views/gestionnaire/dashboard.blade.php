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
        gap: 20px;
    }

    .head-title .left h1 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .breadcrumb {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
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

    .btn-download {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .btn-download i {
        margin-right: 8px;
    }

    .btn-download:hover {
        background-color: #0056b3;
    }

    /* Boîtes de statistiques */
    .box-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        list-style: none;
        padding: 0;
    }

    .box-info li {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .box-info li i {
        font-size: 32px;
    }

    .box-info li .text h3 {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .box-info li .text p {
        font-size: 14px;
        color: #555;
        margin: 0;
    }

    h4 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    /* Section des transactions */
    .table-data {
        margin-bottom: 20px;
    }

    .order {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .order .head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .order .head h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .order .head i.bx {
        font-size: 24px;
        color: #666;
        cursor: pointer;
        margin-left: 10px;
        transition: color 0.3s;
    }

    .order .head i.bx:hover {
        color: #007bff;
    }

    .order table {
        width: 100%;
        border-collapse: collapse;
    }

    .order table th,
    .order table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }

    .order table th {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        background-color: #f8f9fa;
    }

    .order table td {
        font-size: 14px;
        color: #555;
    }

    .order table tr:hover {
        background-color: #f1f3f5;
    }

    .no-transactions {
        font-size: 14px;
        color: #999;
        text-align: center;
        padding: 20px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .head-title {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .box-info {
            grid-template-columns: 1fr;
        }

        .order table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tableau de bord</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Tableau de bord</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">Accueil</a>
                </li>
            </ul>
        </div>
        {{-- <a href="#" class="btn-download">
            <i class='bx bxs-cloud-download'></i>
            <span class="text">Télécharger PDF</span>
        </a> --}}
        {{-- <a href="{{ route('manager.dashboard.pdf') }}" class="btn-download">
    <i class='bx bxs-cloud-download'></i>
    <span class="text">Télécharger PDF</span>
</a> --}}
    </div>
    <ul class="box-info">
        <li>
            <i class='bx bx-transfer' style='color: #3b82f6'></i>
            <span class="text">
                <h3>{{ $transactionStats['transfere']['count'] }}</h3>
                <p>Transferts</p>
            </span>
        </li>
        <li>
            <i class='bx bx-download' style='color: #10b981'></i>
            <span class="text">
                <h3>{{ $transactionStats['depot']['count'] }}</h3>
                <p>Dépôts</p>
            </span>
        </li>
        <li>
            <i class='bx bx-upload' style='color: #ef4444'></i>
            <span class="text">
                <h3>{{ $transactionStats['retrait']['count'] }}</h3>
                <p>Retraits</p>
            </span>
        </li>
    </ul>
    <div>
        <h4>Totaux des Montants</h4>
        <ul class="box-info">
            <li>
                <i class='bx bx-transfer' style='color: #3b82f6'></i>
                <span class="text">
                    <h3>{{ number_format($transactionStats['transfere']['total_amount'], 2) }} FCFA</h3>
                    <p>Total Transferts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-download' style='color: #10b981'></i>
                <span class="text">
                    <h3>{{ number_format($transactionStats['depot']['total_amount'], 2) }} FCFA</h3>
                    <p>Total Dépôts</p>
                </span>
            </li>
            <li>
                <i class='bx bx-upload' style='color: #ef4444'></i>
                <span class="text">
                    <h3>{{ number_format($transactionStats['retrait']['total_amount'], 2) }} FCFA</h3>
                    <p>Total Retraits</p>
                </span>
            </li>
        </ul>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Dernières Transactions</h3>
            </div>
            <table>
                <thead>
                    <tr>
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
                            <td colspan="5" class="no-transactions">Aucune transaction récente.</td>
                        </tr>
                    @else
                        @foreach($latestTransactions as $transaction)
                            <tr>
                                <td>{{ ucfirst($transaction->type) }}</td>
                                <td>{{ $transaction->montant }}</td>
                                <td>{{ $transaction->expediteur ?? 'N/A' }}</td>
                                <td>{{ $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    {{ $transaction->reference ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>
<!-- MAIN -->

@endsection
