<header>
	<div class="bg-light">
		<div class="container text-right text-dark py-3">
			<h5>Practice makes you better!</h5>
			<a href="{{ url('/bidding') }}">DATA BIDDING</a>
		</div>
	</div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="mx-auto">
			<ul class="navbar-nav text-center">
				<li class="nav-item">
					<h5><a class="nav-link {{request()->is('/') ? 'active':''}}" href="{{ url('/') }}">HOME</a></h5>
				</li>
				<li class="nav-item dropdown {{Route::is('openingPage') ? 'active':''}}">
					<h5 class="nav-link dropdown-toggle align-items-center my-auto" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						BIDDING
					</h5>
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
						<h5><a class="nav-link {{request()->is('/login') ? 'active':''}}" href="{{ url('/login') }}">LOGIN</a></h5>
						@else
						<h5><a class="nav-link text-decoration-none" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> LOGOUT</a></h5>
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
