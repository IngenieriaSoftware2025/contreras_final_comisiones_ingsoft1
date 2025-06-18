import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';

const FormLogin = document.getElementById('FormLogin');
const BtnIniciar = document.getElementById('BtnIniciar');

const login = async (e) => {
    e.preventDefault();
    BtnIniciar.disabled = true;

    if (!validarFormulario(FormLogin, [''])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnIniciar.disabled = false;
        return;
    }

    try {
        const body = new FormData(FormLogin);
        const url = '/contreras_final_comisiones_ingsoft1/API/login';
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;

        if (codigo == 1) {
            await Swal.fire({
                title: 'Éxito',
                text: mensaje,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });

            FormLogin.reset();
            location.href = '/contreras_final_comisiones_ingsoft1/inicio';
        } else {
            Swal.fire({
                title: '¡Error!',
                text: mensaje,
                icon: 'error',
                showConfirmButton: true
            });
        }

    } catch (error) {
        console.log(error);
        Swal.fire({
            title: '¡Error!',
            text: 'Error de conexión',
            icon: 'error'
        });
    }

    BtnIniciar.disabled = false;
}

const logout = async () => {
    try {
        const confirmacion = await Swal.fire({
            title: '¿Cerrar sesión?',
            text: "¿Estás seguro que deseas cerrar la sesión?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar',
            cancelButtonText: 'Cancelar'
        });

        if (confirmacion.isConfirmed) {
            await Swal.fire({
                title: 'Cerrando sesión',
                text: 'Redirigiendo al login...',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true
            });

            location.href = '/contreras_final_comisiones_ingsoft1/logout';
        }

    } catch (error) {
        console.log(error);
    }
}

FormLogin.addEventListener('submit', login);
window.logout = logout;