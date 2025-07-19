/**
 * DASHBOARD CHARTS - Funciones para gráficos del dashboard de cobradores
 * Autor: Sistema de Gestión
 * Fecha: 2025
 */

// Variables globales para los gráficos
let cobrosChart, moraChart, comparacionChart;

/**
 * Crear gráfico de pastel para cobros por cobrador
 */
function crearGraficoPastelCobros(datos) {
    const ctx = document.getElementById('cobrosChart').getContext('2d');
    
    // Destruir gráfico existente si existe
    if (cobrosChart) {
        cobrosChart.destroy();
    }
    
    cobrosChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: datos.labels,
            datasets: [{
                data: datos.datasets[0].data,
                backgroundColor: datos.datasets[0].backgroundColor,
                borderColor: datos.datasets[0].borderColor,
                borderWidth: datos.datasets[0].borderWidth,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: `Cobros por Cobrador (${datos.total_cobradores} cobradores)`,
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const meta = chart.getDatasetMeta(0);
                                    const style = meta.controller.getStyle(i);
                                    const value = data.datasets[0].data[i];
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    
                                    return {
                                        text: `${label}: ${formatearMoneda(value)} (${percentage}%)`,
                                        fillStyle: style.backgroundColor,
                                        strokeStyle: style.borderColor,
                                        lineWidth: style.borderWidth,
                                        hidden: isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${formatearMoneda(value)} (${percentage}%)`;
                        },
                        afterLabel: function(context) {
                            const promedio = datos.promedio_por_cobrador;
                            const diferencia = context.parsed - promedio;
                            const signo = diferencia >= 0 ? '+' : '';
                            return `Vs Promedio: ${signo}${formatearMoneda(diferencia)}`;
                        }
                    }
                }
            },
            interaction: {
                intersect: false
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1000,
                easing: 'easeOutBounce'
            }
        }
    });
    
    // Mostrar estadísticas adicionales
    mostrarEstadisticasCobros(datos);
}

/**
 * Crear gráfico de pastel para mora por cobrador
 */
function crearGraficoPastelMora(datos) {
    const ctx = document.getElementById('moraChart').getContext('2d');
    
    // Destruir gráfico existente si existe
    if (moraChart) {
        moraChart.destroy();
    }
    
    moraChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: datos.labels,
            datasets: [{
                data: datos.datasets[0].data,
                backgroundColor: datos.datasets[0].backgroundColor,
                borderColor: datos.datasets[0].borderColor,
                borderWidth: datos.datasets[0].borderWidth,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: `Mora por Cobrador (${datos.total_cobradores_con_mora} con mora)`,
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const meta = chart.getDatasetMeta(0);
                                    const style = meta.controller.getStyle(i);
                                    const value = data.datasets[0].data[i];
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    
                                    return {
                                        text: `${label}: ${formatearMoneda(value)} (${percentage}%)`,
                                        fillStyle: style.backgroundColor,
                                        strokeStyle: style.borderColor,
                                        lineWidth: style.borderWidth,
                                        hidden: isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${formatearMoneda(value)} (${percentage}%)`;
                        },
                        afterLabel: function(context) {
                            return 'Requiere atención prioritaria';
                        }
                    }
                }
            },
            interaction: {
                intersect: false
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1000,
                easing: 'easeOutBounce'
            }
        }
    });
    
    // Mostrar estadísticas adicionales
    mostrarEstadisticasMora(datos);
}

/**
 * Crear gráfico de líneas para comparación mensual
 */
function crearGraficoComparacion(datos) {
    const ctx = document.getElementById('comparacionChart').getContext('2d');
    
    // Destruir gráfico existente si existe
    if (comparacionChart) {
        comparacionChart.destroy();
    }
    
    comparacionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: datos.labels,
            datasets: datos.datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Días del Mes',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Monto ($)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return formatearMoneda(value);
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Comparación: Mes Anterior vs Mes Actual',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                },
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        title: function(context) {
                            return `Día ${context[0].label}`;
                        },
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return `${label}: ${formatearMoneda(value)}`;
                        },
                        afterBody: function(context) {
                            const cobroActual = context.find(c => c.dataset.label.includes('Actual'))?.parsed.y || 0;
                            const cobroAnterior = context.find(c => c.dataset.label.includes('Anterior'))?.parsed.y || 0;
                            
                            if (cobroActual && cobroAnterior) {
                                const diferencia = cobroActual - cobroAnterior;
                                const porcentaje = cobroAnterior > 0 ? ((diferencia / cobroAnterior) * 100).toFixed(1) : 0;
                                const signo = diferencia >= 0 ? '+' : '';
                                
                                return [
                                    '',
                                    `Diferencia: ${signo}${formatearMoneda(diferencia)}`,
                                    `Variación: ${signo}${porcentaje}%`
                                ];
                            }
                            return [];
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 8,
                    hitRadius: 10
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
    
    // Mostrar estadísticas de comparación
    mostrarEstadisticasComparacion(datos);
}

/**
 * Mostrar estadísticas adicionales para cobros
 */
function mostrarEstadisticasCobros(datos) {
    const estadisticas = `
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="text-center">
                    <h5 class="text-primary">${formatearMoneda(datos.total_monto)}</h5>
                    <small class="text-muted">Total Cobrado</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h5 class="text-info">${datos.total_cobradores}</h5>
                    <small class="text-muted">Cobradores Activos</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h5 class="text-success">${formatearMoneda(datos.promedio_por_cobrador)}</h5>
                    <small class="text-muted">Promedio por Cobrador</small>
                </div>
            </div>
        </div>
    `;
    
    $('#cobrosChart').parent().append(estadisticas);
}

/**
 * Mostrar estadísticas adicionales para mora
 */
function mostrarEstadisticasMora(datos) {
    const estadisticas = `
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="text-center">
                    <h5 class="text-danger">${formatearMoneda(datos.total_mora)}</h5>
                    <small class="text-muted">Total en Mora</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h5 class="text-warning">${datos.total_cobradores_con_mora}</h5>
                    <small class="text-muted">Cobradores con Mora</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h5 class="text-info">${formatearMoneda(datos.promedio_mora)}</h5>
                    <small class="text-muted">Promedio Mora</small>
                </div>
            </div>
        </div>
    `;
    
    $('#moraChart').parent().append(estadisticas);
}

/**
 * Mostrar estadísticas de comparación
 */
function mostrarEstadisticasComparacion(datos) {
    const stats = datos.estadisticas;
    const variacionCobros = stats.total_anterior_cobros > 0 ? 
        (((stats.total_actual_cobros - stats.total_anterior_cobros) / stats.total_anterior_cobros) * 100).toFixed(1) : 0;
    
    const estadisticas = `
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="comparison-container">
                    <div class="comparison-period">
                        <div class="comparison-value text-primary">${formatearMoneda(stats.total_actual_cobros)}</div>
                        <div class="comparison-label">Mes Actual</div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 text-center">
                <div class="comparison-arrow">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="comparison-container">
                    <div class="comparison-period">
                        <div class="comparison-value text-secondary">${formatearMoneda(stats.total_anterior_cobros)}</div>
                        <div class="comparison-label">Mes Anterior</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="text-center">
                    <h5 class="${variacionCobros >= 0 ? 'text-success' : 'text-danger'}">
                        ${variacionCobros >= 0 ? '+' : ''}${variacionCobros}%
                    </h5>
                    <small class="text-muted">Variación</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <h5 class="text-info">${formatearMoneda(stats.promedio_diario_actual)}</h5>
                    <small class="text-muted">Promedio Diario</small>
                </div>
            </div>
        </div>
    `;
    
    $('#comparacionChart').parent().append(estadisticas);
}

/**
 * Actualizar tabla de rendimiento
 */
function actualizarTablaRendimiento(datos) {
    let html = '';
    
    if (datos && datos.length > 0) {
        datos.forEach(function(fila, index) {
            const variacionClase = fila.variacion_mensual >= 0 ? 'text-success' : 'text-danger';
            const variacionIcono = fila.variacion_mensual >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
            
            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <div class="badge badge-primary rounded-circle" style="width: 30px; height: 30px; line-height: 16px;">
                                    ${index + 1}
                                </div>
                            </div>
                            <div>
                                <strong>${fila.cobrador_nombre}</strong><br>
                                <small class="text-muted">${fila.cobrador_usuario}</small>
                            </div>
                        </div>
                    </td>
                    <td>${fila.sucursal_nombre}</td>
                    <td>
                        <span class="badge badge-info">${fila.ruta_nombre}</span>
                    </td>
                    <td>
                        <strong class="text-success">${fila.total_cobrado_formato}</strong>
                    </td>
                    <td>
                        <strong class="text-danger">${fila.total_mora_formato}</strong>
                    </td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-${getProgressBarColor(fila.eficiencia)}" 
                                 role="progressbar" 
                                 style="width: ${Math.min(fila.eficiencia, 100)}%"
                                 aria-valuenow="${fila.eficiencia}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                ${fila.eficiencia}%
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-secondary">${fila.total_clientes}</span>
                    </td>
                    <td>
                        <span class="${variacionClase}">
                            <i class="fas ${variacionIcono}"></i>
                            ${fila.variacion_mensual >= 0 ? '+' : ''}${fila.variacion_mensual}%
                        </span>
                    </td>
                    <td>
                        <span class="performance-badge ${fila.rendimiento_clase}">
                            ${fila.rendimiento_texto}
                        </span>
                    </td>
                </tr>
            `;
        });
    } else {
        html = `
            <tr>
                <td colspan="9" class="text-center text-muted">
                    <i class="fas fa-info-circle"></i> No hay datos para mostrar con los filtros seleccionados
                </td>
            </tr>
        `;
    }
    
    $('#tablaRendimientoBody').html(html);
}

/**
 * Obtener color de la barra de progreso según eficiencia
 */
function getProgressBarColor(eficiencia) {
    if (eficiencia >= 90) return 'success';
    if (eficiencia >= 75) return 'info';
    if (eficiencia >= 60) return 'warning';
    return 'danger';
}

/**
 * Configurar eventos de interacción de gráficos
 */
function configurarEventosGraficos() {
    // Click en sectores del gráfico de cobros
    if (cobrosChart) {
        cobrosChart.options.onClick = function(event, elements) {
            if (elements.length > 0) {
                const index = elements[0].index;
                const cobrador = cobrosChart.data.labels[index];
                mostrarDetallesCobrador(cobrador);
            }
        };
    }
    
    // Click en sectores del gráfico de mora
    if (moraChart) {
        moraChart.options.onClick = function(event, elements) {
            if (elements.length > 0) {
                const index = elements[0].index;
                const cobrador = moraChart.data.labels[index];
                mostrarDetallesMora(cobrador);
            }
        };
    }
}

/**
 * Mostrar detalles de un cobrador específico
 */
function mostrarDetallesCobrador(cobrador) {
    Swal.fire({
        title: `Detalles de ${cobrador}`,
        html: `
            <div class="text-left">
                <p><strong>Cobrador:</strong> ${cobrador}</p>
                <p><strong>Período:</strong> ${$('#fecha_inicio').val()} al ${$('#fecha_fin').val()}</p>
                <p class="text-info">Cargando información detallada...</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ver Reporte Completo',
        cancelButtonText: 'Cerrar',
        confirmButtonColor: '#007bff'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir a reporte detallado del cobrador
            window.open(`reportes_cobrador_detallado.php?cobrador=${encodeURIComponent(cobrador)}`, '_blank');
        }
    });
}

/**
 * Mostrar detalles de mora de un cobrador
 */
function mostrarDetallesMora(cobrador) {
    Swal.fire({
        title: `Análisis de Mora - ${cobrador}`,
        html: `
            <div class="text-left">
                <p><strong>Cobrador:</strong> ${cobrador}</p>
                <p><strong>Estado:</strong> <span class="text-danger">Requiere Atención</span></p>
                <p><strong>Recomendación:</strong> Revisar cartera vencida y contactar clientes</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Plan de Acción',
        cancelButtonText: 'Cerrar',
        confirmButtonColor: '#dc3545'
    });
}

/**
 * Animaciones y efectos visuales
 */
function aplicarAnimaciones() {
    // Animación de entrada para las métricas
    $('.metric-card').each(function(index) {
        $(this).css('opacity', 0).delay(index * 100).animate({
            opacity: 1
        }, 500);
    });
    
    // Efecto hover para las tarjetas
    $('.metric-card').hover(
        function() {
            $(this).addClass('shadow-lg').css('transform', 'translateY(-5px)');
        },
        function() {
            $(this).removeClass('shadow-lg').css('transform', 'translateY(0)');
        }
    );
}

// Inicializar animaciones cuando el documento esté listo
$(document).ready(function() {
    aplicarAnimaciones();
});

console.log('✅ Dashboard Charts - Funciones de gráficos cargadas correctamente'); 