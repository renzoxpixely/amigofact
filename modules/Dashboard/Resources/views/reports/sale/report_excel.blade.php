<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Venta</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Empresa: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$company->name}}</strong></p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong></p>
                    </td>
                    <td align="center">
                        <p><strong>{{date('Y-m-d')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong></p>
                    </td>
                    <td align="center">{{$company->number}}</td>

                </tr>
            </table>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Serial N°</th>
                                <th rowspan="2">Model</th>
                                <th rowspan="2">Contract N°</th>
                                <th rowspan="2">Client</th>
                                <th rowspan="2">Company</th>
                                <th rowspan="2">Sala</th>
                                <th rowspan="2">Region</th>
                                <th rowspan="2">City</th>
                                <th rowspan="2" class="text-right">Date of Installation</th>
                                <th rowspan="2" class="text-right">Doc. de Atribución</th>
                                <th rowspan="2" class="text-right">Invoice N°</th>
                                @foreach($records['months'] as $key => $value)
                                <th colspan="2" class="text-center">{{$value}}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($records['months_subtitle'] as $key => $value)
                                <th class="text-center">{{$value}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records['data'] as $key => $value)
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value['serie']}}</td>
                                    <td class="celda">{{$value['item']}}</td>
                                    <td class="celda">{{$value['contract']}}</td>
                                    <td class="celda">{{$value['customer']}}</td>
                                    <td class="celda">{{$value['contact']}}</td>
                                    <td class="celda">{{$value['hall']}}</td>
                                    <td class="celda">{{$value['region']}}</td>
                                    <td class="celda">{{$value['city']}}</td>
                                    <td class="celda">{{$value['activation_date']}}</td>
                                    <td class="celda">{{$value['mincetur']}}</td>
                                    <td class="celda">{{$value['invoice']}}</td>
                                    @foreach($value['result'] as $key2 => $value2)
                                    <td class="celda">{{$value2}}</td>
                                    @endforeach
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
