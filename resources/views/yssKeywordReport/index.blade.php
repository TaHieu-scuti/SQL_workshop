<?php
use App\Http\Controllers\AbstractReportController;
use App\Http\Controllers\RepoYssKeywordReport\RepoYssKeywordReportController;
?>
@extends('layouts.master')

@section('title')
    Keyword Report
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
            @if (session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'device'
                || session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'hourofday'
                || session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'dayOfWeek'
                || session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'prefecture'
            )
                <li class="panel-body normal-report">
                    <a href="javascript:void(0)">
                        @lang('language.keyword')
                    </a>
                </li>
            @else
                <li class="panel-body normal-report active">
                    <a href="javascript:void(0)">
                        @lang('language.keyword')
                    </a>
                </li>
            @endif
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
        <li class="panel-body specific-filter-item
            @if (session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'prefecture') active @endif"
            data-value="prefecture">
            <a href="javascript:void(0)">
                @lang('language.PREFECTURES')
            </a>
        </li>
        <li class="panel-body specific-filter-item
            @if (session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'hourofday') active @endif"
            data-value="hourofday">
            <a href="javascript:void(0)">
                @lang('language.BY_TIME_ZONE')
            </a>
        </li>
        <li class="panel-body specific-filter-item
            @if (session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'dayOfWeek') active @endif"
            data-value="dayOfWeek">
            <a href="javascript:void(0)">
                @lang('language.BY_DAYS_OF_THE_WEEK')
            </a>
        </li>
        <li class="panel-body specific-filter-item
            @if (session(RepoYssKeywordReportController::SESSION_KEY_GROUPED_BY_FIELD) === 'device') active @endif"
            data-value="device">
            <a href="javascript:void(0)">
                @lang('language.DEVICES')
            </a>
        </li>
    </ul>
@stop

@section('export')
    <li><a href="{{ url('/keyword-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/keyword-report/export_excel') }}">Excel</a></li>
    <li><a href="{{ url('/keyword-report/export_search_query_csv') }}">Search Query CSV</a></li>
    <li><a href="{{ url('/keyword-report/export_search_query_excel') }}">Search Query Excel</a></li>
@stop
