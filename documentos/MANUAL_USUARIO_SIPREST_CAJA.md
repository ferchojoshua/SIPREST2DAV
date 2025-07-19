# 📖 MANUAL DE USUARIO - SISTEMA DE CAJA SIPREST
**Version 2.0 - Enero 2025**  
**Desarrollado por: Developer Senior**

---

## 🎯 **TABLA DE CONTENIDOS**

1. [Introducción al Sistema](#introducción-al-sistema)
2. [Configuración Inicial](#configuración-inicial)
3. [Apertura de Caja - Proceso Paso a Paso](#apertura-de-caja)
4. [Operaciones Diarias de Caja](#operaciones-diarias)
5. [Gestión de Ingresos y Egresos](#gestión-de-ingresos-y-egresos)
6. [Proceso de Creación de Facturas](#proceso-de-creación-de-facturas)
7. [Cierre de Caja](#cierre-de-caja)
8. [Dashboard y Reportes](#dashboard-y-reportes)
9. [Gestión Multi-Sucursal](#gestión-multi-sucursal)
10. [Solución de Problemas](#solución-de-problemas)
11. [Mejores Prácticas](#mejores-prácticas)

---

## 🏢 **INTRODUCCIÓN AL SISTEMA**

### **¿Qué es SIPREST?**
SIPREST es un **Sistema Integral de Préstamos** con módulo de caja empresarial que permite:

- ✅ **Control Total de Caja**: Apertura, operaciones y cierre diario
- ✅ **Gestión Multi-Sucursal**: Múltiples cajas por sucursal
- ✅ **Auditoría Completa**: Registro detallado de todas las operaciones
- ✅ **Alertas Inteligentes**: Notificaciones automáticas de discrepancias
- ✅ **Reportes en Tiempo Real**: Dashboard ejecutivo y operativo

### **Flujo General del Sistema**
```
1. CONFIGURACIÓN → 2. APERTURA → 3. OPERACIONES → 4. CIERRE → 5. REPORTES
      ↓                ↓              ↓              ↓           ↓
  Cajas/Sucursales   Saldo Inicial   Ingresos/     Arqueo     Dashboard
                                     Egresos       Físico
```

---

## ⚙️ **CONFIGURACIÓN INICIAL**

### **PASO 1: Configurar Sucursales**
**📍 Ruta:** `Mantenimiento → Sucursales`

1. **Crear Sucursal Nueva:**
   - Clic en **"Nueva Sucursal"**
   - Completar datos obligatorios:
     - **Código:** SUC-001 (formato recomendado)
     - **Nombre:** Sucursal Centro
     - **Dirección:** Dirección completa
     - **Teléfono:** Número de contacto
     - **Estado:** Activa

2. **Validaciones del Sistema:**
   - ❌ No se permiten códigos duplicados
   - ❌ Nombre debe ser único por región
   - ✅ Validación automática de campos

### **PASO 2: Configurar Cajas por Sucursal**
**📍 Ruta:** `Caja → Configurar Cajas por Sucursal`

1. **Acceder al Modal de Configuración:**
   ```
   Menú → Caja → [Botón] Configurar Cajas por Sucursal
   ```

2. **Crear Nueva Caja:**
   - **Sucursal:** Seleccionar desde dropdown
   - **Nombre Caja:** "Caja Principal" o "Ventanilla 1"
   - **Responsable:** Asignar usuario (opcional)
   - **Saldo Inicial:** Monto en córdobas (C$)
   - **Descripción:** Ubicación o notas adicionales

3. **Configuraciones Recomendadas:**
   ```
   📊 Sucursal Principal:
   ├── Caja Principal (Gerencia)
   ├── Ventanilla 1 (Atención al cliente)
   └── Ventanilla 2 (Cobranza)
   
   📊 Sucursales Secundarias:
   ├── Caja Única (Operaciones generales)
   └── Caja Auxiliar (Respaldo)
   ```

### **PASO 3: Permisos de Usuario**
**📍 Ruta:** `Mantenimiento → Usuarios`

**Permisos por Perfil:**
- **👤 Administrador:** Acceso total a todas las cajas
- **👤 Cajero:** Solo su caja asignada
- **👤 Supervisor:** Múltiples cajas de su sucursal
- **👤 Gerente:** Vista consolidada y reportes

---

## 💰 **APERTURA DE CAJA - PROCESO PASO A PASO**

### **PASO 1: Acceso al Módulo**
**📍 Ruta:** `Caja → Aperturar Caja`

### **PASO 2: Proceso de Apertura Estándar**

1. **Completar Formulario de Apertura:**
   ```
   📝 Datos Obligatorios:
   ├── Fecha/Hora: [Auto-completado]
   ├── Monto Inicial: C$ [Ingreso manual]
   ├── Observaciones: [Opcional]
   └── Responsable: [Usuario actual]
   ```

2. **Validaciones Automáticas:**
   - ✅ **Verificación de Saldo:** Sistema valida el monto inicial
   - ✅ **Estado Previo:** Confirma que la caja esté cerrada
   - ✅ **Permisos:** Valida autorización del usuario

3. **Confirmación de Apertura:**
   ```
   🎯 Resultado Exitoso:
   "Caja abierta correctamente"
   Estado: VIGENTE
   Saldo Inicial: C$ [Monto]
   Responsable: [Usuario]
   ```

### **PASO 3: Apertura Avanzada por Sucursal**

1. **Seleccionar Sucursal:**
   - Dropdown con sucursales disponibles
   - Filtro automático según permisos del usuario

2. **Configuración Avanzada:**
   ```
   🔧 Opciones Avanzadas:
   ├── Sucursal: [Dropdown dinámico]
   ├── Tipo de Caja: [Principal/Auxiliar]
   ├── Monto Base: [Según políticas]
   ├── Moneda: [Córdobas/Dólares]
   └── Observaciones: [Campo libre]
   ```

3. **Proceso de Validación:**
   - **Verificación de Horario:** Solo en horario laboral
   - **Validación de Monto:** Según políticas establecidas
   - **Confirmación Dual:** Para montos altos

---

## 📊 **OPERACIONES DIARIAS DE CAJA**

### **Tipos de Operaciones Disponibles**

#### **1. INGRESOS**
**📍 Ruta:** `Caja → Ingresos / Egresos`

**Tipos de Ingresos:**
```
💵 PAGO DE PRÉSTAMOS:
├── Capital + Intereses
├── Pagos adelantados
└── Cancelaciones totales

💵 INGRESOS EXTRAORDINARIOS:
├── Depósitos adicionales
├── Transferencias entre cajas
└── Correcciones de saldo
```

**Proceso de Registro:**
1. **Seleccionar Tipo:** "INGRESO"
2. **Elegir Concepto:** Dropdown con opciones predefinidas
3. **Ingresar Monto:** Validación automática
4. **Agregar Descripción:** Detalle de la operación
5. **Confirmar:** Sistema actualiza saldo automáticamente

#### **2. EGRESOS**
**Tipos de Egresos:**
```
💸 DESEMBOLSOS:
├── Préstamos aprobados
├── Gastos operativos
└── Transferencias bancarias

💸 GASTOS ADMINISTRATIVOS:
├── Pagos a proveedores
├── Servicios básicos
└── Gastos de oficina
```

**Validaciones de Egresos:**
- ❌ **No exceder saldo disponible**
- ❌ **Requerir autorización para montos altos**
- ✅ **Registro obligatorio de beneficiario**

#### **3. TRANSFERENCIAS ENTRE CAJAS**
```
🔄 PROCESO DE TRANSFERENCIA:
1. Caja Origen → Registra EGRESO
2. Sistema → Genera comprobante
3. Caja Destino → Registra INGRESO
4. Validación → Cuadre automático
```

---

## 🧾 **PROCESO DE CREACIÓN DE FACTURAS**

### **CONTEXTO: Facturación en Sistema de Préstamos**

En SIPREST, las "facturas" se refieren a **comprobantes de pago** y **documentos de préstamo**:

#### **1. COMPROBANTES DE PAGO DE CUOTAS**
**📍 Ruta:** `Préstamos → Administrar Préstamos → [Cliente] → Pagar Cuota`

```
📋 PROCESO DE FACTURACIÓN DE CUOTAS:

PASO 1: Seleccionar Cliente
├── Buscar por nombre/DNI
├── Verificar préstamos activos
└── Seleccionar préstamo específico

PASO 2: Calcular Pago
├── Capital pendiente: C$ [Monto]
├── Intereses acumulados: C$ [Monto] 
├── Mora (si aplica): C$ [Monto]
├── Total a pagar: C$ [Monto]
└── Aplicar descuentos (si autorizado)

PASO 3: Procesar Pago
├── Método: [Efectivo/Transferencia]
├── Monto recibido: C$ [Monto]
├── Cambio: C$ [Monto]
└── Generar comprobante automático

PASO 4: Comprobante Generado
├── Número de recibo: AUTO-001-2025
├── Fecha/Hora: [Timestamp]
├── Cliente: [Nombre completo]
├── Préstamo: [Código]
├── Detalle de pago: [Desglose]
├── Firma digital: [Sistema]
└── Estado: PAGADO
```

#### **2. COMPROBANTES DE DESEMBOLSO**
**📍 Ruta:** `Préstamos → Aprobar S/P → [Préstamo] → Desembolsar`

```
📋 PROCESO DE FACTURACIÓN DE DESEMBOLSOS:

PASO 1: Préstamo Aprobado
├── Verificar documentos completos
├── Confirmar análisis crediticio
├── Validar garantías
└── Autorización gerencial

PASO 2: Generar Documento de Desembolso
├── Contrato de préstamo: [PDF]
├── Tabla de amortización: [PDF]
├── Recibo de desembolso: [PDF]
└── Pagaré: [PDF]

PASO 3: Registro en Caja
├── Tipo: EGRESO
├── Concepto: DESEMBOLSO PRÉSTAMO
├── Beneficiario: [Cliente]
├── Monto: C$ [Monto del préstamo]
├── Referencia: [Número de préstamo]
└── Actualización automática de saldo

PASO 4: Documentos Generados
├── Número de desembolso: DES-001-2025
├── Expediente digital completo
├── Registro en historial crediticio
└── Activación de plan de pagos
```

#### **3. OTROS COMPROBANTES DEL SISTEMA**

```
📄 TIPOS DE DOCUMENTOS/FACTURAS:

💰 OPERACIONES DE CAJA:
├── Recibos de ingreso
├── Comprobantes de egreso
├── Arqueos de caja
└── Transferencias

💼 GESTIÓN DE PRÉSTAMOS:
├── Contratos de préstamo
├── Recibos de pago
├── Comprobantes de desembolso
├── Estados de cuenta
└── Certificaciones de deuda

📊 REPORTES OFICIALES:
├── Reporte diario de caja
├── Estados financieros
├── Reportes de mora
└── Análisis de cartera
```

### **ORDEN OPERATIVO RECOMENDADO**

```
🎯 FLUJO OPERATIVO DIARIO:

1. APERTURA (07:00 AM)
   ├── Aperturar caja con saldo inicial
   ├── Verificar sistema activo
   └── Revisar alertas pendientes

2. OPERACIONES (07:30 AM - 04:30 PM)
   ├── Recibir pagos de cuotas
   ├── Procesar nuevos desembolsos
   ├── Registrar ingresos/egresos
   └── Atender consultas de clientes

3. CUADRE PARCIAL (12:00 PM)
   ├── Arqueo de medio día
   ├── Verificar discrepancias
   └── Correcciones menores

4. CIERRE (05:00 PM)
   ├── Arqueo final obligatorio
   ├── Conteo físico de efectivo
   ├── Cuadre con sistema
   ├── Generar reporte diario
   └── Cerrar caja oficialmente

5. RESPALDOS (05:30 PM)
   ├── Backup de base de datos
   ├── Archivo de documentos
   └── Envío de reportes gerenciales
```

---

## 🔒 **CIERRE DE CAJA**

### **PROCESO DE CIERRE ESTÁNDAR**

#### **PASO 1: Preparación para Cierre**
```
🔍 VERIFICACIONES PREVIAS:
├── Todos los pagos registrados
├── Egresos autorizados y documentados
├── Transferencias confirmadas
└── Sin operaciones pendientes
```

#### **PASO 2: Arqueo Físico**
**📍 Ruta:** `Caja → [Botón] Arqueo de Caja`

```
💵 CONTEO FÍSICO:
├── Billetes por denominación:
│   ├── C$ 1000 × [Qty] = C$ [Total]
│   ├── C$ 500 × [Qty] = C$ [Total]
│   ├── C$ 200 × [Qty] = C$ [Total]
│   ├── C$ 100 × [Qty] = C$ [Total]
│   ├── C$ 50 × [Qty] = C$ [Total]
│   └── C$ 20 × [Qty] = C$ [Total]
├── Monedas por denominación
├── Cheques recibidos
└── Vales/Comprobantes pendientes

💻 SALDO SISTEMA: C$ [Monto calculado]
👥 CONTEO FÍSICO: C$ [Monto real]
📊 DIFERENCIA: C$ [Variación]
```

#### **PASO 3: Cuadre y Validación**
```
✅ TOLERANCIA PERMITIDA: ± C$ 5.00
❌ DIFERENCIAS MAYORES:
├── Requieren justificación escrita
├── Autorización supervisora
└── Investigación de discrepancias
```

#### **PASO 4: Cierre Oficial**
1. **Confirmar Cuadre:** Sistema vs. Físico
2. **Generar Reporte:** Resumen diario automático
3. **Cerrar Caja:** Cambio de estado a "CERRADA"
4. **Backup:** Resguardo automático de información

---

## 📈 **DASHBOARD Y REPORTES**

### **DASHBOARD EJECUTIVO**
**📍 Ruta:** `Dashboard Ejecutivo`

```
📊 MÉTRICAS CLAVE:
├── Total en Caja: C$ [En tiempo real]
├── Préstamos Activos: [Número]
├── Saldo de Cartera: C$ [Total]
├── Clientes Activos: [Número]
├── Monto en Mora: C$ [Crítico]
└── Eficiencia Operativa: [%]
```

### **DASHBOARD DE CAJA**
**📍 Ruta:** `Caja → Dashboard de Caja`

```
🎯 CONTROL OPERATIVO:
├── Cajas Activas: [Por sucursal]
├── Saldos Actuales: [En tiempo real]
├── Alertas Activas: [Crítico/Warning]
├── Operaciones del Día: [Resumen]
├── Usuarios Conectados: [Lista]
└── Auditoría Reciente: [Log]
```

### **REPORTES DISPONIBLES**
**📍 Ruta:** `Reportes → [Tipo de Reporte]`

#### **1. Reportes de Caja:**
- **Reporte Diario:** Resumen completo del día
- **Movimientos:** Detalle de ingresos/egresos
- **Arqueos:** Historial de cuadres
- **Discrepancias:** Análisis de diferencias

#### **2. Reportes Financieros:**
- **Estado de Cartera:** Préstamos por estado
- **Análisis de Mora:** Clientes en mora
- **Flujo de Caja:** Proyecciones
- **Rentabilidad:** Análisis por sucursal

#### **3. Reportes Gerenciales:**
- **Performance:** Indicadores clave
- **Comparativos:** Períodos anteriores
- **Consolidado:** Multi-sucursal
- **Ejecutivo:** Resumen para dirección

---

## 🏪 **GESTIÓN MULTI-SUCURSAL**

### **CONFIGURACIÓN AVANZADA**

#### **PASO 1: Estructura Organizacional**
```
🏢 ORGANIZACIÓN RECOMENDADA:

Oficina Principal
├── Sucursal Centro
│   ├── Caja Principal
│   ├── Ventanilla 1
│   └── Ventanilla 2
├── Sucursal Norte  
│   ├── Caja Única
│   └── Caja Auxiliar
└── Sucursal Sur
    └── Caja Principal
```

#### **PASO 2: Permisos y Accesos**
```
👥 CONTROL DE ACCESO:
├── Administrador Sistema: Todas las sucursales
├── Gerente Sucursal: Solo su sucursal
├── Cajero: Solo su caja asignada
└── Supervisor: Múltiples cajas autorizadas
```

#### **PASO 3: Transferencias Entre Sucursales**
```
🔄 PROCESO DE TRANSFERENCIA:

1. AUTORIZACIÓN:
   ├── Solicitud formal
   ├── Aprobación gerencial
   └── Registro en sistema

2. EJECUCIÓN:
   ├── Sucursal Origen: Egreso
   ├── Transportes: [Método]
   ├── Sucursal Destino: Ingreso
   └── Confirmación bilateral

3. VALIDACIÓN:
   ├── Cuadre automático
   ├── Documentación completa
   └── Registro de auditoría
```

### **CONSOLIDACIÓN DE REPORTES**
```
📊 REPORTES CONSOLIDADOS:
├── Vista por Sucursal
├── Totales Generales
├── Comparativos
├── Análisis de Tendencias
└── Alertas Corporativas
```

---

## 🛠️ **SOLUCIÓN DE PROBLEMAS**

### **PROBLEMAS COMUNES Y SOLUCIONES**

#### **1. Error de Apertura de Caja**
```
❌ PROBLEMA: "No se puede aperturar la caja"
🔧 SOLUCIONES:
├── Verificar que la caja esté cerrada
├── Comprobar permisos del usuario
├── Validar conexión a base de datos
└── Revisar saldo mínimo requerido
```

#### **2. Discrepancias en Arqueo**
```
❌ PROBLEMA: Diferencia entre sistema y físico
🔧 SOLUCIONES:
├── Revisar todas las operaciones del día
├── Verificar operaciones no confirmadas
├── Comprobar transferencias pendientes
├── Documentar diferencias menores
└── Solicitar autorización para ajustes
```

#### **3. Problemas de Conectividad**
```
❌ PROBLEMA: "Error de conexión"
🔧 SOLUCIONES:
├── Verificar conexión a internet
├── Comprobar servidor de base de datos
├── Reiniciar servicios del sistema
├── Modo offline temporal (si disponible)
└── Contactar soporte técnico
```

#### **4. Errores en Reportes**
```
❌ PROBLEMA: Reportes no cargan o datos incorrectos
🔧 SOLUCIONES:
├── Limpiar caché del navegador
├── Verificar filtros aplicados
├── Comprobar permisos de acceso
├── Actualizar navegador
└── Regenerar reporte
```

### **CONTACTOS DE SOPORTE**
```
📞 SOPORTE TÉCNICO:
├── Nivel 1: [Teléfono/WhatsApp]
├── Nivel 2: [Email técnico]
├── Emergencias: [24/7]
└── Documentación: [Portal web]
```

---

## ✅ **MEJORES PRÁCTICAS**

### **OPERATIVAS**
```
🎯 RECOMENDACIONES OPERATIVAS:

1. DISCIPLINA HORARIA:
   ├── Apertura puntual (07:00 AM)
   ├── Arqueos intermedios (12:00 PM)
   ├── Cierre programado (05:00 PM)
   └── Respaldos diarios

2. CONTROL DE DOCUMENTOS:
   ├── Numeración consecutiva
   ├── Firmas obligatorias
   ├── Archivo ordenado
   └── Digitalización preventiva

3. SEGURIDAD:
   ├── Cambio periódico de contraseñas
   ├── Logout al ausentarse
   ├── Montos máximos por operación
   └── Autorización dual para altos montos
```

### **SEGURIDAD**
```
🔒 PRÁCTICAS DE SEGURIDAD:

1. ACCESO AL SISTEMA:
   ├── Contraseñas complejas (8+ caracteres)
   ├── Cambio cada 90 días
   ├── No compartir credenciales
   └── Logout automático por inactividad

2. MANEJO DE EFECTIVO:
   ├── Límites por caja
   ├── Conteos aleatorios
   ├── Depósitos programados
   └── Cámaras de seguridad

3. RESPALDOS:
   ├── Backup automático diario
   ├── Copias en múltiples ubicaciones
   ├── Pruebas de restauración
   └── Documentación actualizada
```

### **AUDITORIA**
```
📋 CONTROL DE AUDITORÍA:

1. RASTRO COMPLETO:
   ├── Toda operación registrada
   ├── Usuario responsable identificado
   ├── Fecha/hora automática
   └── IP de conexión

2. REVISIONES PERIÓDICAS:
   ├── Auditoría semanal interna
   ├── Revisión mensual gerencial
   ├── Auditoría externa anual
   └── Correcciones inmediatas

3. INDICADORES:
   ├── % de cuadres exactos
   ├── Tiempo promedio de operaciones
   ├── Errores por cajero
   └── Satisfacción del cliente
```

---

## 📧 **CONTACTO Y SOPORTE**

### **INFORMACIÓN DEL SISTEMA**
- **Sistema:** SIPREST v2.0
- **Módulo:** Gestión de Caja Empresarial
- **Desarrollador:** Developer Senior
- **Fecha:** Enero 2025

### **SOPORTE TÉCNICO**
```
📱 CANALES DE CONTACTO:
├── WhatsApp: [+505] XXXX-XXXX
├── Email: soporte@siprest.com
├── Teléfono: [+505] XXXX-XXXX
└── Portal: https://soporte.siprest.com
```

### **HORARIOS DE ATENCIÓN**
```
🕐 DISPONIBILIDAD:
├── Lunes a Viernes: 7:00 AM - 6:00 PM
├── Sábados: 8:00 AM - 12:00 PM
├── Emergencias: 24/7 (WhatsApp)
└── Mantenimiento: Domingos 2:00 AM - 4:00 AM
```

---

**© 2025 SIPREST - Sistema Integral de Préstamos**  
**Manual desarrollado por Developer Senior**  
**Versión 2.0 - Documentación Oficial**

---

> **💡 NOTA IMPORTANTE:** Este manual debe actualizarse cada vez que se implementen nuevas funcionalidades. Para sugerencias de mejora o reporte de errores en la documentación, contactar al equipo de desarrollo. 