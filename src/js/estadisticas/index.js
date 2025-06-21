import { Dropdown } from "bootstrap";

document.addEventListener('DOMContentLoaded', function() {
    const grafico1Element = document.getElementById("grafico1");
    const grafico2Element = document.getElementById("grafico2");
    const grafico3Element = document.getElementById("grafico3");
    const grafico4Element = document.getElementById("grafico4");
    
    if (!grafico1Element || !grafico2Element || !grafico3Element || !grafico4Element) {
        console.error("No se encontraron todos los elementos de gráficos");
        return;
    }

    const grafico1 = grafico1Element.getContext("2d");
    const grafico2 = grafico2Element.getContext("2d");
    const grafico3 = grafico3Element.getContext("2d");
    const grafico4 = grafico4Element.getContext("2d");

    window.graficaComisionesPorComando = new Chart(grafico1, {
        type: 'pie',
        data: { labels: [], datasets: [] },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Comisiones por Comando' },
                legend: { position: 'bottom' }
            }
        }
    });

    window.graficaComisionesPorUbicacion = new Chart(grafico2, {
        type: 'bar',
        data: { labels: [], datasets: [] },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Comisiones por Ubicación' },
                legend: { display: false }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    window.graficaPersonalPorUnidad = new Chart(grafico3, {
        type: 'doughnut',
        data: { labels: [], datasets: [] },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Personal por Unidad' },
                legend: { position: 'bottom' }
            }
        }
    });

    window.graficaComisionesVsPersonal = new Chart(grafico4, {
        type: 'bar',
        data: { labels: [], datasets: [] },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Comisiones vs Personal' },
                legend: { display: false }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    const BuscarComisionesPorComando = async () => {
        const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarComisionesPorComandoAPI';
        const config = { method: 'GET' };

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;
            
            if (codigo == 1 && data && data.length > 0) {
                const etiquetas = data.map(d => d.comando);
                const cantidades = data.map(d => parseInt(d.cantidad));
                
                window.graficaComisionesPorComando.data.labels = etiquetas;
                window.graficaComisionesPorComando.data.datasets = [{
                    label: 'Cantidad de Comisiones',
                    data: cantidades,
                    backgroundColor: ['#FFA500', '#36A2EB', '#FFCE56', '#4BC0C0']
                }];
                window.graficaComisionesPorComando.update();
            }
        } catch (error) {
            console.error('Error en la petición de comisiones por comando:', error);
        }
    }

    const BuscarComisionesPorUbicacion = async () => {
        const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarComisionesPorUbicacionAPI';
        const config = { method: 'GET' };

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;
            
            if (codigo == 1 && data && data.length > 0) {
                const etiquetas = data.map(d => d.ubicacion);
                const cantidades = data.map(d => parseInt(d.cantidad));
                
                window.graficaComisionesPorUbicacion.data.labels = etiquetas;
                window.graficaComisionesPorUbicacion.data.datasets = [{
                    label: 'Cantidad de Comisiones',
                    data: cantidades,
                    backgroundColor: ['#000000', '#A52A2A', '#87CEEB', '#808080', '#008000', '#800080', '#008080', '#FF1493', '#32CD32', '#FF4500']
                }];
                window.graficaComisionesPorUbicacion.update();
            }
        } catch (error) {
            console.error('Error en la petición de comisiones por ubicación:', error);
        }
    }

    const BuscarPersonalPorUnidad = async () => {
        const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarPersonalPorUnidadAPI';
        const config = { method: 'GET' };

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;
            
            if (codigo == 1 && data && data.length > 0) {
                const etiquetas = data.map(d => d.unidad);
                const cantidades = data.map(d => parseInt(d.cantidad));
                
                window.graficaPersonalPorUnidad.data.labels = etiquetas;
                window.graficaPersonalPorUnidad.data.datasets = [{
                    label: 'Cantidad de Personal',
                    data: cantidades,
                    backgroundColor: ['#FF0000', '#FFFF00', '#000000', '#4BC0C0']
                }];
                window.graficaPersonalPorUnidad.update();
            }
        } catch (error) {
            console.error('Error en la petición de personal por unidad:', error);
        }
    }

    const BuscarComisionesVsPersonal = async () => {
        const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarComisionesVsPersonalAPI';
        const config = { method: 'GET' };

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;
            
            if (codigo == 1 && data && data.length > 0) {
                const etiquetas = data.map(d => d.categoria);
                const cantidades = data.map(d => parseInt(d.cantidad));
                
                window.graficaComisionesVsPersonal.data.labels = etiquetas;
                window.graficaComisionesVsPersonal.data.datasets = [{
                    label: 'Cantidad',
                    data: cantidades,
                    backgroundColor: ['#008000', '#808080']
                }];
                window.graficaComisionesVsPersonal.update();
            }
        } catch (error) {
            console.error('Error en la petición de comisiones vs personal:', error);
        }
    }

    BuscarComisionesPorComando();
    BuscarComisionesPorUbicacion();
    BuscarPersonalPorUnidad();
    BuscarComisionesVsPersonal();
});