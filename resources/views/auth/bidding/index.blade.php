@extends('layouts.master')
@section('title', 'Data Bidding')
@section('container')
<div class="container">
	<h1>DATA BIDDING</h1>
	<!-- Button trigger modal -->
	<a href="javascript:void(0)" id="btn-add" class="btn btn-success mb-3">Add Data</a>
	<table id="biddingTable" class="table">
		<thead>
			<tr>
				<th>NO</th>
				<th>OPENING</th>
				<th>DESC</th>
			</tr>
		</thead>
		<tbody id="bodyData">
			<?php $iteration=1 ?>
			@foreach($data_bidding as $bidding)
			<tr class="biddingRow{{$bidding->id}}">
				<td id="iterasi">{{$iteration++}}</td>
				<td id="addOpeningID"><span id="opening{{$bidding->id}}">{{$bidding->opening}}</span></td>
				@forelse ($bidding->kasus as $kasus)
				<td>{{$kasus->description}}</td>
				@empty
				<td><em>No description yet!</em></td>
				@endforelse
				<td>
					<button type="button" id="editButton" class="btn btn-info" name="{{$bidding->opening}}" value="{{$bidding->id}}">Edit</button>
				</td>
				<td>
					<td>
						<button type="button" id="deleteButton" class="btn btn-danger" onclick="return confirm('are you sure?')" value="{{$bidding->id}}">Delete</button>
					</td>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<!-- Modal Create-->
<div class="modal fade" id="openModal" tabindex="-1" aria-labelledby="openModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="openModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="alertSuccess" class="alert alert-success" style="display:none"></div>
				{{-- <div id="alertError" class="alert alert-danger" style="display:none"></div> --}}
				<form id="biddingAddForm">
					<div class="mb-3">
						<label for="opening">Opening</label>
						<input type="text" class="form-control" id="opening" name="opening" value="">
						<div class="invalid-feedback"></div>
					</div>
					<button id="btn-save" class="btn btn-success" type="submit" value="add">SAVE</button>
					<input type="hidden" id="bidding_id" name="bidding_id" value="0">
				</form>
			</div>
		</div>
	</div>
</div>
@section('ajax')
<script>
	//Modal show
	$('#btn-add').on('click', function(){
		$('#openModal').modal('show');
	});

	//UPDATE GET VALUE
	$('body').on('click', '#editButton', function(){
		var bidding_id = $(this).val();
		var name = $(this).attr('name');
		var url = "{{ url('/bidding') }}";
		$.get(url+'/'+bidding_id, function(){
			$('#bidding_id').val(bidding_id);
			$('#opening').val(name);
			$('#btn-save').val("update");
			$('#openModal').modal('show');
		});
	});

	//CREATE or UPDATE
	$('#btn-save').on('click', function (e) {
		e.preventDefault();

		var state = jQuery('#btn-save').val();
		var opening = $('#opening').val();
		var _token = $('meta[name="csrf-token"]').attr('content');
		var bidding_id = $('#bidding_id').val();
		var url = "{{ url('/bidding') }}";
		var type = "POST"

		//CREATE OR UPDATE CONDITION
		if (state == 'update') {
			type = "PUT";
			url =  "{{ url('/bidding') }}"+'/'+bidding_id;
		}
		var data = {
			_token:_token,
			opening: opening
		}
		$.ajax({
			url: url,
			type: type,
			data: data,
			dataType: 'json',
			beforeSend:function(){
				$('#btn-save').attr('disabled', true);
			},
			success: function (response)
			{
				if (response.error) {
					// Call error function
					printErrorMessage(response.error);
				}else{
					//IF CREATE
					if (state == "add") {
						$('#btn-save').attr('disabled', false);
						$('#alertSuccess').fadeIn();
						$('#alertSuccess').html(response.success);
						$('.invalid-feedback').hide();
						$('#opening').removeClass('is-invalid');
						document.getElementById('biddingAddForm').reset();

						var totalIteration = '{{$iteration}}'; //Number of row
						var resultData = response.data;
						var bodyData = '<tr>';
						bodyData += '<td>'+totalIteration+'</td>'+'<td>'+resultData.opening+'</td>'+'<td>'+'</td>'+'</tr>';
						btnEdit = '<tr>'
						$("#bodyData").append(bodyData);
					}else{
						//IF UPDATE
						$('#btn-save').attr('disabled', false);
						$('#alertSuccess').fadeIn();
						$('#alertSuccess').html(response.success);
						$('.invalid-feedback').hide();
						$('#opening').removeClass('is-invalid');
						$("#opening"+bidding_id).replaceWith(response.data);
					}
				}

			},
			error:function(response){
				$('#alertSuccess').hide();
				$('#alertError').show();
				$('#alertError').html('Something went error, please try again!');
			}
		});

		//ERROR FUNCTION
		function printErrorMessage(errMsg){
			$('#alertSuccess').hide();
			$('#btn-save').attr('disabled', false);
			$('.invalid-feedback').show();
			$('.invalid-feedback').html(errMsg);
			$('#opening').addClass('is-invalid');
		}
	});

	//DELETE
	$('#deleteButton').on('click',function(){
		var _token = $('meta[name="csrf-token"]').attr('content');
		var bidding_id = $(this).val();
		var url = "{{ url('/bidding') }}"+'/'+bidding_id;
		var type = "DELETE";
		var data = {
			_token:_token,
		}
		console.log(bidding_id)
		$.ajax({
			type: type,
			url: url,
			data: data,
			dataType: 'json',
			success: function(data) {
				$('.biddingRow'+bidding_id).remove();
			}
		});
	});
</script>
@stop
@endsection