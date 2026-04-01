// Función para abrir el modal y llenar datos
$(document).on("click", ".btn-editar", function() {
    const nombre = $(this).attr("data-nombre");
    const stock = $(this).attr("data-stock");
    const precio = $(this).attr("data-precio");
    const tallas = $(this).attr("data-tallas");

    // Llenar inputs
    $("#edit-nombre").val(nombre);
    $("#edit-stock").val(stock);
    $("#edit-precio").val(precio);
    $("#edit-tallas").val(tallas);

    // Mostrar el modal
    $("#modalPersonalizado").fadeIn(200);
});

// Función para cerrar
function cerrarModal() {
    $("#modalPersonalizado").fadeOut(200);
}

// Cerrar si hacen clic fuera de la caja blanca
window.onclick = function(event) {
    if (event.target == document.getElementById("modalPersonalizado")) {
        cerrarModal();
    }
}
