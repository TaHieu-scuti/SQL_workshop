<?php
use App\Http\Controllers\RepoAccountReport\RepoAccountReportController;

$accountGrouped = session(RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD);
?>
@extends('layouts.master')

@section('title')
    Account Report
@stop

@section('filter-list')
    <ul class="panel">
        <li class="panel-body normal-report @if ($accountGrouped === 'accountName') active @endif">
            <a href="javascript:void(0)">
                @lang('language.engineAccount')
            </a>
        </li>
        <li class="panel-body grayed-out">
            @lang('language.campaign')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.AD_GROUPS')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.keywords')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.ADS')
        </li>
        <li class="panel-body separator">
        </li>
        <li class="panel-body specific-filter-item @if ($accountGrouped === 'prefecture') active @endif"
        data-value="prefecture">
            <a href="javascript:void(0)">
                @lang('language.PREFECTURES')
            </a>
        </li>
        <li class="panel-body specific-filter-item @if ($accountGrouped === 'hourofday') active @endif"
        data-value="hourofday">
            <a href="javascript:void(0)">
                @lang('language.BY_TIME_ZONE')

        <li class="panel-body specific-filter-item @if ($accountGrouped === 'dayOfWeek') active @endif"
        data-value="dayOfWeek">
            <a href="javascript:void(0)">
                @lang('language.BY_DAYS_OF_THE_WEEK')
            </a>
        </li>
        <li class="panel-body specific-filter-item @if ($accountGrouped === 'device') active @endif"
        data-value="device">
            <a href="javascript:void(0)">
                @lang('language.DEVICES')
            </a>
        </li>
    </ul>
@stop

@section('export')
    <li class="export_data"><a data-href="{{ url('/account_report/export_csv') }}">@lang('language.CSV')</a></li>
    <li class="export_data"><a data-href="{{ url('/account_report/export_excel') }}">@lang('language.Excel')</a></li>
@stop
