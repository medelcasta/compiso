<?php
session_start();
header("Content-Type: application/json");

echo json_encode([
  "email" => $_SESSION["pendiente_email"] ?? "",
  "usuario" => $_SESSION["pendiente_usuario"] ?? "",
  "link" => $_SESSION["pendiente_link"] ?? ""
]);
?>
