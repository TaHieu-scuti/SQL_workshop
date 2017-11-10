@extends('layouts.master')

@section('title')
    Ad Report
@stop

@section('filter-list')
    <ul class="panel">
        <li class="panel-body specific-filter-item" data-value="prefecture">
            <a href="javascript:void(0)">
                @lang('language.PREFECTURES')
            </a>
        </li>
        <li class="panel-body">
            <p style="color: lightgrey">
                @lang('language.BY_TIME_ZONE')
            </p>
        </li>
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
    <li><a href="{{ url('/ad-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/ad-report/export_excel') }}">Excel</a></li>
@stop
