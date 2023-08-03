<table style="width: 100%">
    <thead>
    <tr>
        <td>Numero Contrato</td>
        <td>Tipo Contrato</td>
        <td>Cliente</td>
        <td>Sala</td>
        <td>Producto</td>
        <td>Serie</td>
    </tr>
    </thead>
    <tbody>
    @foreach ($contratos as $contrato)
        @foreach ($contrato->items as $item)
            @foreach ($item->item->lots as $lot)
                <tr>
                    <td>{{ $contrato->prefix }} - {{ $contrato->number }}</td>
                    <td>{{ $contrato->tipocontrato->name }}</td>
                    <td>{{ $contrato->customer->name }}</td>
                    <td>{{ $item->hall->name }}</td>
                    <td>{{ $item->item->description }}</td>
                    <td>{{ $item->item->lots[0]->series}}</td>
                </tr>
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>
