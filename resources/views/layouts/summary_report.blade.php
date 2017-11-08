<div class="col-md-3 fields active" data-name="impressions">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">@lang('language.Impr')<br></span>
                <span class="content">
                    <i class="small-blue-stuff fa fa-circle"></i>{{ $summaryReport['impressions'] }}<br>
                </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3 fields" data-name="clicks">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">@lang('language.clicks')<br></span>
                <span class="content">
                    <i class="small-blue-stuff"></i>{{$summaryReport['clicks']}}<br>
                </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3 fields" data-name="cost">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">@lang('language.cost')<br></span>
                <span class="content">
                    <i class="small-blue-stuff"></i>
                    <i class="fa fa-rmb"></i>{{ $summaryReport['cost'] }}<br>
                </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3 fields" data-name="averageCpc">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">@lang('language.AvgCPC')<br></span>
                <span class="content">
                    <i class="small-blue-stuff"></i>
                    <i class="fa fa-rmb"></i>{{ $summaryReport['averageCpc'] }}<br>
                </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3 fields" data-name="averagePosition">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">@lang('language.Avg_pos')<br></span>
                <span class="content">
                    <i class="small-blue-stuff"></i>
                    {{ $summaryReport['averagePosition'] }}<br>
                </span>
            </div>
        </section>
    </a>
</div>
