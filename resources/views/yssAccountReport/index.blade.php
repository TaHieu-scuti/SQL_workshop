@extends('layouts.master')

@section('title')
    Account Report
@stop

@section('filter-list')
    <ul class="panel">
        <li class="panel-body">
            <a href="campaign-list.html">
                CAMPAIGNS
            </a>
        </li>
        <li class="panel-body">
            <a href="#">
                AD GROUPS
            </a>
        </li>
        <li class="panel-body">
            <a href="keywords.html">
                KEYWORDS
            </a>
        </li>
        <li class="panel-body">
            <a href="ad-list.html">
                ADS
            </a>
        </li>
        <li class="panel-body separator">
        </li>
        <li class="panel-body">
            <a href="prefectures.html">
                PREFECTURES
            </a>
        </li>
        <li class="panel-body specific-filter-item" data-value="hourofday">
            <a href="javascript:void(0)">
                BY TIME ZONE
            </a>
        </li>
        <li class="panel-body">
            <a href="days-of-the-week.html">
                BY DAYS OF THE WEEK
            </a>
        </li>
        <li class="panel-body specific-filter-item" data-value="device">
            <a href="javascript:void(0)">
                DEVICES
            </a>
        </li>
    </ul>
@stop

@section('export')
    <li><a href="{{ url('/account_report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/account_report/export_excel') }}">Excel</a></li>
@stop
