document.addEventListener('DOMContentLoaded', function() {
    const grafico1Element = document.getElementById("grafico1");
    if (!grafico1Element) {
        console.error("No se encontró el elemento con id 'grafico1'");
        return;
    }
    const grafico1 = grafico1Element.getContext("2d");

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

    const BuscarComisionesPorTipo = async () => {
        const url = '/contreras_final_comisiones_ingsoft1/estadisticas/buscarComisionesPorTipoAPI';
        const config = { method: 'GET' };

        try {
            console.log('Haciendo petición a:', url);
            const respuesta = await fetch(url, config);
            console.log('Respuesta recibida:', respuesta.status);
            const datos = await respuesta.json();
            console.log('Datos parseados:', datos);
            const { codigo, mensaje, data } = datos;
            
            if (codigo == 1 && data && data.length > 0) {
                console.log('Datos encontrados:', data);
                const etiquetas = data.map(d => d.tipo);
                const cantidades = data.map(d => parseInt(d.cantidad));
                
                window.graficaComisionesPorTipo.data.labels = etiquetas;
                window.graficaComisionesPorTipo.data.datasets = [{
                    label: 'Cantidad de Comisiones',
                    data: cantidades,
                    backgroundColor: ['#008000', '#0000FF', '#FFFF00', '#FFA500', '#000000', '#800080', '#008080']
                }];
                window.graficaComisionesPorTipo.update();
                console.log('Gráfica actualizada correctamente');
            } else {
                console.log('No se encontraron datos:', { codigo, mensaje, data });
            }
        } catch (error) {
            console.error('Error en la petición:', error);
        }
    }

    BuscarComisionesPorTipo();
});