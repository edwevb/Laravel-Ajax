@extends('layouts.master')
@section('title', 'Gunadarma Precision')
@section('container')
<div id="render">
	<div class="container">
		<h1>Bridge Gunadarma Precision Bidding System</h1>
	</div>
</div>
@push('custom-scripts')
<script>
	$(document).ready(function(){
		const link = $('.spa_route'),
		render = $('#render');

		link.on('click', function(e){
			e.preventDefault();
			let url = $(this).attr('href');

			$.get(url, function(data){
				render.html(data)
			});
		});
	});
</script>
@endpush
@endsection