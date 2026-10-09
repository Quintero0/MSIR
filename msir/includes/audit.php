<?php
function addAuditLog($conn, $userID, $activity){
    $sql = "INSERT INTO auditlogs 
    (UserID, Activity, DateCreated)
    VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $userID, $activity);
    mysqli_stmt_execute($stmt);
}
?>