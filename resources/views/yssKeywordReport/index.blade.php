<?php
use App\Http\Controllers\AbstractReportController;
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
            <li class="panel-body active">
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
        <li class="panel-body grayed-out">
            @lang('language.PREFECTURES')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.BY_TIME_ZONE')
        <li class="panel-body grayed-out">
            @lang('language.BY_DAYS_OF_THE_WEEK')
        </li>
        <li class="panel-body grayed-out">
            @lang('language.DEVICES')
        </li>
    </ul>
@stop

@section('export')
    <li><a href="{{ url('/keyword-report/export_csv') }}">CSV</a></li>
    <li><a href="{{ url('/keyword-report/export_excel') }}">Excel</a></li>
    <li><a href="{{ url('/keyword-report/export_search_query_csv') }}">Search Query CSV</a></li>
    <li><a href="{{ url('/keyword-report/export_search_query_excel') }}">Search Query Excel</a></li>
@stop
