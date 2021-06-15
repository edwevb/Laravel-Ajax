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
					<button class="btn btn-warning editButton" name="{{$bidding->opening}}" value="{{$bidding->id}}">EDIT</button>
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
<!-- Modal Create/Update-->
<div class="modal fade" id="openModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="biddingAddForm" name="biddingForm">
					<div class="mb-3">
						<label for="opening">Opening</label>
						<input type="text" class="form-control form-typing" id="opening" name="opening">
						<div class="invalid-feedback"></div>
					</div>
					<div class="row row-cols-2 row-cols-md-2 justify-content-center align-items-center">
						<div class="col">
							<button class="btn btn-success btn-save" type="submit" value="add" disabled>SAVE</button>
						</div>
						<div class="col">
							<div class="buffering text-muted" style="display: none;">
								<img height="15" src="{{ asset('/assets/img/load_buffering.gif') }}" alt="buffering"> Loading..
							</div>
							<div style="position: absolute!important;">
								<div id="alertSuccess" class="alert alert-success" style="display:none;position: relative;"></div>
								<div id="alertError" class="alert alert-danger" style="display:none;"></div>
							</div>
						</div>
					</div>
					<input type="hidden" id="bidding_id" name="bidding_id" value="0">
				</form>
			</div>
		</div>
	</div>
</div>
@section('ajax')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	//CREATE SET VALUE
	$('.btn-add').on('click', function(){

		$('.form-typing').on('keyup', function(){
			if ($(this).val() != null) {
				$(".btn-save").attr("disabled", false);
			}
			if ($(this).val() == '') {
				$(".btn-save").attr("disabled", true);
			}
		});

		$('.btn-save').val("add");
		$('#openModal').modal('show');
		$('#bidding_id').val('');
		$('#opening').val('');
		
		removeClassInvalidInput();
	});

	//UPDATE SET VALUE
	$(document).on('click', '.editButton', function(){
		let bidding_id = $(this).val();
		let opening = $(this).attr('name');
		let url = "{{ url('/bidding') }}";

		$.get(url+'/'+bidding_id, function(){
			$('.btn-save').val("update");
			$('#openModal').modal('show');
			$('#bidding_id').val(bidding_id);
			$('#opening').val(opening);
			
			$(".btn-save").prop("disabled", false);
			removeClassInvalidInput()
		});
	});

	//CREATE or UPDATE
	$('.btn-save').on('click', function(e){
		e.preventDefault();

		$('.buffering').show(); 
		const _token = $('meta[name="csrf-token"]').attr('content');
		var state = $('.btn-save').val();
		var opening = $('#opening').val();
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

		//BUFFERING
		setTimeout(function()
		{
			$('.buffering').hide(function(){
				//Callback Ajax
				doAjax();
			});
		},500/2);

		//AJAX CREATE or UPDATE
		function doAjax(){
			$.ajax({
				url: url,
				type: type,
				data: data,
				dataType: 'json',
				success: function (response)
				{
					if (response.error) {
					// Call error function
					errorMsg(response.error);
				}else{
						//IF CREATE
						if (state == "add") {
							$('#openModal').modal('hide');
							$('#openModal').modal('hide');
							Swal.fire({
								title:response.success,
								position: 'top-end',
								icon:'success',
								confirmButtonColor:'#38C172',
								iconColor:'#38C172',
								timer: 2000
							});
							removeClassInvalidInput();
							document.getElementById('biddingAddForm').reset();
							let totalIteration = '{{$iteration}}'; //Number of row
							let resultData = response.data;
							let bodyData = "<tr class='biddingRow"+resultData.id+"'>";
							let btnEdit = "<button class='btn btn-warning editButton' name='"+resultData.opening+"' value='"+resultData.id+"'>EDIT</button>";
							let btnDelete = "<button class='btn btn-danger deleteButton' value='"+resultData.id+"'>DELETE</button>";

							bodyData += "<td>"+totalIteration+"</td><td><span id='opening"+resultData.id+"'>"+resultData.opening+"</span></td><td><em>No description yet!<em></td><td class='text-center'>"+btnEdit+"</td><td class='text-center'>"+btnDelete+"</td></tr>";
							$(".bodyData").append(bodyData);

						}else{
							//IF UPDATE
							$('#openModal').modal('hide');
							Swal.fire({
								title:response.success,
								position: 'top-end',
								icon:'success',
								confirmButtonColor:'#38C172',
								iconColor:'#38C172',
								timer: 2000
							});
							removeClassInvalidInput();
							let resultData = response.data;
							let rowAfterUpdate = "<span id='opening"+resultData.id+"'>"+resultData.opening+"</span>"
							$('.btn-save').attr('disabled', false);
							//REPLACE OLD ROW with NEW ONE
							var txt = document.getElementById('opening');
							txt.value =  resultData.opening;
							$('.editButton').attr('name', resultData.opening);
							$("#opening"+bidding_id).replaceWith(rowAfterUpdate);
						}
					}
				},
				error:function(response){
					$('#alertError').show();
					$('#alertError').html('Something went error, please refresh & try again!');
				}
			});
		}

		//ERROR FUNCTION
		function errorMsg(errMsg){
			Swal.fire({
				title:'Something is wrong!',
				titleText:errMsg,
				icon:'error',
				confirmButton:'#E3342F',
				iconColor:'#E3342F'
			});
			$('.btn-save').attr('disabled', false);
			$('.invalid-feedback').show();
			$('.invalid-feedback').html(errMsg);
			$('#opening').addClass('is-invalid');
		}
	});

	//DELETE
	$(document).on('click','.deleteButton',function(){
		const _token = $('meta[name="csrf-token"]').attr('content');
		let bidding_id = $(this).val();
		let url = "{{ url('/bidding') }}"+'/'+bidding_id;
		let type = "DELETE";
		let data = {
			_token:_token
		}
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then(function(result){
			if (result.isConfirmed) {
				$.ajax({
					type: type,
					url: url,
					data: data,
					dataType: 'json',
					success: function(response) {
						Swal.fire({
							title:response.success,
							position: 'top-end',
							icon:'success',
							confirmButtonColor:'#38C172',
							iconColor:'#38C172',
							timer: 2000
						});
						$('.biddingRow'+bidding_id).remove();
					}
				});
			}
		});
		//ARROW FUNC
		// .then((result) => {

		// });
	});

	//CLEAR INVALID INPUT
	function removeClassInvalidInput(){
		$('.invalid-feedback').hide();
		$('#opening').removeClass('is-invalid');
	}
</script>
@stop
@endsection