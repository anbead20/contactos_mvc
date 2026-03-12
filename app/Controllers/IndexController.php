<?php
/*****************************************************
 * TAREA 1
 * 
 * IndexController - Controlador principal de la aplicación
 * 
 * Gestiona la página de inicio y las acciones generales.
 * 
 * FIN TAREA
*/

/*****************************************************
 * TAREA 2
 * 
 * Documenta cada una de los métodos de la clase. 
 * 
 * FIN TAREA
*/

/*****************************************************
 * TAREA 3
 * 
 * Inclye espacio de nombres y uso de las clases necesarias. 
 * 
 * FIN TAREA
*/
namespace App\Controllers;

use App\Services\ContactoService;
use App\Models\DatabaseException;

class IndexController extends BaseController
{
    private ContactoService $contactoService;

    public function __construct()
    {
        parent::__construct();
        $this->contactoService = new ContactoService();
    }

    public function indexAction(): void
    {
        try {
            /*****************************************************
             * TAREA 4
             * 
             * Obtenemos $totalContactos. 
             * 
             * FIN TAREA
            */
            $totalContactos = $this->contactoService->contarContactos();
         
            $contactosRecientes = $this->contactoService->getUltimosContactos(RECENT_CONTACTS_LIMIT);
    
            $this->renderHTML(VIEWS_DIR . '/home/index_view.php', [
                'titulo'  => 'Inicio | Agenda Pro',
                'total'   => $totalContactos,
                'ultimos' => $contactosRecientes
            ]);
    
        } catch (DatabaseException $e) {
           
            $this->mostrarErrorDB($e->getMessage());

        } catch (\Exception $e) {

            $this->mostrarError("No se pudo cargar el panel de control: " . $e->getMessage(), 500);
        }
    }
}