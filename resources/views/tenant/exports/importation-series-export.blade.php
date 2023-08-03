<table>
    <thead>
    <tr>
        <th>Sap Material #</th>
        <th>Import Order</th>
        <th>Invoice #</th>
        <th>Mes</th>
        <th>Prod. Year</th>
        <th>Type and Version</th>
        <th>Serial #</th>
        <th>Codigo Mincetur</th>
        <th>RD</th>
    </tr>
    </thead>
    <tbody>
    @foreach($series as $serie)
    <tr>
        <td> {{ $serie['matrix_code'] }}</td>
        <td> {{ $serie['import_order_task'] }}</td>
        <td> {{ $serie['invoice_number'] }}</td>
        <td> {{ $serie['month'] }}</td>
        <td> {{ $serie['prod_year'] }}</td>
        <td> {{ $serie['type_version'] }}</td>
        <td> {{ $serie['serial'] }}</td>
        <td> {{ $serie['mincetur_code'] }}</td>
        <td> {{ $serie['rd'] }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
