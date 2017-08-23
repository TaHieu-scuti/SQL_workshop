<?php
    if (!isset($export)) {
        $export = false;
    }
?>
<div class="row report-table">
    <div class="col-md-12">
    <table class="table table-striped" id="reportTable">
        <thead>
            <tr>
                @foreach($fieldNames as $fieldName)
                    @if ($columnSort === $fieldName && $sort ==="desc")
                        <th>
                            <a href="javascript:void(0)">
                            <i class="fa fa-arrow-down"></i>{{ $fieldName }}</a>
                        </th>
                    @elseif ($columnSort === $fieldName && $sort ==="asc")
                        <th>
                            <a href="javascript:void(0)">
                            <i class="fa fa-arrow-up"></i>{{ $fieldName }}</a>
                        </th>
                    @else 
                        <th>
                            <a href="javascript:void(0)"></i>{{ $fieldName }}</a>
                        </th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                @foreach($fieldNames as $fieldName)
                    <td>{{ $report->$fieldName }}</td>
                @endforeach
            </tr>
            @endforeach
            <tr>
                <td>Total - all networks</td>
            </tr>
            @if (!$export)
            <tr>
                <td class="paginator">
                    {{ $reports->links('pagination') }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    </div>
</div>