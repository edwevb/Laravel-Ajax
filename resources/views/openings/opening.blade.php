@section('title', 'OPEN - '.$data->opening)
<div class="container">
	<h1>Opening {{$data->opening}}</h1>
	<hr>
	<div>
		<h5> CASE 1</h5>
		<div class="row row-cols-2 row-cols-md-2 align-items-center">
			<div class="col">
				Distribusi:
				<table class="table">
					<tr>
						<td></td>
						<td>TANGAN A</td>
						<td>TANGAN B</td>
					</tr>
					<tr>
						<td>S</td>
						<td>AQJxxx</td>
						<td>x</td>
					</tr>
					<tr>
						<td>H</td>
						<td>xx</td>
						<td>KQ</td>
					</tr>
					<tr>
						<td>D</td>
						<td>AKQ</td>
						<td>TANGAN B</td>
					</tr>
					<tr>
						<td>C</td>
						<td>AKQ</td>
						<td>TANGAN B</td>
					</tr>
				</table>
			</div>
			<div class="col">
				Proses Bidding:
				<p>1C-1D <br>1H-1S</p>
			</div>
		</div>
	</div>
</div>	