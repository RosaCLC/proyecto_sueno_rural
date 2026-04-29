import {
    esNumeroValido
} from './validaciones.js';

//Pruebas unitarias con Jest
test('Validación correcta de precios',()=>{
    expect(esNumeroValido())
})