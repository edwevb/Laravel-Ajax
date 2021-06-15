$('#btn-add').on('click', function(){
	$('#openModal').modal('show');
});

$('body').on('click', '#editButton', function(){
	var bidding_id = $(this).val();
	var url = "{{ url('/bidding') }}";
	$.get(url+'/'+bidding_id, function(){
		$('#bidding_id').val(bidding_id.id);
		$('#btn-save').val("update");
		$('#openModal').modal('show');
	});
});

$('#biddingAddForm').on('submit', function (e)
{
	e.preventDefault();
	var opening = $('#opening').val();
	var _token = $('meta[name="csrf-token"]').attr('content');
	var url = "{{ url('/bidding') }}";
	var data = {
		_token:_token,
		opening: opening
	}
	$.ajax({
		url:      url,
		type:     'POST',
		data:     data,
		dataType: 'json',
		beforeSend:function(){
			$('#btn-save').attr('disabled', true);
		},
		success: function (response)
		{
			if (response.error) {
				printErrorMessage(response.error);
			}else{
				$('#btn-save').attr('disabled', false);
				$('#alertSuccess').show();
				$('#alertSuccess').html(response.success);
				$('.invalid-feedback').hide();
				$('#opening').removeClass('is-invalid');
				document.getElementById('biddingAddForm').reset();
					//APPEND DATA TO HTML
					var totalIteration = '{{$iteration}}'; //Number of row
					var resultData = response.data;
					var bodyData = '<tr>';
					bodyData += '<td>'+totalIteration+'</td>'+'<td>'+resultData.opening+'</td>'+'<td>'+'</td>';
					$("#bodyData").append(bodyData);
				}

			},
			error:function(response){
				$('#alertSuccess').hide();
				$('#alertError').show();
				$('#alertError').html('Something went error, please try again!');
			}
		});

	function printErrorMessage(errMsg){
		$('#alertSuccess').hide();
			// $('#alertError').show();
			// $('#alertError').html(errMsg);
			$('#btn-save').attr('disabled', false);
			$('.invalid-feedback').show();
			$('.invalid-feedback').html(errMsg);
			$('#opening').addClass('is-invalid');
		}
	});