const regexNumeros = /^(0|[1-9]\d*)$/;
const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const regexPassword = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/;
const regexLatitud = /^(-?([0-8]?\d(\.\d+)?|90(\.0+)?))$/;
const regexLongitud =/^(-?(1[0-7]\d(\.\d+)?|0?\d{1,2}(\.\d+)?|180(\.0+)?))$/;


/**
 * Valida los precios y números ingresados, permitiendo sólo números enteros positivos
 *
 * @export
 * @param {number} valor 
 * @returns {boolean} en función de si es válido o no.
 */
export function esNumeroValido(valor){
    if (valor.trim() === "") return true;
    return regexNumeros.test(valor);
}


export function esLatitudValida(valor){
    if(valor.trim() === "") return true;
    return regexLatitud.test(valor);
}
export function esLongitudValida(valor){
    if(valor.trim() === "") return true;
    return regexLongitud.test(valor);
}

/**
 * Valida un email usando una expresión regular
 *
 * @export
 * @param {string} valor - El email que se valida
 * @returns {boolean} - True o False en función si es válido o no.
 */
export function esEmailValido(valor){
    return regexEmail.test(valor);
}

/**
 * Valida una contraseña segura usando una expresión regular
 *
 * @export
 * @param {string} valor - La contraseña que se valida
 * @returns {boolean} - True o False en función si es válido o no.
 */
export function esPasswordValida(valor){
    return regexPassword.test(valor);
}

/**
 * Comprueba que el nombre no sea una cadena vacía
 *
 * @export
 * @param {string} valor - El nombre introducido
 * @returns {boolean} - True o False en función si es válido o no.
 */
export function esNombreValido(valor){
    return valor.trim() !== '';
}