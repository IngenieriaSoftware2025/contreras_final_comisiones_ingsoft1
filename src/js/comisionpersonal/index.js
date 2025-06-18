import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

document.addEventListener('DOMContentLoaded', function() {
    const formComisionPersonal = document.getElementById('formComisionPersonal');
    const BtnGuardar = document.getElementById('BtnGuardar');
    const BtnModificar = document.getElementById('BtnModificar');
    const BtnLimpiar = document.getElementById('BtnLimpiar');
    const BtnBuscarAsignaciones = document.getElementById('BtnBuscarAsignaciones');
    const BtnPersonalDisponible = document.getElementById('BtnPersonalDisponible');
    const SelectComision = document.getElementById('comision_id');
    const SelectUsuario = document.getElementById('usuario_id');
    const seccionTabla = document.getElementById('seccionTabla');
    const seccionPersonalDisponible = document.getElementById('seccionPersonalDisponible');

    const cargarComisiones = async () => {
        const url = `/contreras_final_comisiones_ingsoft1/comisionpersonal/buscarComisionesAPI`;
        const config = {
            method: 'GET'
        }

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;

            if (codigo == 1) {
                SelectComision.innerHTML = '<option value="">Seleccione una comisión</option>';
                
                data.forEach(comision => {
                    const option = document.createElement('option');
                    option.value = comision.comision_id;
                    option.textContent = `${comision.comision_titulo} (${comision.comision_tipo})`;
                    SelectComision.appendChild(option);
                });
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

    const cargarUsuarios = async () => {
        const url = `/contreras_final_comisiones_ingsoft1/comisionpersonal/buscarUsuariosAPI`;
        const config = {
            method: 'GET'
        }

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;

            if (codigo == 1) {
                SelectUsuario.innerHTML = '<option value="">Seleccione personal</option>';
                
                data.forEach(usuario => {
                    const option = document.createElement('option');
                    option.value = usuario.usuario_id;
                    option.textContent = `${usuario.usuario_nom1} ${usuario.usuario_ape1}`;
                    SelectUsuario.appendChild(option);
                });
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

    const cargarUsuariosDisponibles = async () => {
        const url = `/contreras_final_comisiones_ingsoft1/comisionpersonal/buscarUsuariosDisponiblesAPI`;
        const config = {
            method: 'GET'
        }

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;

            if (codigo == 1) {
                if (datatablePersonalDisponible) {
                    datatablePersonalDisponible.clear().draw();
                    datatablePersonalDisponible.rows.add(data).draw();
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

    const guardarAsignacion = async e => {
        e.preventDefault();
        BtnGuardar.disabled = true;

        if (!validarFormulario(formComisionPersonal, ['comision_personal_id', 'comision_personal_fecha_asignacion', 'comision_personal_usuario_asigno', 'comision_personal_situacion'])) {
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

        document.getElementById('comision_personal_usuario_asigno').value = 1;

        const body = new FormData(formComisionPersonal);
        const url = "/contreras_final_comisiones_ingsoft1/comisionpersonal/guardarAPI";
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
                BuscarAsignaciones();
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

    const BuscarAsignaciones = async () => {
        const url = `/contreras_final_comisiones_ingsoft1/comisionpersonal/buscarAPI`;
        const config = {
            method: 'GET'
        }

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();
            const { codigo, mensaje, data } = datos;

            if (codigo == 1) {
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
            seccionPersonalDisponible.style.display = 'none';
            BuscarAsignaciones();
        } else {
            seccionTabla.style.display = 'none';
        }
    }

    const MostrarPersonalDisponible = () => {
        if (seccionPersonalDisponible.style.display === 'none') {
            seccionPersonalDisponible.style.display = 'block';
            seccionTabla.style.display = 'none';
            cargarUsuariosDisponibles();
        } else {
            seccionPersonalDisponible.style.display = 'none';
        }
    }

    const datatable = new DataTable('#TableComisionPersonal', {
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
                data: 'comision_personal_id',
                width: '4%',
                render: (data, type, row, meta) => meta.row + 1
            },
            { 
                title: 'Comisión', 
                data: 'comision_titulo',
                width: '15%'
            },
            { 
                title: 'Tipo', 
                data: 'comision_tipo',
                width: '8%'
            },
            { 
                title: 'Personal Asignado', 
                data: 'usuario_nom1',
                width: '10%',
                render: (data, type, row) => {
                    return `${row.usuario_nom1} ${row.usuario_ape1}`;
                }
            },
            { 
                title: 'Fecha Asignación', 
                data: 'comision_personal_fecha_asignacion',
                width: '8%'
            },
            {
                title: 'Estado Comisión',
                data: 'comision_estado',
                width: '8%',
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
                title: 'Fecha Inicio', 
                data: 'comision_fecha_inicio',
                width: '8%'
            },
            { 
                title: 'Fecha Fin', 
                data: 'comision_fecha_fin',
                width: '8%'
            },
            {
                title: 'Asignado por',
                data: 'asigno_nom1',
                width: '10%',
                render: (data, type, row) => {
                    return `${row.asigno_nom1} ${row.asigno_ape1}`;
                }
            },
            {
                title: 'Situación',
                data: 'comision_personal_situacion',
                width: '6%',
                render: (data, type, row) => {
                    return data == 1 ? "ACTIVO" : "INACTIVO";
                }
            },
            {
                title: 'Acciones',
                data: 'comision_personal_id',
                width: '15%',
                searchable: false,
                orderable: false,
                render: (data, type, row, meta) => {
                    return `
                     <div class='d-flex justify-content-center'>
                         <button class='btn btn-warning modificar mx-1' 
                             data-id="${data}" 
                             data-comision="${row.comision_id || ''}"  
                             data-usuario="${row.usuario_id || ''}"  
                             data-observaciones="${row.comision_personal_observaciones || ''}"
                             title="Modificar">
                             <i class='bi bi-pencil-square me-1'></i> Modificar
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

    const datatablePersonalDisponible = new DataTable('#TablePersonalDisponible', {
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
                data: 'usuario_id',
                width: '10%',
                render: (data, type, row, meta) => meta.row + 1
            },
            { 
                title: 'Nombre Completo', 
                data: 'usuario_nom1',
                width: '40%',
                render: (data, type, row) => {
                    return `${row.usuario_nom1} ${row.usuario_ape1}`;
                }
            },
            { 
                title: 'Correo', 
                data: 'usuario_correo',
                width: '25%'
            },
            { 
                title: 'Teléfono', 
                data: 'usuario_tel',
                width: '15%'
            },
            {
                title: 'Estado',
                data: 'usuario_situacion',
                width: '10%',
                render: (data, type, row) => {
                    return '<span class="badge bg-success">DISPONIBLE</span>';
                }
            }
        ]
    });

    const llenarFormulario = async (event) => {
        const datos = event.currentTarget.dataset;

        document.getElementById('comision_personal_id').value = datos.id;
        document.getElementById('comision_id').value = datos.comision;
        document.getElementById('usuario_id').value = datos.usuario;
        document.getElementById('comision_personal_observaciones').value = datos.observaciones;

        BtnGuardar.classList.add('d-none');
        BtnModificar.classList.remove('d-none');

        window.scrollTo({
            top: 0,
        });
    }

    const limpiarTodo = () => {
        formComisionPersonal.reset();
        BtnGuardar.classList.remove('d-none');
        BtnModificar.classList.add('d-none');
    }

    const ModificarAsignacion = async (event) => {
        event.preventDefault();
        BtnModificar.disabled = true;

        if (!validarFormulario(formComisionPersonal, ['comision_personal_id', 'comision_personal_fecha_asignacion', 'comision_personal_usuario_asigno', 'comision_personal_situacion'])) {
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

        document.getElementById('comision_personal_usuario_asigno').value = 1;

        const body = new FormData(formComisionPersonal);
        const url = '/contreras_final_comisiones_ingsoft1/comisionpersonal/modificarAPI';
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
                BuscarAsignaciones();
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

    const EliminarAsignacion = async (e) => {
        const idAsignacion = e.currentTarget.dataset.id;

        const AlertaConfirmarEliminar = await Swal.fire({
            position: "center",
            icon: "warning",
            title: "¿Eliminar esta asignación?",
            text: 'Esta acción eliminará el registro permanentemente',
            showConfirmButton: true,
            confirmButtonText: 'Si, Eliminar',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'No, Cancelar',
            showCancelButton: true
        });

        if (AlertaConfirmarEliminar.isConfirmed) {
            const url = `/contreras_final_comisiones_ingsoft1/comisionpersonal/EliminarAPI?id=${idAsignacion}`;
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
                    
                    BuscarAsignaciones();
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

    cargarComisiones();
    cargarUsuarios();

    datatable.on('click', '.modificar', llenarFormulario);
    datatable.on('click', '.eliminar', EliminarAsignacion);
    formComisionPersonal.addEventListener('submit', guardarAsignacion);
    BtnLimpiar.addEventListener('click', limpiarTodo);
    BtnModificar.addEventListener('click', ModificarAsignacion);
    BtnBuscarAsignaciones.addEventListener('click', MostrarTabla);
    BtnPersonalDisponible.addEventListener('click', MostrarPersonalDisponible);
});