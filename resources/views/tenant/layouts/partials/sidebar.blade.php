<?php

    use App\Models\Tenant\Configuration;

    $configuration = Configuration::first();
    $firstLevel = $path[0] ?? null;
    $secondLevel = $path[1] ?? null;
    $thridLevel = $path[2] ?? null;
?>
<aside id="sidebar-left"
       class="sidebar-left">
    <div class="sidebar-header">
        <a href="{{route('tenant.dashboard.index')}}"
           class="logo pt-2 pt-md-0">
            @if($vc_company->logo)
                <img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}"
                     alt="Logo"/>
            @else
                <img src="{{asset('logo/700x300.jpg')}}"
                     alt="Logo"/>
            @endif
        </a>
        <div class="d-md-none toggle-sidebar-left"
             data-toggle-class="sidebar-left-opened"
             data-target="html"
             data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars"
               aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="nano">
        <div class="nano-content">
            <nav id="menu"
                 class="nav-main"
                 role="navigation">
                <ul class="nav nav-main">
                    @if(in_array('dashboard', $vc_modules))
                        <li class="{{ ($firstLevel === 'dashboard')?'nav-active':'' }}">
                            <a class="nav-link"
                               href="{{ route('tenant.dashboard.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-airplay">
                                    <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                    <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                </svg>
                                <span>DASHBOARD</span>
                            </a>
                        </li>
                    @endif

                    {{-- Ventas --}}
                    @if(in_array('documents', $vc_modules))
                        <li class="
                        nav-parent
                        {{ ($firstLevel === 'documents')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'summaries')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'voided')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'quotations')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'sale-notes')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'contingencies')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'incentives')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'order-notes')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'sale-opportunities')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'technical-services')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'user-commissions')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'regularize-shipping')?'nav-active nav-expanded':'' }}
                            ">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16"
                                          y1="13"
                                          x2="8"
                                          y2="13"></line>
                                    <line x1="16"
                                          y1="17"
                                          x2="8"
                                          y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span>VENTAS</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(auth()->user()->type != 'integrator' && $vc_company->soap_type_id != '03')
                                    @if(in_array('documents', $vc_modules))
                                        @if(in_array('new_document', $vc_module_levels))
                                            <li class="{{ ($firstLevel === 'documents' && $secondLevel === 'create')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.documents.create')}}">Comprobante
                                                                                               electrónico</a>
                                            </li>
                                        @endif
                                    @endif
                                @endif

                                @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')
                                    @if(in_array('list_document', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'documents' && $secondLevel != 'create' && $secondLevel != 'not-sent'&& $secondLevel != 'regularize-shipping')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.documents.index')}}">Listado de comprobantes</a>
                                        </li>
                                    @endif
                                @endif

                                @if(in_array('sale_notes', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'sale-notes')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.sale_notes.index')}}">Notas de Venta</a>
                                    </li>
                                @endif

                                @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')

                                    @if(in_array('document_not_sent', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'documents' && $secondLevel === 'not-sent')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.documents.not_sent')}}">
                                                Comprobantes no enviados
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array('regularize_shipping', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'documents' && $secondLevel === 'regularize-shipping')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.documents.regularize_shipping')}}">
                                                CPE pendientes de rectificación
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if(auth()->user()->type != 'integrator' && in_array('documents', $vc_modules) )

                                    @if(auth()->user()->type != 'integrator' && in_array('document_contingengy', $vc_module_levels) && $vc_company->soap_type_id != '03')
                                        <li class="{{ ($firstLevel === 'contingencies' )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.contingencies.index')}}">
                                                Documentos de contingencia
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('summary_voided', $vc_module_levels) && $vc_company->soap_type_id != '03')

                                        <li class="nav-parent
                                        {{ ($firstLevel === 'summaries')?'nav-active nav-expanded':'' }}
                                        {{ ($firstLevel === 'voided')?'nav-active nav-expanded':'' }}
                                            ">
                                            <a class="nav-link"
                                               href="#">
                                                Resúmenes y Anulaciones
                                            </a>
                                            <ul class="nav nav-children">
                                                <li class="{{ ($firstLevel === 'summaries')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.summaries.index')}}">
                                                        Resúmenes
                                                    </a>
                                                </li>
                                                <li class="{{ ($firstLevel === 'voided')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.voided.index')}}">
                                                        Anulaciones
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif

                                    @if(in_array('sale-opportunity', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'sale-opportunities')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.sale_opportunities.index')}}">
                                                Oportunidad de venta
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('quotations', $vc_module_levels))

                                        <li class="{{ ($firstLevel === 'quotations')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.quotations.index')}}">
                                                Cotizaciones
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('order-note', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'order-notes')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.order_notes.index')}}">
                                                Pedidos
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('technical-service', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'technical-services')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.technical_services.index')}}">
                                                Servicio de soporte técnico
                                            </a>
                                        </li>
                                    @endif

                                    @if(in_array('incentives', $vc_module_levels))

                                        <li class="nav-parent
                                        {{ ($firstLevel === 'incentives')?'nav-active nav-expanded':'' }}
                                        {{ ($firstLevel === 'user-commissions')?'nav-active nav-expanded':'' }}
                                            ">
                                            <a class="nav-link"
                                               href="#">
                                                Comisiones
                                            </a>
                                            <ul class="nav nav-children">
                                                <li class="{{ ($firstLevel === 'user-commissions')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.user_commissions.index')}}">
                                                        Vendedores
                                                    </a>
                                                </li>
                                                <li class="{{ ($firstLevel === 'incentives')?'nav-active':'' }}">
                                                    <a class="nav-link"
                                                       href="{{route('tenant.incentives.index')}}">Productos</a>
                                                </li>
                                            </ul>
                                        </li>

                                    @endif

                                @endif

                            </ul>
                        </li>
                    @endif

                        <li class="nav-parent
                                {{ ($firstLevel === 'commercial')?'nav-active nav-expanded':'' }}
                                {{ ($firstLevel === 'contract-types')?'nav-active nav-expanded':'' }}
                                ">
                            <a class="nav-link" href="#">
                                    <!-- Icono de currency-exchange -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-exchange" viewBox="0 0 16 16">
                                        <path d="M0 5a5.002 5.002 0 0 0 4.027 4.905 6.46 6.46 0 0 1 .544-2.073C3.695 7.536 3.132 6.864 3 5.91h-.5v-.426h.466V5.05c0-.046 0-.093.004-.135H2.5v-.427h.511C3.236 3.24 4.213 2.5 5.681 2.5c.316 0 .59.031.819.085v.733a3.46 3.46 0 0 0-.815-.082c-.919 0-1.538.466-1.734 1.252h1.917v.427h-1.98c-.003.046-.003.097-.003.147v.422h1.983v.427H3.93c.118.602.468 1.03 1.005 1.229a6.5 6.5 0 0 1 4.97-3.113A5.002 5.002 0 0 0 0 5zm16 5.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0zm-7.75 1.322c.069.835.746 1.485 1.964 1.562V14h.54v-.62c1.259-.086 1.996-.74 1.996-1.69 0-.865-.563-1.31-1.57-1.54l-.426-.1V8.374c.54.06.884.347.966.745h.948c-.07-.804-.779-1.433-1.914-1.502V7h-.54v.629c-1.076.103-1.808.732-1.808 1.622 0 .787.544 1.288 1.45 1.493l.358.085v1.78c-.554-.08-.92-.376-1.003-.787H8.25zm1.96-1.895c-.532-.12-.82-.364-.82-.732 0-.41.311-.719.824-.809v1.54h-.005zm.622 1.044c.645.145.943.38.943.796 0 .474-.37.8-1.02.86v-1.674l.077.018z"/>
                                    </svg>
                                <span>COMERCIAL</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="{{ ($firstLevel === 'commercial')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.commercials.index')}}">
                                        Listado
                                    </a>
                                </li>    
                                <li class="{{ ($firstLevel === 'commercial')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.commercials.participacion.create')}}">
                                        Participación
                                    </a>
                                </li>
                                <li class="{{ ($firstLevel === 'commercial')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.commercials.venta.create')}}">
                                        Venta
                                    </a>
                                </li>
                                <li class="{{ ($firstLevel === 'commercial')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.commercials.renta.create')}}">
                                        Renta
                                    </a>
                                </li>                                                                                            
                            </ul>
                        </li>   


                        <li class="nav-parent
                                {{ ($firstLevel === 'logistic')?'nav-active nav-expanded':'' }}
                                {{ ($firstLevel === 'contract-types')?'nav-active nav-expanded':'' }}
                                ">
                            <a class="nav-link" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
                                    <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                                </svg>
                                <span>LOGÍSTICA</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="{{ ($firstLevel === 'logistic')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.logistics.index')}}">
                                        Listado
                                    </a>
                                </li>
                            </ul>
                        </li>        


                        <li class="nav-parent
                                {{ ($firstLevel === 'contracts')?'nav-active nav-expanded':'' }}
                                {{ ($firstLevel === 'contract-types')?'nav-active nav-expanded':'' }}
                                ">
                            <a class="nav-link" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                <span>CONTRATOS</span>
                            </a>
                            <ul class="nav nav-children">

                                <li class="{{ ($firstLevel === 'contracts')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.contracts.index')}}">
                                        Listado
                                    </a>
                                </li>
                                <li class="{{ ($firstLevel === 'contract-types')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.contract-types.index')}}">
                                        Tipos de contrato
                                    </a>
                                </li>
                            </ul>
                        </li>

                    {{-- POS --}}
                    @if(auth()->user()->type != 'integrator')
                        @if(in_array('pos', $vc_modules))
                            <li class="nav-parent
                        {{ ($firstLevel === 'pos')?'nav-active nav-expanded':'' }}
                            {{ ($firstLevel === 'cash')?'nav-active nav-expanded':'' }}
                                ">
                                <a class="nav-link"
                                   href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-shopping-cart">
                                        <circle cx="9"
                                                cy="21"
                                                r="1"></circle>
                                        <circle cx="20"
                                                cy="21"
                                                r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>POS</span>
                                </a>
                                <ul class="nav nav-children">
                                    @if(in_array('pos', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'pos'  )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.pos.index')}}">Punto de venta</a>
                                        </li>
                                    @endif
                                    @if(in_array('cash', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'cash')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.cash.index')}}">Caja chica POS</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif

                    {{-- Tienda virtual --}}
                    @if(in_array('ecommerce', $vc_modules))
                        <li class="nav-parent
                        {{ in_array($firstLevel, ['ecommerce','items_ecommerce', 'tags', 'promotions', 'orders', 'configuration'])?'nav-active nav-expanded':'' }}"
                        >
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Tienda Virtual</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('ecommerce', $vc_module_levels))
                                    <li class="">
                                        <a class="nav-link"
                                           onclick="window.open( '{{ route("tenant.ecommerce.index") }} ')">Ir a
                                                                                                            Tienda</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_orders', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'orders')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant_orders_index')}}">Pedidos</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_items', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'items_ecommerce')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.items_ecommerce.index')}}">Productos Tienda Virtual</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_tags', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'tags')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.tags.index')}}">Tags - Categorias(Etiquetas)</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_promotions', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'promotions')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.promotion.index')}}">Promociones(Banners)</a>
                                    </li>
                                @endif
                                @if(in_array('ecommerce_settings', $vc_module_levels))
                                    <li class="{{ ($secondLevel === 'configuration')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant_ecommerce_configuration')}}">Configuración</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- Productos --}}
                    @if(in_array('items', $vc_modules))
                        <li class="nav-parent
                        {{ ($firstLevel === 'items')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'services')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'categories')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'brands')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'item-sets')?'nav-active nav-expanded':'' }}
                            ">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-grid">
                                    <rect x="3"
                                          y="3"
                                          width="7"
                                          height="7"></rect>
                                    <rect x="14"
                                          y="3"
                                          width="7"
                                          height="7"></rect>
                                    <rect x="14"
                                          y="14"
                                          width="7"
                                          height="7"></rect>
                                    <rect x="3"
                                          y="14"
                                          width="7"
                                          height="7"></rect>
                                </svg>
                                <span>Productos/Servicios</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('items', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'items')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.items.index')}}">Productos</a>
                                    </li>
                                @endif
                                @if(in_array('items_packs', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'item-sets'  )?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.item_sets.index')}}">Conjuntos/Packs/Promociones</a>
                                    </li>
                                @endif
                                @if(in_array('items_services', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'services')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.services')}}">Servicios</a>
                                    </li>
                                @endif
                                @if(in_array('items_categories', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'categories')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.categories.index')}}">Categorías</a>
                                    </li>
                                @endif
                                @if(in_array('items_brands', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'brands')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.brands.index')}}">Marcas</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- Clientes --}}
                    @if(in_array('persons', $vc_modules))
                        <li class="nav-parent
                        {{ ($firstLevel === 'persons' && $secondLevel === 'customers')?'nav-active nav-expanded':'' }}
                        {{ $firstLevel === 'person-types' ? 'nav-active nav-expanded' : '' }}
                            ">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-user-check">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5"
                                            cy="7"
                                            r="4"></circle>
                                    <polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                                <span>Clientes</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('clients', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'persons' && $secondLevel === 'customers')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.persons.index', ['type' => 'customers'])}}">Clientes</a>
                                    </li>
                                @endif
                                @if(in_array('clients_types', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'person-types')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.person_types.index')}}">Grupos</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(auth()->user()->type != 'integrator')
                        @if(in_array('purchases', $vc_modules))
                            <li class="
                            nav-parent
                            {{ (
	                            $firstLevel === 'purchases' ||
                                ($firstLevel === 'persons' && $secondLevel === 'suppliers') ||
                                $firstLevel === 'expenses' ||
                                $firstLevel === 'bank_loan' ||
                                $firstLevel === 'purchase-quotations' ||
                                $firstLevel === 'fixed-asset'
                                ) ?'nav-active nav-expanded':'' }}
                                ">
                                <a class="nav-link"
                                   href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-shopping-bag">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3"
                                              y1="6"
                                              x2="21"
                                              y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    <span>Compras</span>
                                </a>
                                <ul class="nav nav-children">
                                    @if(in_array('purchases_create', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'purchases' && $secondLevel === 'create')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchases.create')}}">Nueva compra</a>
                                        </li>
                                    @endif
                                        <li class="{{ ($firstLevel === 'purchases' && $secondLevel === 'import')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchases.import')}}">Nueva importación</a>
                                        </li>
                                        <li class="{{ ($firstLevel === 'purchases' && $secondLevel === 'import-index')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchases.import-index')}}">Listado importaciones</a>
                                        </li>
                                    @if(in_array('purchases_list', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'purchases' && !in_array($secondLevel, ['create', 'import', 'import-index']))?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchases.index')}}">Listado</a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_orders', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'purchase-orders')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.purchase-orders.index')}}">Ordenes de compra</a>
                                        </li>
                                    @endif
                                        @if(in_array('purchases_expenses', $vc_module_levels))
                                            <li class="{{ ($firstLevel === 'bank_loan' )?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.bank_loan.index')}}">Credito Bancario</a>
                                            </li>
                                        @endif
                                    @if(in_array('purchases_expenses', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'expenses' )?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.expenses.index')}}">Gastos diversos</a>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_quotations', $vc_module_levels) || in_array('purchases_suppliers', $vc_module_levels))
                                        <li class="nav-parent
                                    {{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'nav-active nav-expanded':'' }}
                                        {{ ($firstLevel === 'purchase-quotations')?'nav-active nav-expanded':'' }}
                                            ">
                                            <a class="nav-link"
                                               href="#">
                                                Proveedores
                                            </a>
                                            <ul class="nav nav-children">
                                                @if(in_array('purchases_suppliers', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'persons' && $secondLevel === 'suppliers')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.persons.index', ['type' => 'suppliers'])}}">Listado</a>
                                                    </li>
                                                @endif
                                                @if(in_array('purchases_quotations', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'purchase-quotations')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.purchase-quotations.index')}}">Solicitar
                                                                                                                cotización</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if(in_array('purchases_fixed_assets_purchases', $vc_module_levels) || in_array('purchases_fixed_assets_items', $vc_module_levels))
                                        <li class="nav-parent {{ ($firstLevel === 'fixed-asset' )?'nav-active nav-expanded' : '' }}">
                                            <a class="nav-link"
                                               href="#">Activos fijos</a>
                                            <ul class="nav nav-children">
                                                @if(in_array('purchases_fixed_assets_items', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'fixed-asset' && $secondLevel === 'items')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.fixed_asset_items.index')}}">Ítems</a>
                                                    </li>
                                                @endif
                                                @if(in_array('purchases_fixed_assets_purchases', $vc_module_levels))
                                                    <li class="{{ ($firstLevel === 'fixed-asset' && $secondLevel === 'purchases')?'nav-active':'' }}">
                                                        <a class="nav-link"
                                                           href="{{route('tenant.fixed_asset_purchases.index')}}">Compras</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        {{-- Inventario --}}
                        @if(in_array('inventory', $vc_modules))
                            <li class="nav-parent
                            {{ (in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions', 'extra_info_items', 'item-lots']) |($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex'])))?'nav-active nav-expanded':'' }}
                                ">
                                <a class="nav-link"
                                   href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-archive">
                                        <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                        <rect x="1"
                                              y="3"
                                              width="22"
                                              height="5"></rect>
                                        <line x1="10"
                                              y1="12"
                                              x2="14"
                                              y2="12"></line>
                                    </svg>
                                    <span>Inventario</span>
                                </a>
                                <ul class="nav nav-children">
                                    @if(in_array('items_lots', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'item-lots' && !$secondLevel)?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('tenant.item-lots.index')}}">Series</a>
                                        </li>
                                    @endif
                                        @if(in_array('items_lots', $vc_module_levels))
                                            <li class="{{ ($secondLevel === 'report-index')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.item-lots.report')}}">Reporte series </a>
                                            </li>
                                        @endif
                                    @if(in_array('inventory', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'inventory')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('inventory.index')}}">Movimientos</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_transfers', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'transfers')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('transfers.index')}}">Traslados</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_devolutions', $vc_module_levels))
                                        <li class="{{ ($firstLevel === 'devolutions')?'nav-active':'' }}">
                                            <a class="nav-link"
                                               href="{{route('devolutions.index')}}">Devoluciones</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report_kardex', $vc_module_levels))
                                        <li class="{{(($firstLevel === 'reports') && ($secondLevel === 'kardex')) ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('reports.kardex.index')}}">Reporte Kardex</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report', $vc_module_levels))
                                        <li class="{{(($firstLevel === 'reports') && ($secondLevel == 'inventory')) ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('reports.inventory.index')}}">Reporte Inventario</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_report_kardex', $vc_module_levels))
                                        {{-- <li class="{{ ($firstLevel === 'warehouses')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('warehouses.index')}}">Almacenes</a>
                                        </li> --}}
                                        <li class="{{(($firstLevel === 'reports') && ($secondLevel === 'valued-kardex')) ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('reports.valued_kardex.index')}}">Kardex valorizado</a>
                                        </li>
                                    @endif
                                    @if(in_array('inventory_item_extra_data', $vc_module_levels) && $configuration->isShowExtraInfoToItem())
                                        <li class="{{($firstLevel === 'extra_info_items') ? 'nav-active' : ''}}">
                                            <a class="nav-link"
                                               href="{{route('extra_info_items.index')}}">Datos extra de items</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                    @endif

                    @if(in_array('establishments', $vc_modules))
                        <li class="nav-parent {{ in_array($firstLevel, ['users', 'establishments'])?'nav-active nav-expanded':'' }}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-users">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9"
                                            cy="7"
                                            r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Usuarios/Locales & Series</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(in_array('users', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'users')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.users.index')}}">Usuarios</a>
                                    </li>
                                @endif
                                @if(in_array('users_establishments', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'establishments')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.establishments.index')}}">Establecimientos</a>
                                    </li>
                                @endif
                                @if(in_array('users_warehouses', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'warehouses')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('warehouses.index')}}">Almacenes</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(in_array('advanced', $vc_modules) && $vc_company->soap_type_id != '03')
                        <li class="
                        nav-parent
                        {{ ($firstLevel === 'retentions')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'dispatches')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'perceptions')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'drivers')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'dispatchers')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'order-forms')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'purchase-settlements')?'nav-active nav-expanded':'' }}
                        {{ ($firstLevel === 'documents_old')?'nav-active nav-expanded':'' }}

                            ">
                            <a class="nav-link"
                               href="#">
                                <i class="fas fa-receipt"
                                   aria-hidden="true"></i>
                                <span>Comprobantes avanzados</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(in_array('advanced_retentions', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'retentions')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.retentions.index')}}">Retenciones</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_dispatches', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'dispatches')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.dispatches.index')}}">Guías de remisión</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_perceptions', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'perceptions')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.perceptions.index')}}">Percepciones</a>
                                    </li>
                                @endif

                                    <li class="{{ ($firstLevel === 'documents_old'  && $secondLevel === 'create_old')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.documents.create_old')}}">
                                            Facturas pasadas
                                        </a>
                                    </li>

                                @if(in_array('advanced_purchase_settlements', $vc_module_levels))
                                    <li class="{{ ($firstLevel === 'purchase-settlements')?'nav-active':'' }}">
                                        <a class="nav-link"
                                           href="{{route('tenant.purchase-settlements.index')}}">Liquidaciones de
                                                                                                 compra</a>
                                    </li>
                                @endif
                                @if(in_array('advanced_order_forms', $vc_module_levels))
                                    <li class="nav-parent
                                {{ ($firstLevel === 'order-forms')?'nav-active nav-expanded':'' }}
                                    {{ ($firstLevel === 'drivers')?'nav-active nav-expanded':'' }}
                                    {{ ($firstLevel === 'dispatchers')?'nav-active nav-expanded':'' }}
                                        ">
                                        <a class="nav-link"
                                           href="#">Ordenes de pedido</a>
                                        <ul class="nav nav-children">
                                            <li class="{{ ($firstLevel === 'order-forms')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.order_forms.index')}}">Listado</a>
                                            </li>
                                            <li class="{{ ($firstLevel === 'drivers')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.order_forms.drivers.index')}}">Conductores</a>
                                            </li>
                                            <li class="{{ ($firstLevel === 'dispatchers')?'nav-active':'' }}">
                                                <a class="nav-link"
                                                   href="{{route('tenant.order_forms.dispatchers.index')}}">Transportistas</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(in_array('reports', $vc_modules))
                        <li class="{{  ($firstLevel === 'reports' && in_array($secondLevel, ['purchases', 'search','sales','customers','items', 'general-items','consistency-documents', 'quotations', 'sale-notes','cash','commissions','document-hotels', 'validate-documents', 'document-detractions','commercial-analysis', 'order-notes-consolidated', 'order-notes-general', 'sales-consolidated', 'user-commissions', 'fixed-asset-purchases', 'massive-downloads'])) ? 'nav-active' : ''}} {{ $firstLevel === 'list-reports' ? 'nav-active' : '' }}">
                            <a class="nav-link"
                               href="{{ url('list-reports') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-pie-chart">
                                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                </svg>
                                <span>Reportes</span>
                            </a>
                        </li>
                    @endif

                    @if(in_array('accounting', $vc_modules))
                        <li class="nav-parent {{ ($firstLevel === 'account' || $firstLevel === 'accounting_ledger'  )?'nav-active nav-expanded':'' }}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-bar-chart-2">
                                    <line x1="18"
                                          y1="20"
                                          x2="18"
                                          y2="10"></line>
                                    <line x1="12"
                                          y1="20"
                                          x2="12"
                                          y2="4"></line>
                                    <line x1="6"
                                          y1="20"
                                          x2="6"
                                          y2="14"></line>
                                </svg>
                                <span>Contabilidad</span>
                            </a>
                            <ul class="nav nav-children"
                                style="">
                                @if(in_array('account_report', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'account') && ($secondLevel === 'format')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.account_format.index') }}">Exportar reporte</a>
                                    </li>
                                @endif
                                @if(in_array('account_formats', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'account') && ($secondLevel == ''))   ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.account.index') }}">Exportar formatos - Sis.
                                                                                      Contable</a>
                                    </li>
                                @endif
                                @if(in_array('account_summary', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'account') && ($secondLevel == 'summary-report'))   ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.account_summary_report.index') }}">Reporte resumido -
                                                                                                     Ventas</a>
                                    </li>
                                @endif
                                <li class="{{(($firstLevel === 'accounting_ledger') )   ? 'nav-active' : ''}}">
                                    <a class="nav-link"
                                       href="{{ route('tenant.accounting_ledger.create') }}">
                                        Libro Mayor
                                    </a>
                                </li>

                                    <li class="{{(($firstLevel === 'account') && ($secondLevel == 'ple'))   ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{ route('tenant.account_ple.index') }}">
                                            PLE Vers. 5.2.0 2021
                                        </a>
                                    </li>

                                    <li class="{{(($firstLevel === 'account') && ($secondLevel == 'tributo'))   ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{ route('tenant.account_tributo.index') }}">
                                            Cuadro de tributos
                                        </a>
                                    </li>
                            </ul>
                        </li>
                    @endif

                    @if(in_array('finance', $vc_modules))

                        <li class="nav-parent {{ $firstLevel === 'finances' && in_array($secondLevel, [
                                                	'global-payments',
                                                	 'balance',
                                                	 'payment-method-types',
                                                	 'unpaid',
                                                	 'to-pay',
                                                	 'income',
                                                	 'transactions',
                                                	 'movements'
                                                	 ]) ? 'nav-active nav-expanded' : ''}}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-dollar-sign">
                                    <line x1="12"
                                          y1="1"
                                          x2="12"
                                          y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                <span>Finanzas</span>
                            </a>
                            <ul class="nav nav-children">

                                @if(in_array('finances_movements', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'movements')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.movements.index')}}">Movimientos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_movements', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'transactions')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.transactions.index')}}">Transacciones</a>
                                    </li>
                                @endif
                                @if(in_array('finances_incomes', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'income')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.income.index')}}">Ingresos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_unpaid', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'unpaid')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.unpaid.index')}}">Cuentas por cobrar</a>
                                    </li>
                                @endif
                                @if(in_array('finances_to_pay', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'to-pay')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.to_pay.index')}}">Cuentas por pagar</a>
                                    </li>
                                @endif
                                @if(in_array('finances_payments', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'global-payments')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.global_payments.index')}}">Pagos</a>
                                    </li>
                                @endif
                                @if(in_array('finances_balance', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'balance')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.balance.index')}}">Balance</a>
                                    </li>
                                @endif
                                @if(in_array('finances_payment_method_types', $vc_module_levels))
                                    <li class="{{(($firstLevel === 'finances') && ($secondLevel == 'payment-method-types')) ? 'nav-active' : ''}}">
                                        <a class="nav-link"
                                           href="{{route('tenant.finances.payment_method_types.index')}}">Ingresos y
                                                                                                          Egresos - M.
                                                                                                          Pago</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(in_array('configuration', $vc_modules))
                        <li class="{{in_array($firstLevel, ['list-platforms', 'list-cards', 'list-currencies', 'list-bank-accounts', 'list-banks', 'list-attributes', 'list-detractions', 'list-units', 'list-payment-methods', 'list-incomes', 'list-payments', 'company_accounts', 'list-vouchers-type',     'companies', 'advanced', 'tasks', 'inventories','bussiness_turns','offline-configurations','series-configurations','configurations', 'login-page', 'list-settings']) ? 'nav-active' : ''}}">
                            <a class="nav-link"
                               href="{{ url('list-settings') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-settings">
                                    <circle cx="12"
                                            cy="12"
                                            r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                                <span>Configuración</span>
                            </a>
                        </li>
                    @endif

                    {{-- @if(in_array('cuenta', $vc_modules))
                    <li class=" nav-parent
                        {{ ($firstLevel === 'cuenta')?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-dollar-sign" aria-hidden="true"></i>
                            <span>Mis Pagos</span>
                        </a>
                        <ul class="nav nav-children">
                            @if(in_array('account_users_settings', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'cuenta') && ($secondLevel === 'configuration')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.configuration.index')}}">Configuracion</a>
                            </li>
                            @endif
                            @if(in_array('account_users_list', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'cuenta') && ($secondLevel === 'payment_index')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.payment.index')}}">Lista de Pagos</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif --}}
                    {{-- @if(in_array('hotels', $vc_modules) || in_array('documentary-procedure', $vc_modules))
                    <li class="nav-description">Módulos extras</li>
                    @endif --}}
                    @if(in_array('hotels', $vc_modules))
                        <li class=" nav-parent {{ ($firstLevel === 'hotels') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <i class="fas fa-building"
                                   aria-hidden="true"></i>
                                <span>Hoteles</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('hotels_reception', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'reception')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/reception') }}">Recepción</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_rates', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'rates')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/rates') }}">Tarifas</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_floors', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'floors')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/floors') }}">Pisos</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_cats', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'categories')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/categories') }}">Categorías</a>
                                    </li>
                                @endif
                                @if(in_array('hotels_rooms', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'hotels') && ($secondLevel === 'rooms')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ url('hotels/rooms') }}">Habitaciones</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(in_array('documentary-procedure', $vc_modules))
                        <li class=" nav-parent {{ ($firstLevel === 'documentary-procedure') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-folder">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span>Trámite documentario</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('documentary_offices', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'offices')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.offices') }}">Listado de Etapas</a>
                                    </li>
                                @endif
                                @if(in_array('documentary_requirements', $vc_module_levels))

                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'requirements')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.requirements') }}">Listado de requerimientos</a>
                                    </li>

                                @endif
                                @if(in_array('documentary_process', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'processes')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.processes') }}">Tipos de tramites</a>
                                    </li>
                                @endif
                                {{--
                            @if(in_array('documentary_documents', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'documents')) ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{ route('documentary.documents') }}">Tipos de Documento</a>
                            </li>
                            @endif
                            @if(in_array('documentary_actions', $vc_module_levels))
                            <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'actions')) ? 'nav-active' : '' }}">
                                <a class="nav-link" href="{{ route('documentary.actions') }}">Acciones</a>
                            </li>
                            @endif
                                --}}
                                @if(in_array('documentary_files', $vc_module_levels))
                                    <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'files')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('documentary.files') }}">Listado de tramites</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- DIGEMID --}}
                    @if(in_array('digemid', $vc_modules) && $configuration->isPharmacy())
                        <li class=" nav-parent {{ ($firstLevel === 'digemid') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link"
                               href="#">
                                <i class="fa fas fa-ambulance"
                                   aria-hidden="true"></i>
                                <span>Farmacia</span>
                            </a>
                            <ul class="nav nav-children">
                                @if(in_array('digemid', $vc_module_levels))
                                    {{-- <li class="{{ (($firstLevel === 'documentary-procedure') && ($secondLevel === 'offices')) ? 'nav-active' : '' }}">
                                        <a class="nav-link" href="{{ route('documentary.offices') }}">Oficinas</a>
                                    </li> --}}
                                    <li class="{{ (($firstLevel === 'digemid') && ($secondLevel === 'digemid')) ? 'nav-active' : '' }}">
                                        <a class="nav-link"
                                           href="{{ route('tenant.digemid.index') }}">Productos</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- DIGEMID --}}
                    @if(in_array('apps', $vc_modules))
                        <li class="">
                            <a class="nav-link"
                               href="{{url('list-extras')}}">
                                <i class="fas fa-cube"></i>
                                <span>Apps</span>
                            </a>
                        </li>
                    @endif

                    {{-- TRANSPORTE --}}
                    @if(in_array('transporte', $vc_modules))
                        <li class=" nav-parent
                    {{ ($firstLevel === 'transportes') ? 'nav-active nav-expanded' : '' }}">
                            <a class="nav-link" href="#">
                                <i class="fas fa-building" aria-hidden="true"></i>
                                <span>Transportes</span>
                        </a>
                            <ul class="nav nav-children">
                                <li class="{{ ($firstLevel === 'transportes' && ($secondLevel === 'cash') )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.transportes.cash.index')}}">Caja chica</a>
                                </li>
                                <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'choferes')) ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ url('transportes/choferes') }}">Conductores</a>
                                </li>
                                <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'vehiculos')) ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ url('transportes/vehiculos') }}">Vehículos</a>
                                </li>
                                <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'destinos')) ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ url('transportes/destinos') }}">Ciudades</a>
                                </li>
                                <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'terminales')) ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ url('transportes/terminales') }}">Terminales</a>
                                </li>

                                <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'pasajes' || $secondLevel === 'sales')) ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ url('transportes/pasajes') }}">Pasajes</a>
                                </li>
                                <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'encomiendas')) ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ url('transportes/encomiendas') }}">Encomiendas</a>
                                </li>
                                <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'manifiestos')) ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ url('transportes/manifiestos') }}">Manifiestos</a>
                                </li>

                                @if(auth()->user()->type == 'admin')
                                    <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'programaciones')) ? 'nav-active' : '' }}">
                                        <a class="nav-link" href="{{ url('transportes/programaciones') }}">Programaciones</a>
                                    </li>
                                    <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'reportes')) ? 'nav-active' : '' }}">
                                        <a class="nav-link" href="{{ url('transportes/reportes') }}">Reportes</a>
                                    </li>
                                    <li class="{{ (($firstLevel === 'transportes') && ($secondLevel === 'usuarios-terminales')) ? 'nav-active' : '' }}">
                                        <a class="nav-link" href="{{ url('transportes/usuarios-terminales') }}">Usuarios terminales</a>
                                    </li>
                                @endif
                            </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    sidebarLeft.scrollTop = initialPosition;
                }
            }

            function shadeColor(color, percent) {

                var R = parseInt(color.substring(1,3),16);
                var G = parseInt(color.substring(3,5),16);
                var B = parseInt(color.substring(5,7),16);

                R = parseInt(R * (100 + percent) / 100);
                G = parseInt(G * (100 + percent) / 100);
                B = parseInt(B * (100 + percent) / 100);

                R = (R<255)?R:255;
                G = (G<255)?G:255;
                B = (B<255)?B:255;

                var RR = ((R.toString(16).length==1)?"0"+R.toString(16):R.toString(16));
                var GG = ((G.toString(16).length==1)?"0"+G.toString(16):G.toString(16));
                var BB = ((B.toString(16).length==1)?"0"+B.toString(16):B.toString(16));

                return "#"+RR+GG+BB;
            }

            function colorRGB2Hex(color) {
                var rgb = color.split(',');
                var r = parseInt(rgb[0].split('(')[1]);
                var g = parseInt(rgb[1]);
                var b = parseInt(rgb[2].split(')')[0]);

                var hex = "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
                return hex;
            }

            let element = document.getElementById('sidebar-left');
            let elementStyle = window.getComputedStyle(element);
            let elementColor = elementStyle.getPropertyValue('background-color');

            var colorhexa=colorRGB2Hex(elementColor);

            console.log(colorhexa);

            var colorobscuro= shadeColor(colorhexa,-20);

            console.log(colorobscuro);

            var etiquetaStyle = document.createElement("style");
            var estilos = document.createTextNode(".nav-children{background:"+colorobscuro+" !important;}");

            etiquetaStyle.appendChild(estilos);

            document.head.appendChild(etiquetaStyle);

        </script>
    </div>
</aside>
