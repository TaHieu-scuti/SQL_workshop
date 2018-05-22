@extends('layouts.master')

@section('title')
    Agency Report
@stop

@section('filter-list')
    <ul class="panel">
        <li class="panel-body">
            <a href="{{route('client-report')}}">
                @lang('language.clients')
            </a>
        </li>
        <li class="panel-body separator">
        </li>
        <li class="panel-body grayed-out">
            @lang('language.PREFECTURES')
        </li>
        <li class="panel-body grayed-out">
                @lang('language.BY_TIME_ZONE')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.BY_DAYS_OF_THE_WEEK')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.DEVICES')
        </li>
    </ul>
@stop

@section('export')
    <li class="export_data"><a data-href="{{ url('/agency-report/export_csv') }}">@lang('language.CSV')</a></li>
    <li class="export_data"><a data-href="{{ url('/agency-report/export_excel') }}">@lang('language.Excel')</a></li>
@stop
