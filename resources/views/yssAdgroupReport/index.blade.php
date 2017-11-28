@extends('layouts.master')

@section('title')
    Adgroup Report
@stop

@section('filter-list')
    <ul class="panel">
        <li class="panel-body normal-report">
            @if (session('engine') === 'adw')
                <img src="images/adwords.png" width="15px" height="15px" class="iconMedia" >
            @else
                <img src="images/yahoo.png" width="15px" height="15px" class="iconMedia" >
            @endif
            <a href="javascript:void(0)">
                @lang('language.AD_GROUPS')
            </a>
        </li>
        <li class="panel-body">
            <a href="{{ route('keyword-report') }}">
                @lang('language.keywords')
            </a>
        </li>
        <li class="panel-body">
            <a href="{{ route('ad-report') }}">
                @lang('language.ADS')
            </a>
        </li>
        <li class="panel-body separator">
        </li>
        <li class="panel-body specific-filter-item" data-value="prefecture">
            <a href="javascript:void(0)">
                @lang('language.PREFECTURES')
            </a>
        </li>
        <li class="panel-body specific-filter-item" data-value="hourofday">
            <a href="javascript:void(0)">
                @lang('language.BY_TIME_ZONE')

        <li class="panel-body specific-filter-item" data-value="dayOfWeek">
            <a href="javascript:void(0)">
                @lang('language.BY_DAYS_OF_THE_WEEK')
            </a>
        </li>
        <li class="panel-body specific-filter-item" data-value="device">
            <a href="javascript:void(0)">
                @lang('language.DEVICES')
            </a>
        </li>
    </ul>
@stop

@section('export')
    <li><a href="{{ url('/adgroup-report/export_csv') }}">@lang('language.CSV')</a></li>
    <li><a href="{{ url('/adgroup-report/export_excel') }}">@lang('language.Excel')</a></li>
@stop
