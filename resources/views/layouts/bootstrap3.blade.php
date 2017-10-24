@if ($breadcrumbs)
	<ul class="breadcrumb">
		@foreach ($breadcrumbs as $breadcrumb)
			<?php $titleBreadCumbs = App\User::getArrayAttribute($breadcrumb->title); ?>
			@if ($breadcrumb->url && !$breadcrumb->last)
				<li class="breadcrumb-item">
					<div class="breadcrumb-item-detail">
						<span class="title"><a href="{{ $breadcrumb->url }}">{{ $titleBreadCumbs[0] }}</a><br></span>
	                    <a data-toggle="dropdown" id="dropdownMenu1" class="dropdown-toggle" href="{{ $breadcrumb->url }}">
	                        <span class="content">{{$titleBreadCumbs[1] }}</span>
	                    </a>
	                    <ul class="dropdown-menu extended tasks-bar" id="dropdownMenu1"></ul>
                	</div>
				</li>
			@else
				<li class="breadcrumb-item active">
					<div class="breadcrumb-item-detail">
						<span class="title">{{ $titleBreadCumbs[0] }}<br></span>
	                    <a data-toggle="dropdown" id="dropdownMenu1" class="dropdown-toggle" href="#">
	                        <span class="content">{{ $titleBreadCumbs[1] }}</span>
	                    </a>
	                    <ul class="dropdown-menu extended tasks-bar" id="dropdownMenu1"></ul>
	                </div>
				</li>
			@endif
		@endforeach
	</ul>
@endif
