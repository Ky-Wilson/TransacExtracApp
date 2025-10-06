@extends('layouts.gestionnaire.master')
@section('content')


<!-- MAIN -->
<main>
	<div class="head-title">
		<div class="left">
			<h1>Dashboard</h1>
			<ul class="breadcrumb">
				<li>
					<a href="#">Dashboard</a>
				</li>
				<li><i class='bx bx-chevron-right'></i></li>
				<li>
					<a class="active" href="#">Home</a>
				</li>
			</ul>
		</div>
		<a href="#" class="btn-download">
			<i class='bx bxs-cloud-download'></i>
			<span class="text">Download PDF</span>
		</a>
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
					<h3>{{ $transactionStats['transfere']['total_amount'] }} FCFA</h3>
					<p>Total Transferts</p>
				</span>
			</li>
			<li>
				<i class='bx bx-download' style='color: #10b981'></i>
				<span class="text">
					<h3>{{ $transactionStats['depot']['total_amount'] }} FCFA</h3>
					<p>Total Dépôts</p>
				</span>
			</li>
			<li>
				<i class='bx bx-upload' style='color: #ef4444'></i>
				<span class="text">
					<h3>{{ $transactionStats['retrait']['total_amount'] }} FCFA</h3>
					<p>Total Retraits</p>
				</span>
			</li>
		</ul>
	</div>


	<div class="table-data">
		<div class="order">
			<div class="head">
				<h3>Recent Orders</h3>
				<i class='bx bx-search'></i>
				<i class='bx bx-filter'></i>
			</div>
			<table>
				<thead>
					<tr>
						<th>User</th>
						<th>Date Order</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<img src="img/people.png">
							<p>John Doe</p>
						</td>
						<td>01-10-2021</td>
						<td><span class="status completed">Completed</span></td>
					</tr>
					<tr>
						<td>
							<img src="img/people.png">
							<p>John Doe</p>
						</td>
						<td>01-10-2021</td>
						<td><span class="status pending">Pending</span></td>
					</tr>
					<tr>
						<td>
							<img src="img/people.png">
							<p>John Doe</p>
						</td>
						<td>01-10-2021</td>
						<td><span class="status process">Process</span></td>
					</tr>
					<tr>
						<td>
							<img src="img/people.png">
							<p>John Doe</p>
						</td>
						<td>01-10-2021</td>
						<td><span class="status pending">Pending</span></td>
					</tr>
					<tr>
						<td>
							<img src="img/people.png">
							<p>John Doe</p>
						</td>
						<td>01-10-2021</td>
						<td><span class="status completed">Completed</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="todo">
			<div class="head">
				<h3>Todos</h3>
				<i class='bx bx-plus'></i>
				<i class='bx bx-filter'></i>
			</div>
			<ul class="todo-list">
				<li class="completed">
					<p>Todo List</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
				<li class="completed">
					<p>Todo List</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
				<li class="not-completed">
					<p>Todo List</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
				<li class="completed">
					<p>Todo List</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
				<li class="not-completed">
					<p>Todo List</p>
					<i class='bx bx-dots-vertical-rounded'></i>
				</li>
			</ul>
		</div>
	</div>
</main>
<!-- MAIN -->

@endsection