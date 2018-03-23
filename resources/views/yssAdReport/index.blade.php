<?php
use App\Http\Controllers\AbstractReportController;
use App\Http\Controllers\RepoYssAdReport\RepoYssAdReportController;

?>

@extends('layouts.master')

@section('title')
    Ad Report
@stop

@section('filter-list')
<ul class="panel">
    <li class="panel-body campaign-navigation">
        <a href="{{ route('campaign-report') }}">
            @lang('language.campaign')
        </a>
    </li>
    <li class="panel-body adgroup-navigation">
        <a href="{{session(AbstractReportController::SESSION_KEY_AD_GROUP_ID) !== null ? 'javascript:void(0)' : route('adgroup-report') }}">
            @lang('language.AD_GROUPS')
        </a>
    </li>
    {{--YDN has no keywords report--}}
    @if(session('engine') !== null && session('engine') === 'ydn')
        <li class="panel-body grayed-out">
            @lang('language.keywords')
        </li>
    @else
        <li class="panel-body">
            <a href="{{ route('keyword-report') }}">
                @lang('language.keywords')
            </a>
        </li>
    @endif
    {{--YSS has no ads report--}}
    @if(session('engine') !== null && session('engine') === 'yss')
        <li class="panel-body grayed-out">
            @lang('language.ADS')
        </li>
    @else
        <li class="panel-body active">
            <a href="{{ route('ad-report') }}">
                @lang('language.ADS')
            </a>
        </li>
    @endif
    <li class="panel-body separator">
    </li>
    <li class="panel-body specific-filter-item
            @if (session(RepoYssAdReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'prefecture') active @endif"
        data-value="prefecture">
        <a href="javascript:void(0)">
            @lang('language.PREFECTURES')
        </a>
    </li>
    <li class="panel-body specific-filter-item
            @if (session(RepoYssAdReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'hourofday') active @endif"
        data-value="hourofday">
        <a href="javascript:void(0)">
            @lang('language.BY_TIME_ZONE')
        </a>
    </li>
    <li class="panel-body specific-filter-item
            @if (session(RepoYssAdReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'dayOfWeek') active @endif"
        data-value="dayOfWeek">
        <a href="javascript:void(0)">
            @lang('language.BY_DAYS_OF_THE_WEEK')
        </a>
    </li>
    <li class="panel-body specific-filter-item
            @if (session(RepoYssAdReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'device') active @endif"
        data-value="device">
        <a href="javascript:void(0)">
            @lang('language.DEVICES')
        </a>
    </li>
</ul>
@stop

@section('export')
    <li><a href="{{ url('/ad-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/ad-report/export_excel') }}">Excel</a></li>
@stop
