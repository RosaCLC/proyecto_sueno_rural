import{
    esNumeroValido,
    esNombreValido,
    esEmailValido,
    esPasswordValida, 
    esLatitudValida,
    esLongitudValida
} from './validaciones.js';

const buscador = document.getElementById('buscador');
const formLogin = document.getElementById('formLogin');
const formRegistro = document.getElementById("formRegistro");
const formFiltros = document.getElementById('formFiltros');

const precioMin = document.getElementById('precioMin');
const precioMax = document.getElementById('precioMax');
const precio = document.getElementById('precio');
const banos = document.getElementById('banos');
const habitaciones = document.getElementById('habitaciones');
const metrosViv = document.getElementById('metrosViv');
const metrosTerr = document.getElementById('metrosTerr');
const idInmueble = document.getElementById('id_inmueble');
const latitud = document.getElementById('latitud');
const longitud = document.getElementById('longitud');

const nombre = document.getElementById('nombre');
const email = document.getElementById('email');
const password = document.getElementById('password');
const repetirPassword = document.getElementById('repetirPassword');

const errorPrecioMin = document.querySelector('.errorPrecioMin');
const errorPrecioMax = document.querySelector('.errorPrecioMax');
const errorPrecio = document.querySelector('.errorPrecio');
const errorBanos = document.querySelector('.errorBanos');
const errorHabitaciones = document.querySelector('.errorHabitaciones');
const errorMetrosViv = document.querySelector('.errorMetrosViv');
const errorMetrosTerr = document.querySelector('.errorMetrosTerr');
const errorIdInmueble = document.querySelector('.errorIdInmueble');
const errorLatitud = document.querySelector('.errorLatitud');
const errorLongitud = document.querySelector('.errorLongitud');

const errorNombre = document.querySelector('.errorNombre');
const errorEmail = document.querySelector('.errorEmail');
const errorPassword = document.querySelector('.errorPassword');
const errorRepetir = document.querySelector('.errorRepetir');

const botonFiltros = document.getElementById('botonFiltros');
const contenedorFiltros = document.querySelector('.contenedorFiltros');


//VALIDACIONES BUSCADOR (PRECIOS)
if (buscador){
    buscador.addEventListener('submit', function(e){
        if (precioMin.value <0 || precioMax.value <0){
            alert('Los precios no pueden ser negativos');
            e.preventDefault();
            return;
        }
        if (precioMin.value && precioMax.value && Number(precioMin.value) > Number(precioMax.value)) {
            alert('El precio mínimo no puede ser mayor que el máximo');
            e.preventDefault();
            return;
        }
    })
    if (precioMin){
        validarCampoNumero (precioMin, errorPrecioMin);
    }
    if (precioMax){
        validarCampoNumero (precioMax, errorPrecioMax);
    }
}

//VALIDACIONES EN EL BLOQUE DE FILTROS
if (formFiltros){
    if (precioMin){
        validarCampoNumero (precioMin, errorPrecioMin);
    }
    if (precioMax){
        validarCampoNumero (precioMax, errorPrecioMax);
    }
    if (precio){
        validarCampoNumero (precio, errorPrecio);
    }
    if (banos){
        validarCampoNumero (banos, errorBanos);
    }
    if (habitaciones){
        validarCampoNumero (habitaciones, errorHabitaciones);
    }
    if (metrosViv){
        validarCampoNumero (metrosViv, errorMetrosViv);
    }
    if (metrosTerr){
        validarCampoNumero (metrosTerr, errorMetrosTerr);
    }
    if (idInmueble){
        validarCampoNumero (idInmueble, errorIdInmueble);
    }
    if (latitud){
        latitud.addEventListener('input', function(){
            if (!esLatitudValida(this.value)){
                errorLatitud.textContent = 'Cifra errónea. La latitud ha de ser entre -90 y 90 grados, puede llevar decimales'
            } else{
                errorLatitud.textContent = '';
            }
        });
            
    }
    if (longitud){
        longitud.addEventListener('input', function(){
            if (!esLongitudValida(this.value)){
                errorLongitud.textContent = 'Cifra errónea. La longitud ha de ser entre -180 y 180 grados, puede llevar decimales'
            } else{
                errorLongitud.textContent = '';
            }
        });
    }
}

//VALIDACIONES FORMULARIOS (NOMBRE, EMAIL, CONTRASEÑAS)
if (formRegistro){
    nombre.addEventListener('input',function(){
        if (!esNombreValido(this.value)){
            errorNombre.textContent = 'El nombre no puede estar vacío';
        } else {
            errorNombre.textContent = '';
        }
    });
    email.addEventListener('input',function(){
        validarEmail(this);
    });
    password.addEventListener('input',function(){
        if (!esPasswordValida(this.value)){
            errorPassword.textContent = 'La contraseña debe contener mínimo 8 caracteres, 1 dígito, 1 mayúscula y 1 minúscula';
        } else {
            errorPassword.textContent = '';
        }
    });
    repetirPassword.addEventListener('input', function(){
        compararPassword();
    });
}

if (formLogin){
    email.addEventListener('input',function(){
        validarEmail(this);
    });
}

if (botonFiltros && contenedorFiltros){
    botonFiltros.addEventListener('click', function(){
        contenedorFiltros.classList.toggle('mostrar');
    })
}

//SECCION CARRUSEL DE IMÁGENES
const botonAnterior = document.getElementById('botonAnterior');
const botonSiguiente = document.getElementById('botonSiguiente');
let indiceFoto = 0;
const foto = document.getElementById('fotoCarrusel');
if (typeof fotosInmueble !== 'undefined' && foto && botonAnterior && botonSiguiente){
    actualizarBotones();
    botonAnterior.addEventListener('click', function(){
        if (indiceFoto > 0){
            indiceFoto--;
            cambioImagen();
        }
    })
    botonSiguiente.addEventListener('click', function(){
        if (indiceFoto < fotosInmueble.length -1){
            indiceFoto++;
            cambioImagen();
        }
    })
}

//AGREGAR FAVORITO FALSO
const botonFalsoAgregar = document.getElementById('falsoAgregar');
const errorBotonFalso = document.querySelector('.botonFalso');
if(botonFalsoAgregar){
    botonFalsoAgregar.addEventListener('click', function(){
        errorBotonFalso.style.display='block';
    })
}

//FUNCIONES UTILIZADAS
function actualizarBotones(){
    if (fotosInmueble.length <= 1){
        botonAnterior.style.display = 'none';
        botonSiguiente.style.display = 'none';
    } else if (indiceFoto === 0){
        botonSiguiente.style.display = 'block';
        botonAnterior.style.display ='none';
    } else if (indiceFoto === fotosInmueble.length -1){
        botonAnterior.style.display = 'block';
        botonSiguiente.style.display = 'none';
    } else{
        botonSiguiente.style.display = 'block';
        botonAnterior.style.display = 'block';    
    }
}
function cambioImagen(){
    foto.src = fotosInmueble[indiceFoto].url_foto;
    foto.alt = fotosInmueble[indiceFoto].descripcion_foto;
    actualizarBotones();
}

function validarCampoNumero (input, errorElemento){
    input.addEventListener('input', function(){
        if(!esNumeroValido(this.value)){
            errorElemento.textContent = 'Sólo se aceptan números enteros positivos, sin puntos ni comas';
        } else {
            errorElemento.textContent = '';
        }
    })
}

function validarEmail(input){
    if (!esEmailValido(input.value)){
        errorEmail.textContent = 'Introduzca un formato de email correcto';
    } else {
        errorEmail.textContent = '';
    }
}

function compararPassword() {
    if (repetirPassword.value === '') {
        errorRepetir.textContent = 'Es necesario poner la constraseña';
        return false;
    }
    if (password.value !== repetirPassword.value) {
        errorRepetir.textContent = 'Asegúrese de poner la misma contraseña';
        return false;
    }
    else {
        errorRepetir.textContent = '';
        return true;
    }
}