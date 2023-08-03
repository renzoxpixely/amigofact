{{-- Suscription --}}
@if(in_array('suscription_app', $vc_modules) )
    <li class=" nav-parent {{ ($firstLevel === 'suscription') ? 'nav-active nav-expanded' : '' }}">
        <a class="nav-link"
            href="#">
            <i class="fa fas fa-calendar-check"
                aria-hidden="true"></i>
            <span>Arrendamiento</span>
        </a>
        <ul class="nav nav-children">
            {{--                                @if(in_array('suscription_app_client', $vc_module_levels))--}}


            <li class="nav-parent {{ ( ($firstLevel === 'suscription') && ($secondLevel === 'client') ) ? ' nav-active nav-expanded ' : '' }}">

                <a class="nav-link"
                    href="#">
                    Clientes
                </a>
                <ul class="nav nav-children">
                    <li class="{{ ( ($firstLevel === 'suscription') && ($secondLevel === 'client')  && ($thridLevel !== 'childrens') )?'nav-active':'' }}">
                        <a class="nav-link"
                            href="{{ route('tenant.suscription.client.index') }}">
                            Clientes
                        </a>
                    </li>
                    <li class="{{ ( ($firstLevel === 'suscription') && ($secondLevel === 'client') && ($thridLevel === 'childrens') )?'nav-active':'' }}">
                        <a class="nav-link"
                            href="{{ route('tenant.suscription.client_children.index') }}">
                            Salas
                        </a>
                    </li>
                </ul>
            </li>
            {{--                                @endif--}}
            {{--
            @todo suscription_app_service borrar de modulo de permisos admin y cliente

            @if(in_array('suscription_app_service', $vc_module_levels))
                <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'service')) ? 'nav-active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('tenant.suscription.service.index') }}">
                        Servicio
                    </a>
                </li>
            @endif
                --}}
            {{--                                    @if(in_array('suscription_app_plans', $vc_module_levels))--}}
            <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'plans')) ? 'nav-active' : '' }}">
                <a class="nav-link"
                    href="{{ route('tenant.suscription.plans.index') }}">
                    Planes
                </a>
            </li>
            {{--                                    @endif--}}

            {{--                                   @if(in_array('suscription_app_payments', $vc_module_levels))--}}
            <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'payments')) ? 'nav-active' : '' }}">
                <a class="nav-link"
                    href="{{ route('tenant.suscription.payments.index') }}">
                    Contratos
                </a>
            </li>
            {{--                                @endif--}}
            {{--                                   @if(in_array('suscription_app_payments', $vc_module_levels))--}}
            <li class="{{ (($firstLevel === 'suscription') && ($secondLevel === 'payment_receipt')) ? 'nav-active' : '' }}">
                <a class="nav-link"
                    href="{{ route('tenant.suscription.payment_receipt.index') }}">
                    Recibos de pago
                </a>
            </li>
            {{--                                @endif--}}
        </ul>
    </li>
@endif