<?php
// Solo simulamos que procesamos los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aquí podrías imprimir los datos con print_r($_POST) para demostrar que llegan bien
    header("Location: admin_tienda.php?msg=Simulación: Producto actualizado (Los cambios no se guardarán sin BD)");
}
?>