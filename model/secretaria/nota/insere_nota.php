<?php
session_start();
$usuario = $_SESSION['UsuarioNome'];
$id_usuario = $_SESSION['UsuarioID'];

include("../../../base/conexao.php");
include("../../../base/logatvusu.php");

$id_turma           = $_POST["id_turma"];
$id_disc            = $_POST["id_disc"];
$matricula_alu      = $_POST["matricula_alu"];
$aulas_prev         = $_POST["aulas_prev"];
$aulas_dadas        = $_POST["aulas_dadas"];
$trimestre          = $_POST["trimestre"];
$nota               = $_POST["nota"];
$rec                = $_POST["rec"];
$falta              = $_POST["falta"];

foreach($matricula_alu as $index => $matricula){
    foreach ($rec as $index_rec => $nota_rec){
        if(empty($rec[$index_rec])){
            $rec[$index_rec] = "NULL";
        }
    } //Seta as recuperações vazias como null para serem inputadas no sql 
    foreach ($nota as $index_nota => $nota_nota){
        if(empty($nota[$index_nota])){
            $nota[$index_nota] = "NULL";
        }
    } //Seta as notas vazias como null para serem inputadas no sql 
    foreach ($falta as $index_falta => $faltas){
        if(empty($falta[$index_falta])){
            $falta[$index_falta] = "NULL";
        }
    } //Seta as faltas vazias como null para serem inputadas no sql 
    
    switch ($trimestre){
        case 1:
            $sql   = "update boletim set ";
            $sql  .= "nota_1t = ".$nota[$index].", nota_rec_1t = ".$rec[$index].", aulas_prev_1t = ".$aulas_prev.", aulas_dadas_1t = ".$aulas_dadas.", faltas_1t = ".$falta[$index]." ";
            $sql  .= "where matricula_alu = '".$matricula."' ";
            $sql  .= "and id_disc = '".$id_disc."' ";
            $sql  .= "and id_turma = '".$id_turma."'; ";
            $resultado = mysqli_query($conexao, $sql);
        break;
        case 2:
            $sql   = "update boletim set ";
            $sql  .= "nota_2t = ".$nota[$index].", nota_rec_2t = ".$rec[$index].", aulas_prev_2t = ".$aulas_prev.", aulas_dadas_2t = ".$aulas_dadas.", faltas_2t = ".$falta[$index]." ";
            $sql  .= "where matricula_alu = '".$matricula."' ";
            $sql  .= "and id_disc = '".$id_disc."' ";
            $sql  .= "and id_turma = '".$id_turma."'; ";
            $resultado = mysqli_query($conexao, $sql);
        break;
        case 3:
            $sql   = "update boletim set ";
            $sql  .= "nota_3t = ".$nota[$index].", nota_rec_3t = ".$rec[$index].", aulas_prev_3t = ".$aulas_prev.", aulas_dadas_3t = ".$aulas_dadas.", faltas_3t = ".$falta[$index]." ";
            $sql  .= "where matricula_alu = '".$matricula."' ";
            $sql  .= "and id_disc = '".$id_disc."' ";
            $sql  .= "and id_turma = '".$id_turma."'; ";
            $resultado = mysqli_query($conexao, $sql);
        break;
    } 
}
if($resultado){
    //$registra_atv = mysql_query (lau($usuario, str_replace( array("'"), "\'", $sql), $id_usuario));
    mysqli_close($conexao);
    header('Location: lanca_nota.php?msg=1');
    
}else{
    //echo $sql;
    echo "Erro na inserção de dados!<br>".$sql;
    echo mysqli_error($conexao);
	//echo trigger_error(mysql_error());
}

?>