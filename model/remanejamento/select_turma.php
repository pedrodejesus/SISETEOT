<?php
include "../../base/conexao.php";
 
$ano_letivo = $_GET['ano_letivo_corrente'];
 
$rs = mysqli_query($conexao, "SELECT * FROM turma WHERE ano_letivo = '$ano_letivo' ORDER BY numero");
$nr = mysqli_num_rows($rs);

while($data = mysqli_fetch_array($rs)){
    if ($nr > 1){
        echo "<option value='".$data['id_turma']."'>".$data['numero']." / ".$data['ano_letivo']."</option>";
    }else{
        echo "<option value='".$data['id_turma']."' SELECTED>".$data['numero']." / ".$data['ano_letivo']."</option>";
    }
    
}
 
?>