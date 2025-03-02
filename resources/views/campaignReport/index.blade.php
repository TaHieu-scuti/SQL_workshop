<?php
use App\Http\Controllers\RepoCampaignReport\RepoCampaignReportController;
?>
@extends('layouts.master')

@section('title')
    Campaign Report
@stop

@section('site-information')
    <section class="panel">
        <div class="panel-body">
            <span class="site-info-annotation">@lang('language.account')<br></span>
            <span class="element-name">
                @lang('language.campaignname')
            </span>
        </div>
    </section>
@stop

@section('filter-list')
    <ul class="panel">
        @if (session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'device'
            || session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'hourofday'
            || session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'dayOfWeek'
            || session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'prefecture'
        )
            <li class="panel-body normal-report">
                <a href="javascript:void(0)">
                    @lang('language.campaign')
                </a>
            </li>
        @else
            <li class="panel-body normal-report active">
                <a href="javascript:void(0)">
                    @lang('language.campaign')
                </a>
            </li>
        @endif
        <li class="panel-body">
            <a href="{{ route('adgroup-report') }}">
                @lang('language.AD_GROUPS')
            </a>
        </li>
        {{--YDN has no keywords report--}}
        @if(session('engine') !== null && session('engine') === 'ydn')
        <li class="panel-body grayed-out ">
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
            <li class="panel-body">
                <a href="{{ route('ad-report') }}">
                    @lang('language.ADS')
                </a>
            </li>
        @endif
        <li class="panel-body separator">
        </li>
        <li class="panel-body specific-filter-item
            @if (session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'prefecture') active @endif"
            data-value="prefecture">
            <a href="javascript:void(0)">
                @lang('language.PREFECTURES')
            </a>
        </li>
        <li class="panel-body specific-filter-item 
            @if (session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'hourofday') active @endif" 
            data-value="hourofday">
            <a href="javascript:void(0)">
                @lang('language.BY_TIME_ZONE')
            </a>
        </li>
        <li class="panel-body specific-filter-item 
            @if (session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'dayOfWeek') active @endif" 
            data-value="dayOfWeek">
            <a href="javascript:void(0)">
                @lang('language.BY_DAYS_OF_THE_WEEK')
            </a>
        </li>
        <li class="panel-body specific-filter-item 
            @if (session(RepoCampaignReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'device') active @endif" 
            data-value="device">
            <a href="javascript:void(0)">
                @lang('language.DEVICES')
            </a>
        </li>
    </ul>
@stop

@section('export')
    <li class="export_data"><a data-href="{{ url('/campaign-report/export_csv') }}">CSV</a></li>
    <li class="export_data"><a data-href="{{ url('/campaign-report/export_excel') }}">Excel</a></li>
@stop
