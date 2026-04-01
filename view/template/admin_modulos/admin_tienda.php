<?php
// admin_tienda.php - VERSIÓN PROTOTIPO INTEGRADA

// 1. DECLARACIÓN DE DATOS
$productos = [
    [
        "id" => 1,
        "nombre" => "Playera Institucional",
        "tallas" => "S, M, L",
        "stock" => 15,
        "precio" => 250.00,
        "imagen" => "https://i.pinimg.com/736x/6c/b1/99/6cb199c80970b73721408a2b11b65662.jpg"
    ],
    [
        "id" => 2,
        "nombre" => "Gorra Bordada",
        "tallas" => "Única",
        "stock" => 3,
        "precio" => 180.00,
        "imagen" => "https://i.pinimg.com/1200x/8d/22/bb/8d22bb4217a0ec72d0055e9ab659a02a.jpg"
    ]
];
?>

<link rel="stylesheet" href="view/css/admin_tienda.css"> 

<div id="vista-tienda" class="dashboard-content" style="display: none;">
    <div class="header-mensajes mb-4">
        <div>
            <h1 class="page-title">🛒 Gestión de Tienda</h1>
            <p class="text-muted">Administra el inventario de productos escolares.</p>
        </div>
    </div>

    <div class="container-fluid p-0">
        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <table class="table table-hover align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Tallas</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productos as $row): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $row['imagen']; ?>" alt="Producto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                        </td>
                        <td><strong><?php echo htmlspecialchars($row['nombre']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['tallas']); ?></td>
                        <td>
                            <span class="badge <?php echo ($row['stock'] < 5) ? 'bg-warning text-dark' : 'bg-light text-dark'; ?>">
                                <?php echo $row['stock']; ?> uds.
                            </span>
                        </td>
                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-editar" 
                                    data-id="<?php echo $row['id']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($row['nombre']); ?>"
                                    data-tallas="<?php echo htmlspecialchars($row['tallas']); ?>"
                                    data-stock="<?php echo $row['stock']; ?>"
                                    data-precio="<?php echo $row['precio']; ?>"
                                    data-imagen="<?php echo $row['imagen']; ?>">
                                Editar
                            </button>
                            <a href="eliminar_producto.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalPersonalizado" class="modal-overlay" style="display: none;">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h3>✏️ Editar Producto</h3>
            <span class="close-modal" onclick="cerrarModal()" style="cursor:pointer;">&times;</span>
        </div>
        
        <div class="modal-body-custom">
            <form id="formEditar">
                <input type="hidden" id="edit-id">
                
                <div style="text-align: center; margin-bottom: 15px;">
                    <img id="edit-preview" src="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 2px solid #fdf6e3;">
                </div>

                <div class="input-group">
                    <label>Nombre del Producto</label>
                    <input type="text" id="edit-nombre">
                </div>

                <div class="row-inputs" style="display: flex; gap: 10px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Stock</label>
                        <input type="number" id="edit-stock">
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Precio ($)</label>
                        <input type="text" id="edit-precio">
                    </div>
                </div>

                <div class="input-group">
                    <label>Tallas</label>
                    <input type="text" id="edit-tallas">
                </div>
            </form>
        </div>

        <div class="modal-footer-custom">
            <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-guardar" onclick="alert('Cambios guardados localmente')">Guardar Cambios</button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="view/js/admin_tienda.js"></script>

