<table>
    <thead>
    <tr>
        <th>Task</th>
        <th>Invoice</th>
        <th>Date</th>
        <th>Part number</th>
        <th>Name</th>
        <th>Description</th>
        <th>Categoría</th>
        <th>Cantidad</th>
        <th>Precio unitario</th>
        <th>Amount $</th>
        <th>TC</th>
        <th>Amount S/</th>
        <th>Afecto al ad-valorem</th>
        <th>Peso</th>
        <th>% ad-valorem</th>
        <th>% Peso</th>
        <th>% Costo</th>
        <th>Ad-valorem $</th>
        <th>Flete $</th>
        <th>Seguro $</th>
        <th>Costos de importación $</th>
        <th>Costo total $</th>
        <th>Costo unitario $</th>
        <th>Ad-valorem S/</th>
        <th>Flete S/</th>
        <th>Seguro S/</th>
        <th>Costos de importación S/</th>
        <th>Costo total S/</th>
        <th>Costo unitario S/</th>
    </tr>
    </thead>
    <tbody>
        @foreach($parent_report_items as $item)
            <tr>
                <td>{{ $item['import_order_task'] }}</td>
                <td>{{ $item['invoice'] }}</td>
                <td>{{ $item['date'] }}</td>
                <td>{{ $item['part_number'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['category'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['unit_price'] }}</td>
                <td>{{ $item['amount_usd'] }}</td>
                <td>{{ $item['exchange_rate'] }}</td>
                <td>{{ $item['amount_sol'] }}</td>
                <td>{{ $item['afecto_ad_valorem'] }}</td>
                <td>{{ $item['weight'] }}</td>
                <td>{{ $item['ad_valorem_pct'] }}</td>
                <td>{{ $item['weight_pct'] }}</td>
                <td>{{ $item['cost_pct'] }}</td>
                <td>{{ $item['ad_valorem_dol'] }}</td>
                <td>{{ $item['lading_dol'] }}</td>
                <td>{{ $item['insurance_dol'] }}</td>
                <td>{{ $item['import_cost_dol'] }}</td>
                <td>{{ $item['total_cost_dol'] }}</td>
                <td>{{ $item['unit_cost'] }}</td>
                <td>{{ $item['ad_valorem_sol'] }}</td>
                <td>{{ $item['lading_sol'] }}</td>
                <td>{{ $item['insurance_sol'] }}</td>
                <td>{{ $item['import_cost_sol'] }}</td>
                <td>{{ $item['total_cost_sol'] }}</td>
                <td>{{ $item['unit_cost_sol'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
