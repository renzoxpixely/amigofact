<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Series</title>
    </head>
    <body>  
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Serie</th>
                                <th>Producto</th>
                                <th>Fecha</th>
                                <th>Grupo</th>
                                <th>Cliente</th>
                                <th>N° Contrato</th>
                                <th>Tipo de Contrato</th>
                                <th>Sala</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            @php
                                $value = (object) $value;
                            @endphp
                            <tr>
                                <td class="celda">{{$loop->iteration}}</td>
                                <td class="celda">{{$value->series}}</td>
                                <td class="celda">{{$value->item_description}}</td>
                                <td class="celda">{{$value->date}}</td>
                                <td class="celda">{{$value->group}}</td>
                                <td class="celda">{{$value->customer}}</td>
                                <td class="celda">{{$value->contract}}</td>
                                <td class="celda">{{$value->contract_type}}</td>
                                <td class="celda">{{$value->hall}}</td>
 
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


