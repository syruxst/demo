<?php
require_once('../admin/conex.php');
$timezone = new DateTimeZone('America/Santiago');
$now = new DateTime("now", $timezone); 
$fecha = $now->format("Y-m-d H:i:s");

// Verificar si se recibieron los datos esperados
if (isset($_POST['rutUser'])) {
    // Obtener los datos de la solicitud POST
    $id_oper = mysqli_real_escape_string($conn, $_POST['id_oper']);
    $rut = mysqli_real_escape_string($conn, $_POST['rutUser']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $region = mysqli_real_escape_string($conn, $_POST['regiones']);
    $comuna = mysqli_real_escape_string($conn, $_POST['comunas']);
    $familia = mysqli_real_escape_string($conn, $_POST['familia']) ;
    $licencia = mysqli_real_escape_string($conn, $_POST['licencia']);

    $verificar = mysqli_query($conn, "SELECT * FROM `operadores` WHERE Id = '$id_oper'");
    $row = mysqli_fetch_array($verificar);

    if($row['nombre_archivo'] == ''){
        // Validar el tipo de archivo PDF y el tamaño
        $pdf_extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        $max_pdf_size = 5 * 1024 * 1024; // 5 MB (tamaño máximo permitido para PDF)

        if ($pdf_extension !== 'pdf' || $_FILES['cv']['size'] > $max_pdf_size) {
            echo "Solo se permiten archivos PDF de hasta 5 MB.";
        } else {
            // Cambiar el nombre del archivo PDF
            $pdf_nombre_archivo = uniqid() . ".pdf";
            $pdf_dir_subida = '../uploads_op/';
            $new_name = $id_oper . "_" . $pdf_nombre_archivo;
            $pdf_fichero_subido = $pdf_dir_subida . $new_name;

            // Verificar que el archivo PDF se haya subido correctamente
            if (is_uploaded_file($_FILES['cv']['tmp_name'])) {
                // Mover el archivo PDF al directorio correspondiente
                if (move_uploaded_file($_FILES['cv']['tmp_name'], $pdf_fichero_subido)) {
                    // Crear una sentencia preparada para actualizar la tabla `operadores`
                    $sql = "UPDATE operadores SET celular = ?, direccion = ?, id_region = ?, id_ciudad = ?, licencia = ? WHERE rut = ?";
                    
                    // Preparar la sentencia
                    $stmt = mysqli_prepare($conn, $sql);

                    if ($stmt) {
                        // Vincular parámetros
                        mysqli_stmt_bind_param($stmt, "ssssss", $telefono, $direccion, $region, $comuna, $licencia, $rut);

                        // Ejecutar la sentencia
                        if (mysqli_stmt_execute($stmt)) {
                            // Los datos se actualizaron correctamente
                            echo "Los datos se actualizaron correctamente.";

                            // Actualizar información del archivo PDF en la base de datos
                            $pdf_subio_archivo = 1;
                            $pdf_query = "UPDATE operadores SET subio_archivo = '$pdf_subio_archivo', fecha_ingreso = '$fecha', nombre_archivo='$new_name' WHERE rut='$rut'";
                            $actuaOT = mysqli_query($conn, "UPDATE detallle_ot SET cv = '$new_name' WHERE rut = '$rut'");
                            $pdf_rs = mysqli_query($conn, $pdf_query);
                        } else {
                            // Hubo un error en la ejecución de la sentencia
                            echo "Error al actualizar los datos: " . mysqli_stmt_error($stmt);
                        }

                        // Cerrar la sentencia
                        mysqli_stmt_close($stmt);
                    } else {
                        // Hubo un error en la preparación de la sentencia
                        echo "Error al preparar la sentencia: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error al mover el archivo PDF al directorio de destino.";
                }
            } else {
                echo "Error: El archivo PDF no se subió correctamente.";
            }
        }
    } else {
        if (isset($_POST['actualizar_cv'])) {
            // El checkbox 'actualizar_cv' si está marcado
                // Validar el tipo de archivo PDF y el tamaño
                $pdf_extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
                $max_pdf_size = 5 * 1024 * 1024; // 5 MB (tamaño máximo permitido para PDF)

                if ($pdf_extension !== 'pdf' || $_FILES['cv']['size'] > $max_pdf_size) {
                    echo "Solo se permiten archivos PDF de hasta 5 MB.";
                } else {
                    // Cambiar el nombre del archivo PDF
                    $pdf_nombre_archivo = uniqid() . ".pdf";
                    $pdf_dir_subida = '../uploads_op/';
                    $new_name = $id_oper . "_" . $pdf_nombre_archivo;
                    $pdf_fichero_subido = $pdf_dir_subida . $new_name;

                    // Verificar que el archivo PDF se haya subido correctamente
                    if (is_uploaded_file($_FILES['cv']['tmp_name'])) {
                        // Mover el archivo PDF al directorio correspondiente
                        if (move_uploaded_file($_FILES['cv']['tmp_name'], $pdf_fichero_subido)) {
                            // Crear una sentencia preparada para actualizar la tabla `operadores`
                            $sql = "UPDATE operadores SET celular = ?, direccion = ?, id_region = ?, id_ciudad = ?, licencia = ? WHERE rut = ?";
                            
                            // Preparar la sentencia
                            $stmt = mysqli_prepare($conn, $sql);

                            if ($stmt) {
                                // Vincular parámetros
                                mysqli_stmt_bind_param($stmt, "ssssss", $telefono, $direccion, $region, $comuna, $licencia, $rut);

                                // Ejecutar la sentencia
                                if (mysqli_stmt_execute($stmt)) {
                                    // Los datos se actualizaron correctamente
                                    echo "Los datos se actualizaron correctamente.";

                                    // Actualizar información del archivo PDF en la base de datos
                                    $pdf_subio_archivo = 1;
                                    $pdf_query = "UPDATE operadores SET subio_archivo = '$pdf_subio_archivo', fecha_ingreso = '$fecha', nombre_archivo='$new_name' WHERE rut='$rut'";
                                    $actuaOT = mysqli_query($conn, "UPDATE detallle_ot SET cv = '$new_name' WHERE rut = '$rut'");
                                    $pdf_rs = mysqli_query($conn, $pdf_query);
                                } else {
                                    // Hubo un error en la ejecución de la sentencia
                                    echo "Error al actualizar los datos: " . mysqli_stmt_error($stmt);
                                }

                                // Cerrar la sentencia
                                mysqli_stmt_close($stmt);
                            } else {
                                // Hubo un error en la preparación de la sentencia
                                echo "Error al preparar la sentencia: " . mysqli_error($conn);
                            }
                        } else {
                            echo "Error al mover el archivo PDF al directorio de destino.";
                        }
                    } else {
                        echo "Error: El archivo PDF no se subió correctamente.";
                    }
                }
        } else {
            // El checkbox 'actualizar_cv' no está marcado
            $sql = "UPDATE operadores SET celular = ?, direccion = ?, id_region = ?, id_ciudad = ?, licencia = ? WHERE rut = ?";
            // Preparar la sentencia
            $stmt = mysqli_prepare($conn, $sql);
            $stmt->bind_param("ssssss", $telefono, $direccion, $region, $comuna, $licencia, $rut);
            $stmt->execute();
        }
    }// fin de CV

    if($row['foto_licencia'] == ''){
            // Validar y procesar la imagen (foto)
            $imagen_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $formatos_permitidos = array('jpg', 'jpeg', 'png');
            $max_imagen_size = 5 * 1024 * 1024; // 5 MB (tamaño máximo permitido para imágenes)

            if (in_array($imagen_extension, $formatos_permitidos) && $_FILES['foto']['size'] <= $max_imagen_size) {
                // Cambiar el nombre de la imagen
                $imagen_nombre_archivo = uniqid() . "." . $imagen_extension;
                $imagen_dir_subida = '../licencias/';
                $img_newName = $id_oper . "_" . $imagen_nombre_archivo;
                $imagen_fichero_subido = $imagen_dir_subida . $img_newName;

                // Verificar que la imagen se haya subido correctamente
                if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
                    // Mover la imagen al directorio correspondiente
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $imagen_fichero_subido)) {
                        // La imagen se subió correctamente
                        // Puedes realizar acciones adicionales si es necesario
                        echo "La imagen se subió correctamente.";
                        $actualizar = mysqli_query($conn, "UPDATE operadores SET foto_licencia = '$img_newName' WHERE rut = '$rut'");
                        $actualizarOT = mysqli_query($conn, "UPDATE detallle_ot SET licencia = '$img_newName' WHERE rut = '$rut'");
                    } else {
                        echo "Error al mover la imagen al directorio de destino.";
                    }
                } else {
                    echo "Error: La imagen no se subió correctamente.";
                }
            } else {
                echo "Solo se permiten imágenes en formato JPG, JPEG o PNG de hasta 5 MB.";
            }
    }else{
        if (isset($_POST['actualizar_LC'])) {
            // El checkbox 'actualizar_LC' si está marcado
                // Validar y procesar la imagen (foto)
                $imagen_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $formatos_permitidos = array('jpg', 'jpeg', 'png');
                $max_imagen_size = 5 * 1024 * 1024; // 5 MB (tamaño máximo permitido para imágenes)
        
                    if (in_array($imagen_extension, $formatos_permitidos) && $_FILES['foto']['size'] <= $max_imagen_size) {
                        // Cambiar el nombre de la imagen
                        $imagen_nombre_archivo = uniqid() . "." . $imagen_extension;
                        $imagen_dir_subida = '../licencias/';
                        $img_newName = $id_oper . "_" . $imagen_nombre_archivo;
                        $imagen_fichero_subido = $imagen_dir_subida . $img_newName;
        
                        // Verificar que la imagen se haya subido correctamente
                        if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
                            // Mover la imagen al directorio correspondiente
                            if (move_uploaded_file($_FILES['foto']['tmp_name'], $imagen_fichero_subido)) {
                                // La imagen se subió correctamente
                                // Puedes realizar acciones adicionales si es necesario
                                echo "La imagen se subió correctamente.";
                                $actualizar = mysqli_query($conn, "UPDATE operadores SET foto_licencia = '$img_newName' WHERE rut = '$rut'");
                                $actualizarOT = mysqli_query($conn, "UPDATE detallle_ot SET licencia = '$img_newName' WHERE rut = '$rut'");
                            } else {
                                echo "Error al mover la imagen al directorio de destino.";
                            }
                        } else {
                            echo "Error: La imagen no se subió correctamente.";
                        }
                    } else {
                        echo "Solo se permiten imágenes en formato JPG, JPEG o PNG de hasta 5 MB.";
                    }
        } else {
            // El checkbox 'actualizar_LC' no está marcado
            $sql = "UPDATE operadores SET celular = ?, direccion = ?, id_region = ?, id_ciudad = ?, licencia = ? WHERE rut = ?";
            // Preparar la sentencia
            $stmt = mysqli_prepare($conn, $sql);
            $stmt->bind_param("ssssss", $telefono, $direccion, $region, $comuna, $licencia, $rut);
            $stmt->execute();
        }
    }
} else {
    // No se recibieron todos los datos esperados en la solicitud POST
    echo "No se recibieron todos los datos esperados.";
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>