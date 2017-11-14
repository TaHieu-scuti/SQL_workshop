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
        <li class="panel-body">
            <a href="{{ route('adgroup-report') }}">
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
    <li><a href="{{ url('/campaign-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/campaign-report/export_excel') }}">Excel</a></li>
@stop
