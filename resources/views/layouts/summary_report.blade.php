<div class="col-md-3 active" data-name="clicks">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">Clicks<br></span>
                <span class="content">
                    <i class="fa fa-circle"></i>{{$summaryReport['clicks']}}<br>
                </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3" data-name="impressions">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">Impr<br></span>
                <span class="content">{{ $summaryReport['impressions'] }}<br> </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3" data-name="cost">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">Cost<br></span>
                <span class="content"><i class="fa fa-rmb"></i>{{ $summaryReport['cost'] }}<br> </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3" data-name="averageCpc">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">AvgCPC<br></span>
                <span class="content">
                    <i class="fa fa-rmb"></i>{{ $summaryReport['averageCpc'] }}<br>
                </span>
            </div>
        </section>
    </a>
</div>
<div class="col-md-3" data-name="averagePosition">
    <a href="javascript:void(0)">
        <section class="panel">
            <div class="panel-body">
                <span class="title">Avg pos<br></span>
                <span class="content">
                    {{ $summaryReport['averagePosition'] }}<br>
                </span>
            </div>
        </section>
    </a>
</div>