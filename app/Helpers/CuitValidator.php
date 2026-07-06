<?php

namespace App\Helpers;


class CuitValidator
{
    /**
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


        $multiplicadores = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $suma = 0;


        for ($i = 0; $i < 10; $i++) {
            $suma += (int) $cuit[$i] * $multiplicadores[$i];
        }

        $resto = $suma % 11;

        $digitoVerificador = 11 - $resto;

        if ($digitoVerificador === 11) {
            $digitoVerificador = 0;
        } elseif ($digitoVerificador === 10) {

            $digitoVerificador = 9;
        }


        return $digitoVerificador === (int) $cuit[10];
    }
}
