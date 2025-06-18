import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Chart } from "chart.js/auto";

const grafico1 = document.getElementById("grafico1").getContext("2d");
const grafico2 = document.getElementById("grafico2").getContext("2d");
const grafico3 = document.getElementById("grafico3").getContext("2d");
const grafico4 = document.getElementById("grafico4").getContext("2d");
const grafico5 = document.getElementById("grafico5").getContext("2d");
const grafico6 = document.getElementById("grafico6").getContext("2d");
const grafico7 = document.getElementById("grafico7").getContext("2d");
const grafico8 = document.getElementById("grafico8").getContext("2d");
const grafico9 = document.getElementById("grafico9").getContext("2d");
const grafico10 = document.getElementById("grafico10").getContext("2d");

window.graficaComisionesPorTipo = new Chart(grafico1, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Comisiones por Tipo' },
            legend: { display: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});

window.graficaEstadosComisiones = new Chart(grafico2, {
    type: 'pie',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Estados de Comisiones' },
            legend: { position: 'bottom' }
        }
    }
});

window.graficaUsuariosActivos = new Chart(grafico3, {
    type: 'doughnut',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Top 10 Usuarios Más Activos' },
            legend: { position: 'right' }
        }
    }
});

window.graficaPersonalAsignado = new Chart(grafico4, {
    type: 'polarArea',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Personal Más Asignado a Comisiones' },
            legend: { position: 'bottom' }
        }
    }
});

window.graficaAplicacionesPermisos = new Chart(grafico5, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            title: { display: true, text: 'Aplicaciones con Más Permisos' },
            legend: { display: false }
        },
        scales: { x: { beginAtZero: true } }
    }
});

window.graficaTiposPermisos = new Chart(grafico6, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Tipos de Permisos Más Utilizados' },
            legend: { display: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});

window.graficaComisionesPorMes = new Chart(grafico7, {
    type: 'line',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Tendencia de Comisiones por Mes' },
            legend: { display: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});

window.graficaUsuariosConMasPermisos = new Chart(grafico8, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            title: { display: true, text: 'Top 10 Usuarios con Más Permisos' },
            legend: { display: false }
        },
        scales: { x: { beginAtZero: true } }
    }
});

window.graficaAsignacionesVigentes = new Chart(grafico9, {
    type: 'pie',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Asignaciones Vigentes vs Revocadas' },
            legend: { position: 'right' }
        }
    }
});

window.graficaDuracionComisiones = new Chart(grafico10, {
    type: 'doughnut',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Duración de Comisiones: Horas vs Días' },
            legend: { position: 'bottom' }
        }
    }
});

const BuscarComisionesPorTipo = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarComisionesPorTipoAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.tipo);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaComisionesPorTipo.data.labels = etiquetas;
            window.graficaComisionesPorTipo.data.datasets = [{
                label: 'Cantidad de Comisiones',
                data: cantidades,
                backgroundColor: ['#008000', '#0000FF', '#FFFF00', '#FFA500', '#000000']
            }];
            window.graficaComisionesPorTipo.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarEstadosComisiones = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarEstadosComisionesAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.estado);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaEstadosComisiones.data.labels = etiquetas;
            window.graficaEstadosComisiones.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#808080', '#FFC0CB', '#800080', '#93C5FD']
            }];
            window.graficaEstadosComisiones.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarUsuariosActivos = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarUsuariosActivosAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.usuario);
            const cantidades = data.map(d => parseInt(d.actividad));
            
            window.graficaUsuariosActivos.data.labels = etiquetas;
            window.graficaUsuariosActivos.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#059669', '#FFFF00', '#FF0000', '#808080', '#caf0f8', '#000000', '#ECFDF5', '#F0FDF4', '#DCFCE7', '#BBF7D0']
            }];
            window.graficaUsuariosActivos.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarPersonalAsignado = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarPersonalAsignadoAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.personal);
            const cantidades = data.map(d => parseInt(d.asignaciones));
            
            window.graficaPersonalAsignado.data.labels = etiquetas;
            window.graficaPersonalAsignado.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#D97706', '#008000', '#FF0000', '#FCD34D', '#FDE68A', '#FEF3C7', '#FFFBEB', '#F59E0B']
            }];
            window.graficaPersonalAsignado.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarAplicacionesPermisos = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarAplicacionesPermisosAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.aplicacion);
            const cantidades = data.map(d => parseInt(d.permisos));
            
            window.graficaAplicacionesPermisos.data.labels = etiquetas;
            window.graficaAplicacionesPermisos.data.datasets = [{
                label: 'Cantidad de Permisos',
                data: cantidades,
                backgroundColor: ['#7C3AED', '#000000', '#FF6347', '#FFFF00', '#DDD6FE', '#EDE9FE', '#F5F3FF', '#FAF5FF', '#F3F4F6', '#E5E7EB']
            }];
            window.graficaAplicacionesPermisos.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarTiposPermisos = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarTiposPermisosAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.tipo);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaTiposPermisos.data.labels = etiquetas;
            window.graficaTiposPermisos.data.datasets = [{
                label: 'Cantidad de Permisos',
                data: cantidades,
                backgroundColor: ['#EC4899', '#F97316', '#EAB308', '#10B981', '#3B82F6', '#8B5CF6']
            }];
            window.graficaTiposPermisos.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarComisionesPorMes = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarComisionesPorMesAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.mes);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaComisionesPorMes.data.labels = etiquetas;
            window.graficaComisionesPorMes.data.datasets = [{
                label: 'Comisiones Creadas',
                data: cantidades,
                borderColor: '#0891B2',
                backgroundColor: 'rgba(8, 145, 178, 0.2)',
                tension: 0.4
            }];
            window.graficaComisionesPorMes.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarUsuariosConMasPermisos = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarUsuariosConMasPermisosAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.usuario);
            const cantidades = data.map(d => parseInt(d.permisos));
            
            window.graficaUsuariosConMasPermisos.data.labels = etiquetas;
            window.graficaUsuariosConMasPermisos.data.datasets = [{
                label: 'Cantidad de Permisos',
                data: cantidades,
                backgroundColor: ['#0891B2', '#000000', '#FFA500', '#A5F3FC', '#CFFAFE', '#E0F7FA', '#B2EBF2', '#80DEEA', '#4DD0E1', '#26C6DA']
            }];
            window.graficaUsuariosConMasPermisos.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarAsignacionesVigentes = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarAsignacionesVigentesAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.estado);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaAsignacionesVigentes.data.labels = etiquetas;
            window.graficaAsignacionesVigentes.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#F59E0B', '#EF4444', '#10B981', '#8B5CF6', '#06B6D4', '#EC4899', '#84CC16', '#F97316']
            }];
            window.graficaAsignacionesVigentes.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarDuracionComisiones = async () => {
    const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarDuracionComisionesAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.tipo_duracion);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaDuracionComisiones.data.labels = etiquetas;
            window.graficaDuracionComisiones.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#059669', '#7C3AED']
            }];
            window.graficaDuracionComisiones.update();
        }
    } catch (error) {
        console.log(error);
    }
}

BuscarComisionesPorTipo();
BuscarEstadosComisiones();
BuscarUsuariosActivos();
BuscarPersonalAsignado();
BuscarAplicacionesPermisos();
BuscarTiposPermisos();
BuscarComisionesPorMes();
BuscarUsuariosConMasPermisos();
BuscarAsignacionesVigentes();
BuscarDuracionComisiones();