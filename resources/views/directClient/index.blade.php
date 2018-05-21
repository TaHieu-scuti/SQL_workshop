@extends('layouts.master')

@section('title')
Direct Clients Report
@stop

@section('filter-list')
<ul class="panel">
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
</ul>
@stop

@section('export')
<li class="export_data"><a data-href="{{ url('/direct-client-report/export_csv') }}">@lang('language.CSV')</a></li>
<li class="export_data"><a data-href="{{ url('/direct-client-report/export_excel') }}">@lang('language.Excel')</a></li>
@stop
