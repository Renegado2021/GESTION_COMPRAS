<?php
// Conexión a la base de datos
include 'db_connect.php';

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'] . '%'; // Agregamos el operador % aquí
    
    // Consulta SQL para buscar objetos que comiencen con la letra ingresada
    $stmt = $conn->prepare("SELECT * FROM tbl_objetos WHERE NOMBRE_OBJETO LIKE :searchTerm");
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Genera el HTML de la lista de objetos encontrados
    foreach ($objetos as $objeto) {
        echo "<tr>";
        echo "<td>" . $objeto['ID_OBJETO'] . "</td>";
        echo "<td>" . $objeto['NOMBRE_OBJETO'] . "</td>";
        echo "<td>" . $objeto['DESCRIPCION'] . "</td>";
        echo "<td>" . $objeto['FECHA_CREACION'] . "</td>";
        echo "<td>" . $objeto['FECHA_MODIFICACION'] . "</td>";
        echo "<td>" . $objeto['CREADO_POR'] . "</td>";
        echo "<td>" . $objeto['MODIFICADO_POR'] . "</td>";
        echo "<td>";
        echo "<a href='editar_objeto.php?id=" . $objeto['ID_OBJETO'] . "' class='edit-link'><i class='fas fa-pencil-alt'></i></a>";
        echo "<a href='eliminar_objeto.php?id=" . $objeto['ID_OBJETO'] . "' class='delete-link'><i class='fas fa-trash'></i></a>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    // Manejo de búsqueda sin término
    echo "<tr><td colspan='8'>Ingresa un término de búsqueda</td></tr>";
}
?>
