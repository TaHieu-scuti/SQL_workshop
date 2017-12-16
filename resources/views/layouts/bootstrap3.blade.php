<?php
use App\Http\Controllers\AbstractReportController;
?>
@if ($breadcrumbs)
    <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            <?php $titleBreadCumbs = App\User::getArrayAttribute($breadcrumb->title);?>
            <input type="hidden" name="id_{{$breadcrumb->title}}" id="id_{{$breadcrumb->title}}" value="all">
            @if ($breadcrumb->url && !$breadcrumb->last)

                    <li class="breadcrumb-item">
                    <div class="breadcrumb-item-detail">
                        <span class="title"
                        data-titleBreadCumbs="{{ __('language.' .str_slug($titleBreadCumbs['title'],'_')) }}">
                            <a href="javascript:void(0)"
                            data-title="{{ $breadcrumb->title }}">
                            {{ __('language.' .str_slug($titleBreadCumbs['title'],'_')) }}
                            </a>
                            <br>
                        </span>
                        <select class="selectpicker selectpickerBreadCrumbs tasks-bar id_{{$titleBreadCumbs['title']}}"
                        data-live-search="true" id="dropdownBreadcrumbs">
                        @if (count($titleBreadCumbs['contents']) > 0)
                                    @foreach ($titleBreadCumbs['contents'] as $key => $account)
                                    <?php
                                    $checkClient = false;
                                    if (is_array($account)) {
                                        $key = isset($account['accountid']) ? $account['accountid'] : $account['account_id'];
                                        $engine = isset($account['engine']) ? $account['engine'] : null;
                                        if (isset($account['account_id'])) {
                                            $checkClient = true;
                                        }
                                        $account = $account['accountName'];
                                    } elseif ($account !== 'All Account') {
                                        $engine = isset($titleBreadCumbs['engine']) ? $titleBreadCumbs['engine'] : '';
                                    }
                                    ?>
                                        <option data-breadcumbs="{{$key}}" data-tokens="{{$account}}"
                                            @if ($account !== 'All Account')
                                                data-engine = "{{  $engine }}"
                                            @endif
                                            @if ( $titleBreadCumbs['flag'] === 'all')
                                                {{ $key === $titleBreadCumbs['flag'] ? "selected" : ""}}
                                            @else
                                                {{ (int)$key === (int)$titleBreadCumbs['flag']
                                                && $engine === session(AbstractReportController::SESSION_KEY_ENGINE)
                                                || (int)$key === (int)$titleBreadCumbs['flag'] && $checkClient
                                                ? "selected" : ""}}
                                            @endif
                                        data-url= "{{ $breadcrumb->url }}" >
                                            <a href="#">
                                                <div class="desc" >
                                                    @if ($account === 'All Account'
                                                        || $account === 'All Campaigns'
                                                        || $account === 'All Adgroup'
                                                        || $account === 'All Keywords'
                                                        || $account === 'All Adreports'
                                                        || $account === 'All Client'
                                                        || $account === 'All Agencies')
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
                        <span class="title"
                        data-titleBreadCumbs="{{ __('language.' .str_slug($titleBreadCumbs['title'],'_')) }}">
                        {{ __('language.' .str_slug($titleBreadCumbs['title'],'_')) }}<br></span>
                        <select class="selectpicker selectpickerBreadCrumbs tasks-bar id_{{$titleBreadCumbs['title']}}"
                        data-live-search="true" id="dropdownBreadcrumbs">
                            @if (count($titleBreadCumbs['contents']) > 0)
                                @foreach ($titleBreadCumbs['contents'] as $key => $account)
                                    <?php
                                    $checkClient = false;
                                    if (is_array($account)) {
                                        $key = isset($account['accountid']) ? $account['accountid'] : $account['account_id'];
                                        $engine = isset($account['engine']) ? $account['engine'] : null;
                                    }
                                    if (is_array($account)) {
                                        $key = isset($account['accountid']) ? $account['accountid'] : $account['account_id'];
                                        $engine = isset($account['engine']) ? $account['engine'] : null;
                                        $checkClient = false;
                                        if (isset($account['account_id'])) {
                                            $checkClient = true;
                                        }
                                        $account = $account['accountName'];
                                    } elseif ($account !== 'All Account') {
                                        $engine = isset($titleBreadCumbs['engine']) ? $titleBreadCumbs['engine'] : '';
                                    }
                                    ?>
                                    <option data-breadcumbs="{{$key !== null ? $key : '' }}" data-tokens="{{$account}}"
                                        @if ($account !== 'All Account')
                                            data-engine = "{{  $engine }}"
                                        @endif
                                        data-url= "{{ $breadcrumb->url }}"
                                        @if ( $titleBreadCumbs['flag'] === 'all')
                                            {{ $key === $titleBreadCumbs['flag'] ? "selected" : ""}}
                                        @else
                                            {{ (int)$key === (int)$titleBreadCumbs['flag']
                                            && $engine === session(AbstractReportController::SESSION_KEY_ENGINE)
                                            || (int)$key === (int)$titleBreadCumbs['flag'] && $checkClient
                                            ? "selected" : ""}}
                                        @endif >
                                    <a href="#">
                                        <div class="desc" >
                                            @if ($account === 'All Account'
                                                || $account === 'All Campaigns'
                                                || $account === 'All Adgroup'
                                                || $account === 'All Keywords'
                                                || $account === 'All Adreports'
                                                || $account === 'All Client'
                                                || $account === 'All Agencies')
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
