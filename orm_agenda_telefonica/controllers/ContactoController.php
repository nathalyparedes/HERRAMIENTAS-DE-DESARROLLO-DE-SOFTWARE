<?php
// controllers/ContactoController.php
require_once 'models/Persona.php';
require_once 'models/Contacto.php';

class ContactoController {

    public function index() {
        if (!isset($_SESSION['persona_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $persona = Persona::find($_SESSION['persona_id']);
        $contactos = $persona->contactos();
        include 'views/contactos/contactos.php';
    }

    /* ==========================
       AGREGAR
    ========================== */
    public function agregar() {
        if (!isset($_SESSION['persona_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $contacto = new Contacto();          
        $action = 'agregar_contacto';
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contacto->persona_id = $_SESSION['persona_id'];
            $contacto->nombre_contacto   = $_POST['nombre_contacto'] ?? '';
            $contacto->apellido_contacto = $_POST['apellido_contacto'] ?? '';
            $contacto->telefono_contacto = $_POST['telefono_contacto'] ?? '';
            $contacto->email_contacto    = $_POST['email_contacto'] ?? '';

            if ($contacto->save()) {
                header('Location: index.php?action=contactos');
                exit;
            }

            $errors = $contacto->getErrors();
        }

        include 'views/contactos/form_contactos.php';
    }

    /* ==========================
       EDITAR
    ========================== */
    public function editar() {
    if (!isset($_SESSION['persona_id'])) {
        header('Location: index.php?action=login');
        exit;
    }

    $id = $_GET['id'] ?? $_POST['id'] ?? null;
    if (!$id) {
        header('Location: index.php?action=contactos');
        exit;
    }

    $contacto = Contacto::find($id);

    if (!$contacto || $contacto->persona_id != $_SESSION['persona_id']) {
        header('Location: index.php?action=contactos');
        exit;
    }

    $action = 'editar_contacto';
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $contacto->nombre_contacto   = $_POST['nombre_contacto'] ?? '';
        $contacto->apellido_contacto = $_POST['apellido_contacto'] ?? '';
        $contacto->telefono_contacto = $_POST['telefono_contacto'] ?? '';
        $contacto->email_contacto    = $_POST['email_contacto'] ?? '';

        if ($contacto->save()) {
            header('Location: index.php?action=contactos');
            exit;
        }

        $errors = $contacto->getErrors();
    }

        include 'views/contactos/form_contactos.php';
    }

    /* ==========================
       ELIMINAR
    ========================== */
    public function eliminar() {
        if (isset($_GET['id']) && isset($_SESSION['persona_id'])) {
            $contacto = Contacto::find($_GET['id']);
            if ($contacto && $contacto->persona_id == $_SESSION['persona_id']) {
                $contacto->delete();
            }
        }
        header('Location: index.php?action=contactos');
        exit;
    }
}
