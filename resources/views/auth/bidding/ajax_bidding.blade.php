@extends('layouts.master')
@section('title', 'Data Bidding')
@section('container')
<div class="container">
	<h1>DATA BIDDING</h1>
	<!-- Button trigger modal -->
	<a href="javascript:void(0)" class="btn btn-success btn-add mb-3">ADD DATA</a>
	<table id="biddingTable" class="table">
		<thead>
			<tr>
				<th>NO</th>
				<th>OPENING</th>
				<th>DESC</th>
				<th colspan="2" class="text-center">Action</th>
			</tr>
		</thead>
		<tbody class="bodyData">
			<?php $iteration=1 ?>
			@foreach($data_bidding as $bidding)
			<tr class="biddingRow{{$bidding->id}}">
				<td id="iterasi">{{$iteration++}}</td>
				<td><span id="opening{{$bidding->id}}">{{$bidding->opening}}</span></td>
				@forelse ($bidding->kasus as $kasus)
				<td>{{$kasus->description}}</td>
				@empty
				<td><em>No description yet!</em></td>
				@endforelse
				<td class="text-center">
					<button class="btn btn-info editButton" name="{{$bidding->opening}}" value="{{$bidding->id}}">EDIT</button>
				</td>
				<td class="text-center">
					<div class="deleteSection"> 
						<button class="btn btn-danger deleteButton" value="{{$bidding->id}}">DELETE</button>
					</div>
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
				<button class="close" data-dismiss="modal" aria-label="Close">
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
					<button class="btn btn-success btn-save" type="submit" value="add">SAVE</button>
					<input type="hidden" id="bidding_id" name="bidding_id" value="0">
				</form>
			</div>
		</div>
	</div>
</div>
@section('ajax')
<script>
	//CREATE SET VALUE
	$('.btn-add').on('click', function(){
		$('.btn-save').val("add");
		$('#openModal').modal('show');
		$('#bidding_id').val('');
		$('#opening').val('');
		$('.invalid-feedback').hide();
		$('#alertSuccess').hide();
		$('#alertError').hide();
		$('#alertError').html('');
		$('#opening').removeClass('is-invalid');
	});

	//UPDATE SET VALUE
	$('body').on('click', '.editButton', function(){
		var bidding_id = $(this).val();
		var opening = $(this).attr('name');
		var url = "{{ url('/bidding') }}";
		$.get(url+'/'+bidding_id, function(){
			$('#bidding_id').val(bidding_id);
			$('#opening').val(opening);
			$('.btn-save').val("update");
			$('#openModal').modal('show');
			$('.invalid-feedback').hide();
			$('#alertSuccess').hide();
			$('#alertError').hide();
			$('#alertError').html('');
			$('#opening').removeClass('is-invalid');
		});
	});

	//CREATE or UPDATE
	$('.btn-save').on('click', function(e){
		e.preventDefault();

		var state = $('.btn-save').val();
		var opening = $('#opening').val();
		var _token = $('meta[name="csrf-token"]').attr('content');
		var bidding_id = $('#bidding_id').val();
		var url = "";
		var type = "";

		//CREATE OR UPDATE CONDITION
		if (state == 'update') {
			type = "PUT";
			url =  "{{ url('/bidding') }}"+'/'+bidding_id;
		}else{
			url = "{{ url('/bidding') }}";
			type = "POST";
		}
		var data = {
			_token:_token,
			opening: opening
		};
		$.ajax({
			url: url,
			type: type,
			data: data,
			dataType: 'json',
			beforeSend:function(){
				$('.btn-save').attr('disabled', true);
			},
			success: function (response)
			{
				if (response.error) {
					// Call error function
					printErrorMessage(response.error);
				}else{
					//IF CREATE
					if (state == "add") {
						$('.btn-save').attr('disabled', false);
						$('#alertSuccess').fadeIn();
						$('#alertSuccess').html(response.success);
						$('.invalid-feedback').hide();
						$('#opening').removeClass('is-invalid');
						document.getElementById('biddingAddForm').reset();
						var totalIteration = '{{$iteration}}'; //Number of row
						var resultData = response.data;
						var bodyData = '<tr>';

						var btnEdit = "<button class='btn btn-info editButton' name='"+resultData.opening+"' value='"+resultData.id+"'>EDIT</button>";

						var btnDelete = "<button class='btn btn-danger deleteButton' value='"+resultData.id+"'>DELETE</button>";

						bodyData += "<td>"+totalIteration+"</td><td><span id='opening"+resultData.id+"'>"+resultData.opening+"</span></td><td><em>No description yet!<em></td><td class='text-center'>"+btnEdit+"</td><td class='text-center'>"+btnDelete+"</td></tr>";
						$(".bodyData").append(bodyData);

					}else{
						//IF UPDATE
						$('#alertSuccess').fadeIn();
						$('#alertSuccess').html(response.success);
						var resultData = response.data;
						var rowAfterUpdate = "<span id='opening"+resultData.id+"'>"+resultData.opening+"</span>"
						$('.btn-save').attr('disabled', false);
						
						$('.invalid-feedback').hide();
						$('#opening').removeClass('is-invalid');
						$("#opening"+bidding_id).replaceWith(rowAfterUpdate);
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
			$('.btn-save').attr('disabled', false);
			$('.invalid-feedback').show();
			$('.invalid-feedback').html(errMsg);
			$('#opening').addClass('is-invalid');
		}
	});

	//DELETE
	$(document).on('click','.deleteButton',function(){
		var _token = $('meta[name="csrf-token"]').attr('content');
		var bidding_id = $(this).val();
		var url = "{{ url('/bidding') }}"+'/'+bidding_id;
		var type = "DELETE";
		var data = {
			_token:_token,
			id : bidding_id
		}
		if (confirm('delete?'))
		{
			$.ajax({
				type: type,
				url: url,
				data: data,
				dataType: 'json',
				success: function(response) {
					console.log(response)
					$('.biddingRow'+bidding_id).remove();
				},
				error:function(response){
					$('#alertSuccess').hide();
					$('#alertError').show();
					$('#alertError').html('Something went error, please try again!');
				}
			});
		}else{
			return false;
		}
	});
</script>
@stop
@endsection