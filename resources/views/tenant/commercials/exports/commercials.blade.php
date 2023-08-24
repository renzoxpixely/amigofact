<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Clientes</title>
    </head>
    <body>
        <div>

        @if ($records->isNotEmpty())
            <h3 align="center" class="title">{{ $records[0]->accepted_date }}<strong></strong></h3>
        @endif

        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Meses de Contrato</th>
                                <th>Estado</th>
                                <th>Cliente</th>
                                <th>Participación del Cliente</th>
                                <th>Transporte de ida</th>
                                <th>Transporte de vuelta</th>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td class="celda">{{$value->id }}</td>
                                <td class="celda">{{$value->months }}</td>
                                <td class="celda">{{$value->status }}</td>
                                <td class="celda">{{$name}}</td>
                                <td class="celda">{{$value->customer_participation}}</td>
                                <td class="celda">{{$value->one_way_transportation}}</td>
                                <td class="celda">{{$value->return_transport}}</td>
                                <td class="celda">{{$value->comments}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
<style>
    .celda {
        white-space: nowrap;
        overflow: hidden;
    }
</style>