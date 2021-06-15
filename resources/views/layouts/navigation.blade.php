<header>
	<div class="bg-danger">
		<div class="container text-center text-white py-3">
			<h5>Practice makes you better!</h5>
			<a href="{{ url('/bidding') }}">DATA BIDDING</a>
		</div>
	</div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-5">
		<div class="mx-auto">
			<ul class="navbar-nav text-center">
				<li class="nav-item">
					<h1><a class="nav-link {{request()->is('/') ? 'active':''}}" href="{{ url('/') }}">HOME</a></h1>
				</li>
				<li class="nav-item dropdown {{Route::is('openingPage') ? 'active':''}}">
					<h1 class="nav-link dropdown-toggle align-items-center my-auto" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						BIDDING
					</h1>
					<div class="dropdown-menu dropdown-menu-right text-center">
						@foreach (getMenu() as $m)
						@php
						$active ='';
						$currentURL = last(request()->segments());
						if($currentURL == $m->opening){
							$active = 'active';
						}
						@endphp
						<a class="dropdown-item spa_route {{$active}}" href="{{ url('/opening/'.$m->opening) }}">OPEN - {{$m->opening}}</a>
						@endforeach
					</div>
					<li class="nav-item">
						@if (!auth()->check())
						<h1><a class="nav-link {{request()->is('/login') ? 'active':''}}" href="{{ url('/login') }}">Login</a></h1>
						@else
						<h1><a class="nav-link text-decoration-none" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> LOGOUT</a></h1>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
						@endif
					</li>
				</li>
			</ul>
		</div>
	</nav>
</header>
