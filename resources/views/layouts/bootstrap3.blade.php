@if ($breadcrumbs)
	<ul class="breadcrumb">
		@foreach ($breadcrumbs as $breadcrumb)

			<?php $titleBreadCumbs = App\User::getArrayAttribute($breadcrumb->title); ?>
			<input type="hidden" name="id_{{$breadcrumb->title}}" id="id_{{$breadcrumb->title}}" value="all">
			@if ($breadcrumb->url && !$breadcrumb->last)
				<li class="breadcrumb-item">
					<div class="breadcrumb-item-detail">
						<span class="title"><a href="{{ $breadcrumb->url }}">{{ $titleBreadCumbs[0] }}</a><br></span>
	                    <select class="selectpicker tasks-bar id_{{$titleBreadCumbs[0]}}" data-live-search="true" id="dropdownBreadcrumbs">
	                    	@if (count($titleBreadCumbs[1]) > 0)
		                    		@foreach ($titleBreadCumbs[1] as $key => $account)
	                                    <option data-breadcumbs="{{$key}}" data-tokens="{{$account}}" {{ (int)$key === (int)$titleBreadCumbs['flag'] ? "selected" : ""}} >
	                                    <a href="#">
	                                        <div class="desc" >{{$account}}</div>
	                                    </a>
	                                </option>
	                                @endforeach
	                    	@endif
	                    </select>
                	</div>
				</li>
			@else
				<li class="breadcrumb-item active">
					<div class="breadcrumb-item-detail">
						<span class="title">{{ $titleBreadCumbs[0] }}<br></span>
	                    <select class="selectpicker tasks-bar id_{{$titleBreadCumbs[0]}}" data-live-search="true" id="dropdownBreadcrumbs">
	                    	@if (count($titleBreadCumbs[1]) > 0)
	                    		@foreach ($titleBreadCumbs[1] as $key => $account)
                                    <option data-breadcumbs="{{$key}}" data-tokens="{{$account}}" {{ (int)$key === (int)$titleBreadCumbs['flag'] ? "selected" : ""}} >
                                    <a href="#">
                                        <div class="desc" >{{$account}}</div>
                                    </a>
                                </option>
                                @endforeach
	                    	@endif
	                    </select>
	                </div>
				</li>
			@endif
		@endforeach
	</ul>
@endif
