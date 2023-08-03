
function translateInvoiceType(invoiceTypeString) {
    const translateMap = {
        lading: 'Flete',
        insurance: 'Seguro',
        commission: 'Comisión agente de aduana',
        forklift: 'Montacarga',
        quadrille: 'Cuadrilla',
        local_transport: 'Transporte local',
        gate_in: 'Gate in',
        thc: 'THC',
        bl_emission: 'Emisión de BL',
        warehouse_discharge: 'Descarga / Almacén',
        overstay: 'Sobreestadía',
        special_service: 'Servicio Extraordinario',
        others: 'Otros',
    }

    return translateMap[invoiceTypeString] ? translateMap[invoiceTypeString] : 'Compras';
}
export {translateInvoiceType }
