<?php
/*****************************************************
 * TAREA 1
 * 
 * Incluye bloque de documentación del archivo. 
 * 
 * FIN TAREA
*/

/*****************************************************
 * TAREA 2
 * 
 * Documenta la función
 * 
 * FIN TAREA
*/
function diasTranscurridos(string $fecha): string 
{
    if (empty($fecha)) {
        return 'Fecha no disponible';
    }
    
    try {
        $fechaCreacion = new DateTime($fecha);
        $ahora = new DateTime();
        $diferencia = $ahora->diff($fechaCreacion);
        
        if ($diferencia->y > 0) {
            return $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '');
        } elseif ($diferencia->m > 0) {
            return $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
        } elseif ($diferencia->d > 0) {
            return $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
        } else {
            return 'Hoy';
        }
    } catch (Exception $e) {
        return 'Fecha inválida';
    }
}
