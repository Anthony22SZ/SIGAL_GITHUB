console.log('app.js cargado');


import './bootstrap';
import 'flowbite';

// Guardar el fetch original
const originalFetch = window.fetch;

// Sobrescribir el método fetch global
window.fetch = async (url, options = {}) => {
    const externalRoutes = [
        '/login',
        '/logout',
        '/solicitar-recuperacion',
        '/verificar-codigo',
        '/cambiar-contrasena',
        '/perfil',
        '/EMPLEADOS',
        '/empleados',
        '/eliminar-empleado',
        '/actualizar-empleado',
        '/proveedores',
        '/eliminar-proveedor',
        '/actualizar-proveedor',
        '/materiales',
        '/eliminar-material',
        '/salida-material',
        '/facturas-compra',
        '/facturas',
        '/facturas-compraCompleta',
        '/detalle-compra',
        '/ROLES',
        '/roles',
        '/inventario',
        '/OBJETOS',
        '/objetos',
        '/ACCESOS',
        '/permisos',
        '/usuarios',
        '/clientes',
        '/eliminar-cliente',
        '/actualizar-cliente',
        '/categorias',
        '/sucursales',
        '/actualizar-sucursal',
        '/estado-sucursal',
        '/estadisticas',
        '/productos',
        '/inventario/productos',
        '/inventario-productos',
        '/traslados',
        '/api/facturas',
        '/api/empresa',
        '/api/puntos-emision',
        '/api/tipos-documento',
        '/api/cai'
    ];

    const isAbsoluteUrl = /^https?:\/\//i.test(url);
    const isExternalRoute = externalRoutes.some(route => url.includes(route));

    let finalUrl = url;

    if (!isAbsoluteUrl && !isExternalRoute) {
        finalUrl = `${window.APP_URL}${url.startsWith('/') ? '' : '/'}${url}`;
    } else if (isExternalRoute) {
        finalUrl = `${window.API_URL}${url.startsWith('/') ? '' : '/'}${url}`;
    }

    const defaultOptions = {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'include',
    };
    const mergedOptions = { ...defaultOptions, ...options };

    if (mergedOptions.headers['Authorization']) {
        delete mergedOptions.credentials;
    }

    try {
        const response = await originalFetch(finalUrl, mergedOptions);
        if (!response.ok) {
            const text = await response.text();
            throw new Error(`Error ${response.status}: ${text}`);
        }
        return response;
    } catch (error) {
        console.error(`Error en fetch (${finalUrl}):`, error);
        throw error;
    }
};