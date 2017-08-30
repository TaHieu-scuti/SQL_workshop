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
                <th>
                    <a href="javascript:void(0)">{{ $fieldName }}</a>
                </th>
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
                @foreach($fieldNames as $fieldName)
                    @if($fieldName === 'account_id')
                        <?php continue; ?>
                    @endif
                    @if(isset($totalDataArray->$fieldName))
                    <td>{{ $totalDataArray->$fieldName }}</td>
                    @else
                    <td></td>
                    @endif
                @endforeach
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