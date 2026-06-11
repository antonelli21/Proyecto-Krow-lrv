<?php

namespace App\Helpers;

/**
 * CuitValidator — Valida un CUIT argentino.
 * 
 * El CUIT (Clave Única de Identificación Tributaria) tiene 11 dígitos.
 * Los primeros 2 dígitos indican el tipo (20, 23, 24, 27, 30, 33, 34).
 * Los siguientes 8 son el número de documento o identificador.
 * El último dígito es el dígito verificador calculado con un algoritmo específico.
 * 
 * Ejemplo de CUIT válido: 30-12345678-9
 */
class CuitValidator
{
    /**
     * Valida si un CUIT argentino es válido.
     * Acepta el CUIT con o sin guiones (ej: 30-12345678-9 o 30123456789).
     *
     * @param  string  $cuit  El CUIT a validar (solo dígitos, sin guiones)
     * @return bool  true si el CUIT es válido, false en caso contrario
     */
    public static function validar(string $cuit): bool
    {
        // Limpiar el CUIT: quitar todo lo que no sea dígito
        $cuit = preg_replace('/[^0-9]/', '', $cuit);

        // Debe tener exactamente 11 dígitos
        if (strlen($cuit) !== 11) {
            return false;
        }

        // Tipos de CUIT válidos en Argentina
        // 20 = Persona física masculina
        // 23 = Persona física (ambos géneros, usado cuando hay duplicados)
        // 24 = Persona física (ambos géneros, usado cuando hay duplicados)
        // 27 = Persona física femenina
        // 30 = Persona jurídica (empresa)
        // 33 = Persona jurídica (empresa)
        // 34 = Persona jurídica (empresa)
        $tiposValidos = ['20', '23', '24', '27', '30', '33', '34'];
        $tipo = substr($cuit, 0, 2);

        // Verificar que los primeros 2 dígitos sean un tipo válido
        if (!in_array($tipo, $tiposValidos)) {
            return false;
        }

        // Algoritmo de cálculo del dígito verificador
        // Se multiplica cada dígito del CUIT por su peso correspondiente
        $multiplicadores = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $suma = 0;

        // Sumar el producto de cada dígito por su multiplicador
        for ($i = 0; $i < 10; $i++) {
            $suma += (int) $cuit[$i] * $multiplicadores[$i];
        }

        // Calcular el resto de dividir la suma por 11
        $resto = $suma % 11;

        // Calcular el dígito verificador esperado
        $digitoVerificador = 11 - $resto;

        // Ajustar casos especiales del dígito verificador
        if ($digitoVerificador === 11) {
            $digitoVerificador = 0;
        } elseif ($digitoVerificador === 10) {
            // Si el dígito verificador es 10, el CUIT es inválido
            // (en la práctica AFIP no asigna estos CUITs)
            $digitoVerificador = 9;
        }

        // Comparar el dígito verificador calculado con el último dígito del CUIT
        return $digitoVerificador === (int) $cuit[10];
    }
}
