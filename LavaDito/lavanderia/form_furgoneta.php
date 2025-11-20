<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$fur = ['placa' => '', 'modelo' => '', 'capacidad' => '', 'estado' => 'Libre'];
$titulo = "Nueva Furgoneta";

if ($id > 0) {
    $titulo = "Editar Furgoneta";
    $stmt = $conexion->prepare("SELECT * FROM furgonetas WHERE furgoneta_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) $fur = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><?= $titulo ?></h4>
                </div>
                <div class="card-body p-4">
                    <form action="guardar_furgoneta.php" method="POST">
                        <input type="hidden" name="furgoneta_id" value="<?= $id ?>">

                        <div class="mb-3">
                            <label class="form-label">Placa / Matrícula</label>
                            <input type="text" name="placa" class="form-control text-uppercase fw-bold" value="<?= htmlspecialchars($fur['placa']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($fur['modelo']) ?>" required placeholder="Ej: Nissan NV350">
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Capacidad (Kg)</label>
                                <input type="number" step="0.1" name="capacidad" class="form-control" value="<?= htmlspecialchars($fur['capacidad']) ?>" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="Libre" <?= $fur['estado']=='Libre'?'selected':'' ?>>Libre</option>
                                    <option value="Ocupado" <?= $fur['estado']=='Ocupado'?'selected':'' ?>>Ocupado</option>
                                    <option value="Mantenimiento" <?= $fur['estado']=='Mantenimiento'?'selected':'' ?>>Mantenimiento</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-success">Guardar</button>
                            <a href="../lavanderia/ver_furgoneta.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 mb-4">
                <img src="../img/logo-transparente.png" alt="Logo" height="60" style="filter: brightness(0) invert(1);">
                <p class="mt-2 text-secondary">El mejor software para tu lavandería.</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Castillo Alcantar Diego</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Hernández Pérez José Luis</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Limón Jiménez Jorge Alberto</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Vázquez López Ismael</p>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="text-center text-secondary small">
            &copy; 2025 LavaDito. Todos los derechos reservados.
        </div>
    </div>
</footer>
</body>
</html>