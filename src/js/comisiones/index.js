import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formComision = document.getElementById('formComision');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarComisiones = document.getElementById('BtnBuscarComisiones');
const seccionTabla = document.getElementById('seccionTabla');

const guardarComision = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formComision, ['comision_id', 'comision_fecha_creacion', 'comision_usuario_creo', 'comision_situacion'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    document.getElementById('comision_usuario_creo').value = 1;

    const body = new FormData(formComision);
    const url = "/contreras_final_comisiones_ingsoft1/comision/guardarAPI";
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos);
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarComisiones();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
    BtnGuardar.disabled = false;
}

const BuscarComisiones = async () => {
    const url = `/contreras_final_comisiones_ingsoft1/comision/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Comisiones encontradas:', data);

            if (datatable) {
                datatable.clear().draw();
                datatable.rows.add(data).draw();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
}

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarComisiones();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableComisiones', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'comision_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Título', 
            data: 'comision_titulo',
            width: '20%'
        },
        { 
            title: 'Tipo', 
            data: 'comision_tipo',
            width: '10%'
        },
        { 
            title: 'Fecha Inicio', 
            data: 'comision_fecha_inicio',
            width: '10%'
        },
        { 
            title: 'Duración', 
            data: 'comision_duracion',
            width: '8%',
            render: (data, type, row) => {
                return `${data} ${row.comision_duracion_tipo}`;
            }
        },
        { 
            title: 'Ubicación', 
            data: 'comision_ubicacion',
            width: '15%'
        },
        {
            title: 'Estado',
            data: 'comision_estado',
            width: '10%',
            render: (data, type, row) => {
                let badgeClass = 'bg-secondary';
                switch(data) {
                    case 'PROGRAMADA':
                        badgeClass = 'bg-primary';
                        break;
                    case 'EN_CURSO':
                        badgeClass = 'bg-warning';
                        break;
                    case 'COMPLETADA':
                        badgeClass = 'bg-success';
                        break;
                    case 'CANCELADA':
                        badgeClass = 'bg-danger';
                        break;
                }
                return `<span class="badge ${badgeClass}">${data}</span>`;
            }
        },
        {
            title: 'Creado por',
            data: 'usuario_nom1',
            width: '10%',
            render: (data, type, row) => {
                return `${row.usuario_nom1} ${row.usuario_ape1}`;
            }
        },
        {
            title: 'Situación',
            data: 'comision_situacion',
            width: '7%',
            render: (data, type, row) => {
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'comision_id',
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-titulo="${row.comision_titulo || ''}"  
                         data-descripcion="${row.comision_descripcion || ''}"  
                         data-tipo="${row.comision_tipo || ''}"
                         data-fecha-inicio="${row.comision_fecha_inicio || ''}"
                         data-duracion="${row.comision_duracion || ''}"
                         data-duracion-tipo="${row.comision_duracion_tipo || ''}"
                         data-ubicacion="${row.comision_ubicacion || ''}"
                         data-estado="${row.comision_estado || ''}"
                         data-observaciones="${row.comision_observaciones || ''}"
                         title="Modificar">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-info estado mx-1' 
                         data-id="${data}"
                         data-estado="${row.comision_estado || ''}"
                         title="Cambiar Estado">
                         <i class='bi bi-arrow-repeat me-1'></i> Estado
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}"
                         title="Eliminar">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('comision_id').value = datos.id;
    document.getElementById('comision_titulo').value = datos.titulo;
    document.getElementById('comision_descripcion').value = datos.descripcion;
    document.getElementById('comision_tipo').value = datos.tipo;
    document.getElementById('comision_fecha_inicio').value = datos.fechaInicio;
    document.getElementById('comision_duracion').value = datos.duracion;
    document.getElementById('comision_duracion_tipo').value = datos.duracionTipo;
    document.getElementById('comision_ubicacion').value = datos.ubicacion;
    document.getElementById('comision_estado').value = datos.estado;
    document.getElementById('comision_observaciones').value = datos.observaciones;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formComision.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    document.getElementById('comision_estado').value = 'PROGRAMADA';
}

const ModificarComision = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formComision, ['comision_id', 'comision_fecha_creacion', 'comision_usuario_creo', 'comision_situacion'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(formComision);
    const url = '/contreras_final_comisiones_ingsoft1/comision/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarComisiones();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
    }
    BtnModificar.disabled = false;
}

const CambiarEstadoComision = async (e) => {
    const idComision = e.currentTarget.dataset.id;
    const estadoActual = e.currentTarget.dataset.estado;

    const { value: nuevoEstado } = await Swal.fire({
        title: 'Cambiar Estado de Comisión',
        input: 'select',
        inputOptions: {
            'PROGRAMADA': 'PROGRAMADA',
            'EN_CURSO': 'EN CURSO',
            'COMPLETADA': 'COMPLETADA',
            'CANCELADA': 'CANCELADA'
        },
        inputValue: estadoActual,
        showCancelButton: true,
        confirmButtonText: 'Cambiar Estado',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debe seleccionar un estado';
            }
        }
    });

    if (nuevoEstado && nuevoEstado !== estadoActual) {
        const formData = new FormData();
        formData.append('comision_id', idComision);
        formData.append('comision_estado', nuevoEstado);

        const url = '/contreras_final_comisiones_ingsoft1/comision/cambiarEstadoAPI';
        const config = {
            method: 'POST',
            body: formData
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarComisiones();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error);
        }
    }
}

const EliminarComisiones = async (e) => {
    const idComision = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/contreras_final_comisiones_ingsoft1/comision/EliminarAPI?id=${idComision}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarComisiones();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error);
        }
    }
}

datatable.on('click', '.eliminar', EliminarComisiones);
datatable.on('click', '.modificar', llenarFormulario);
datatable.on('click', '.estado', CambiarEstadoComision);
formComision.addEventListener('submit', guardarComision);

BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarComision);
BtnBuscarComisiones.addEventListener('click', MostrarTabla);