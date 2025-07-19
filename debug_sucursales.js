/**
 * SCRIPT DE DEBUG PARA SUCURSALES
 * Para usar: Copia y pega este código en la consola del navegador
 * mientras estés en la página vistas/sucursales.php
 */

console.log('🔧 Iniciando debug de sucursales...');

// Función para probar la verificación de duplicados
window.testVerificarDuplicados = async function(codigo, nombre, id = null) {
    console.log('🧪 Probando verificación de duplicados:', { codigo, nombre, id });
    
    try {
        const response = await fetch('ajax/sucursales_ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                accion: 'verificar_duplicados',
                codigo: codigo,
                nombre: nombre,
                id: id || ''
            })
        });
        
        const text = await response.text();
        console.log('📥 Respuesta cruda:', text);
        
        try {
            const json = JSON.parse(text);
            console.log('📊 Respuesta parseada:', json);
            return json;
        } catch (e) {
            console.error('❌ Error al parsear JSON:', e);
            return { valid: false, message: 'Error al parsear respuesta' };
        }
    } catch (error) {
        console.error('❌ Error en la petición:', error);
        return { valid: false, message: 'Error de conexión' };
    }
};

// Función para probar guardar sucursal
window.testGuardarSucursal = async function(datos) {
    console.log('💾 Probando guardar sucursal:', datos);
    
    const formData = new FormData();
    formData.append('accion', 'guardar');
    
    Object.keys(datos).forEach(key => {
        formData.append(key, datos[key]);
    });
    
    try {
        const response = await fetch('ajax/sucursales_ajax.php', {
            method: 'POST',
            body: formData
        });
        
        const text = await response.text();
        console.log('📥 Respuesta cruda:', text);
        
        try {
            const json = JSON.parse(text);
            console.log('📊 Respuesta parseada:', json);
            return json;
        } catch (e) {
            console.error('❌ Error al parsear JSON:', e);
            return { estado: 'error', mensaje: 'Error al parsear respuesta' };
        }
    } catch (error) {
        console.error('❌ Error en la petición:', error);
        return { estado: 'error', mensaje: 'Error de conexión' };
    }
};

// Función para probar listar sucursales
window.testListarSucursales = async function() {
    console.log('📋 Probando listar sucursales...');
    
    try {
        const response = await fetch('ajax/sucursales_ajax.php?accion=listar');
        const text = await response.text();
        console.log('📥 Respuesta cruda:', text);
        
        try {
            const json = JSON.parse(text);
            console.log('📊 Respuesta parseada:', json);
            return json;
        } catch (e) {
            console.error('❌ Error al parsear JSON:', e);
            return { estado: 'error', mensaje: 'Error al parsear respuesta' };
        }
    } catch (error) {
        console.error('❌ Error en la petición:', error);
        return { estado: 'error', mensaje: 'Error de conexión' };
    }
};

// Función para verificar elementos DOM
window.verificarElementos = function() {
    console.log('🔍 Verificando elementos DOM...');
    
    const elementos = {
        modal: $('#modal-sucursal'),
        form: $('#form-sucursal'),
        btnGuardar: $('#btn-guardar-sucursal'),
        btnNuevo: $('button[data-target="#modal-sucursal"]'),
        tabla: $('#tabla-sucursales'),
        campoNombre: $('#nombre'),
        campoCodigo: $('#codigo'),
        campoEstado: $('#estado')
    };
    
    Object.keys(elementos).forEach(key => {
        const elemento = elementos[key];
        console.log(`${key}: ${elemento.length > 0 ? '✅ Encontrado' : '❌ No encontrado'}`);
        if (elemento.length > 0 && key.startsWith('campo')) {
            console.log(`  Valor actual: "${elemento.val()}"`);
        }
    });
    
    return elementos;
};

// Función para ejecutar pruebas automatizadas
window.ejecutarPruebas = async function() {
    console.log('🚀 Ejecutando pruebas automatizadas...');
    
    // Prueba 1: Verificar elementos DOM
    console.log('\n--- Prueba 1: Elementos DOM ---');
    verificarElementos();
    
    // Prueba 2: Listar sucursales
    console.log('\n--- Prueba 2: Listar sucursales ---');
    await testListarSucursales();
    
    // Prueba 3: Verificar duplicados con datos nuevos
    console.log('\n--- Prueba 3: Verificar duplicados (datos nuevos) ---');
    await testVerificarDuplicados('TEST001', 'Sucursal Test');
    
    // Prueba 4: Verificar duplicados con datos vacíos
    console.log('\n--- Prueba 4: Verificar duplicados (datos vacíos) ---');
    await testVerificarDuplicados('', '');
    
    // Prueba 5: Verificar duplicados con datos null
    console.log('\n--- Prueba 5: Verificar duplicados (datos null) ---');
    await testVerificarDuplicados(null, null);
    
    console.log('\n✅ Pruebas completadas');
};

// Instrucciones de uso
console.log(`
🔧 FUNCIONES DISPONIBLES:
• testVerificarDuplicados(codigo, nombre, id) - Probar verificación de duplicados
• testGuardarSucursal(datos) - Probar guardar sucursal
• testListarSucursales() - Probar listar sucursales
• verificarElementos() - Verificar elementos DOM
• ejecutarPruebas() - Ejecutar todas las pruebas

📖 EJEMPLOS DE USO:
• testVerificarDuplicados('SUC001', 'Sucursal Central')
• testGuardarSucursal({nombre: 'Test', codigo: 'TEST', estado: 'activa'})
• ejecutarPruebas()
`); 