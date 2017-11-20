@if ($breadcrumbs)
    <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            <?php $titleBreadCumbs = App\User::getArrayAttribute($breadcrumb->title); ?>
            <input type="hidden" name="id_{{$breadcrumb->title}}" id="id_{{$breadcrumb->title}}" value="all">
            @if ($breadcrumb->url && !$breadcrumb->last)
                <li class="breadcrumb-item">
                    <div class="breadcrumb-item-detail">
                        <span class="title" data-titleBreadCumbs="{{ __('language.' .str_slug($titleBreadCumbs[0],'_')) }}"><a href="{{ $breadcrumb->url }}">{{ __('language.' .str_slug($titleBreadCumbs[0],'_')) }}</a><br></span>
                        <select class="selectpicker selectpickerBreadCrumbs tasks-bar id_{{$titleBreadCumbs[0]}}" data-live-search="true" id="dropdownBreadcrumbs">
                            @if (count($titleBreadCumbs[1]) > 0)
                                    @foreach ($titleBreadCumbs[1] as $key => $account)
                                        <option data-breadcumbs="{{$key}}" data-tokens="{{$account}}" 
                                            @if ( $titleBreadCumbs['flag'] === 'all')
                                                {{ $key === $titleBreadCumbs['flag'] ? "selected" : ""}}
                                            @else
                                                {{ (int)$key === (int)$titleBreadCumbs['flag'] ? "selected" : ""}}
                                            @endif
                                        data-url= "{{ $breadcrumb->url }}" >
                                            <a href="#">
                                                <div class="desc" >
                                                    @if ($account == 'All Account'
                                                    || $account == 'All Campaigns'
                                                    || $account == 'All Adgroup'
                                                    || $account == 'All Keywords'
                                                    || $account == 'All Adreports')
                                                        {{__('language.' .str_slug($account,'_'))}}
                                                    @else
                                                        {{$account}}
                                                    @endif
                                                </div>
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
                        <span class="title" data-titleBreadCumbs="{{ __('language.' .str_slug($titleBreadCumbs[0],'_')) }}">{{ __('language.' .str_slug($titleBreadCumbs[0],'_')) }}<br></span>
                        <select class="selectpicker selectpickerBreadCrumbs tasks-bar id_{{$titleBreadCumbs[0]}}" data-live-search="true" id="dropdownBreadcrumbs">
                            @if (count($titleBreadCumbs[1]) > 0)
                                @foreach ($titleBreadCumbs[1] as $key => $account)
                                    <option data-breadcumbs="{{$key}}" data-tokens="{{$account}}" data-url= "{{ $breadcrumb->url }}" @if ( $titleBreadCumbs['flag'] === 'all'){{ $key === $titleBreadCumbs['flag'] ? "selected" : ""}} @else{{ (int)$key === (int)$titleBreadCumbs['flag'] ? "selected" : ""}}@endif >
                                    <a href="#">
                                        <div class="desc" >
                                            @if ($account == 'All Account' || $account == 'All Campaigns' || $account == 'All Adgroup' || $account == 'All Keywords' || $account == 'All Adreports')
                                                {{__('language.' .str_slug($account,'_'))}}
                                            @else
                                                {{$account}}
                                            @endif
                                        </div>
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