<thead>
    <tr>
        @foreach($fieldName as $column)
        <th>
            <a href="#">{{ $column }}</a>
        </th>
        @endforeach
    </tr>
</thead>
<tbody>
    @foreach($yssAccountReports as $yssAccountReport)
    <tr>
        @foreach($fieldName as $column)
            <td>{{ $yssAccountReport[$column] }}</td>
        @endforeach
    </tr>
    @endforeach
</tbody>